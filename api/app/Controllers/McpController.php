<?php

namespace MintHCM\Api\Controllers;

use MintMCP\Auth\OAuth2Server;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Psr7\Response;
use Throwable;

class McpController
{
    /**
     * Returns the pending OAuth flow's client_name, scope and consent_token
     * plus the authenticated user's display name (if any). The SPA uses this
     * to decide whether to render the login form or the consent screen, and
     * gets the CSRF consent_token needed for /mcp/authorize and /mcp/deny.
     */
    public function oauthInfo(Request $request, Response $response): Response
    {
        $this->initLegacySession();

        $flowId = $this->getFlowId($request);
        $flow = $this->getFlow($flowId);
        if ($flow === null) {
            return $this->writeJson($response, [
                'error' => 'invalid_request',
                'error_description' => 'No OAuth session found or it has expired',
            ], 400);
        }

        return $this->writeJson($response, [
            'client_name'           => $flow['client_name'] ?? '',
            'scope'                 => $flow['scope'] ?? '',
            'consent_token'         => $flow['consent_token'] ?? '',
            'authenticated_user'    => $this->getAuthenticatedUserName(),
        ], 200, true);
    }

    /**
     * Completes the OAuth2 authorization flow after the user has clicked
     * "Authorize" on the SPA consent page. Validates the CSRF consent token,
     * confirms the session user, and asks OAuth2Server to issue the code.
     */
    public function authorize(Request $request, Response $response): Response
    {
        $this->initLegacySession();

        $flowId = $this->getFlowId($request);
        $flow = $this->getFlow($flowId);
        if ($flow === null) {
            return $this->writeJson($response, [
                'error' => 'invalid_request',
                'error_description' => 'No OAuth session found or it has expired',
            ], 400);
        }

        $userId = (string) ($_SESSION['authenticated_user_id'] ?? '');
        if ($userId === '') {
            return $this->writeJson($response, [
                'error' => 'unauthorized',
                'error_description' => 'User not authenticated',
            ], 401);
        }

        $body = (array) $request->getParsedBody();
        if (!$this->consentTokenValid($flow, $body['consent_token'] ?? null)) {
            return $this->writeJson($response, [
                'error' => 'invalid_request',
                'error_description' => 'Invalid or missing consent token',
            ], 403);
        }

        try {
            require_once __DIR__ . '/../../../mcp/bootstrap.php';
            chdir('../mcp/');
            $result = OAuth2Server::getInstance()->completeAuthorization($flow, $userId);
        } catch (Throwable) {
            return $this->writeJson($response, [
                'error' => 'server_error',
                'error_description' => 'Authorization failed',
            ], 500);
        } finally {
            chdir('../api/');
        }

        if (($result['status'] ?? 0) !== 302 || empty($result['headers']['Location'])) {
            return $this->writeJson($response, [
                'error' => 'server_error',
                'error_description' => 'Authorization failed',
            ], 500);
        }

        $this->clearFlow($flowId);

        return $this->writeJson($response, [
            'redirect_url' => $result['headers']['Location'],
        ]);
    }

    /**
     * Denies the pending authorization and returns a redirect URL that sends
     * the OAuth client an `access_denied` error per RFC 6749 §4.1.2.1. The
     * redirect_uri is re-validated against the registered client so a stale
     * or tampered session cannot turn this into an open redirect.
     */
    public function deny(Request $request, Response $response): Response
    {
        $this->initLegacySession();

        $flowId = $this->getFlowId($request);
        $flow = $this->getFlow($flowId);
        if ($flow === null) {
            return $this->writeJson($response, [
                'error' => 'invalid_request',
                'error_description' => 'No OAuth session found or it has expired',
            ], 400);
        }

        $body = (array) $request->getParsedBody();
        if (!$this->consentTokenValid($flow, $body['consent_token'] ?? null)) {
            return $this->writeJson($response, [
                'error' => 'invalid_request',
                'error_description' => 'Invalid or missing consent token',
            ], 403);
        }

        try {
            require_once __DIR__ . '/../../../mcp/bootstrap.php';
            chdir('../legacy/');
            $clientService = new \MintMCP\Auth\Services\ClientService();
            $clientBean = $clientService->getClientById((string) ($flow['client_id'] ?? ''));
            $redirectValid = $clientBean
                && $clientService->isValidRedirectUri($clientBean, (string) ($flow['redirect_uri'] ?? ''));
        } catch (Throwable) {
            $redirectValid = false;
        } finally {
            chdir('../api/');
        }

        $this->clearFlow($flowId);

        if (!$redirectValid) {
            return $this->writeJson($response, [
                'error' => 'invalid_request',
                'error_description' => 'Invalid redirect_uri',
            ], 400);
        }

        $redirectUrl = $flow['redirect_uri'] . '?' . http_build_query([
            'error' => 'access_denied',
            'error_description' => 'The user denied the request.',
            'state' => $flow['state'] ?? '',
        ]);

        return $this->writeJson($response, [
            'redirect_url' => $redirectUrl,
        ]);
    }

    private function initLegacySession(): void
    {
        chdir('../legacy/');
        try {
            require_once 'include/MVC/SugarApplication.php';
            (new \SugarApplication())->startSession();
        } finally {
            chdir('../api/');
        }
    }

    /**
     * Return a display name for the currently authenticated session user, or
     * empty string when the session is not authenticated.
     */
    private function getAuthenticatedUserName(): string
    {
        $userId = (string) ($_SESSION['authenticated_user_id'] ?? '');
        if ($userId === '') {
            return '';
        }

        try {
            chdir('../legacy/');
            $user = \BeanFactory::getBean('Users', $userId);
            $name = $user && !empty($user->id)
                ? (string) ($user->full_name ?: $user->user_name)
                : '';
        } catch (Throwable) {
            $name = '';
        } finally {
            chdir('../api/');
        }

        return $name;
    }

    private function getFlowId(Request $request): string
    {
        $query = $request->getQueryParams();
        $flowId = is_string($query['flow'] ?? null) ? $query['flow'] : '';
        if ($flowId === '') {
            $body = (array) $request->getParsedBody();
            $flowId = is_string($body['flow'] ?? null) ? $body['flow'] : '';
        }
        return $flowId;
    }

    private function getFlow(string $flowId): ?array
    {
        if ($flowId === '') {
            return null;
        }
        $flow = $_SESSION['oauth_flows'][$flowId] ?? null;
        if (!is_array($flow)) {
            return null;
        }
        if (($flow['expires_at'] ?? 0) < time()) {
            unset($_SESSION['oauth_flows'][$flowId]);
            return null;
        }
        return $flow;
    }

    private function clearFlow(string $flowId): void
    {
        if ($flowId !== '') {
            unset($_SESSION['oauth_flows'][$flowId]);
        }
    }

    private function consentTokenValid(array $flow, $provided): bool
    {
        $expected = (string) ($flow['consent_token'] ?? '');
        $given = is_string($provided) ? $provided : '';
        return $expected !== '' && hash_equals($expected, $given);
    }

    private function writeJson(Response $response, array $payload, int $status = 200, bool $noStore = false): Response
    {
        $response = $response->withHeader('Content-Type', 'application/json');
        if ($noStore) {
            // RFC 6749 §5.1: responses carrying credentials/secrets must not be cached.
            $response = $response
                ->withHeader('Cache-Control', 'no-store')
                ->withHeader('Pragma', 'no-cache');
        }
        $response->getBody()->write(json_encode($payload));
        return $response->withStatus($status);
    }
}
