<?php

namespace MintMCP\Auth\Services;

use BeanFactory;
use Exception;
use MintMCP\Handlers\Logger;
use OAuth2Clients;

/**
 * Handles client-related operations
 */
class ClientService
{

    /**
     * Get client by ID
     */
    public function getClientById(string $clientId): ?OAuth2Clients
    {
        return BeanFactory::getBean('OAuth2Clients', $clientId) ?: null;
    }

    /**
     * Check if redirect URI is valid for client
     */
    public function isValidRedirectUri(OAuth2Clients $clientBean, string $redirectUri): bool
    {
        if (!$clientBean) {
            return false;
        }

        $allowedUris = preg_split('/\s+/', $clientBean->redirect_url);
        return in_array($redirectUri, $allowedUris);
    }

    /**
     * Handle Dynamic Client Registration (RFC 7591)
     * 
     * @param array $input Client registration data
     * @return array Registration result
     */
    public function registerClient(array $input): array
    {
        // Required fields for MCP clients
        $registrationData = $this->prepareClientRegistrationData($input);

        try {
            chdir('../legacy');
            $clientBean = BeanFactory::newBean('OAuth2Clients');
            $clientBean->id = $registrationData['clientId'];
            $clientBean->name = $registrationData['clientName'];
            $clientBean->new_with_id = true;
            $clientBean->is_confidential = !empty($registrationData['clientSecret']);
            $clientBean->secret = $registrationData['clientSecret'] ?: '';
            $clientBean->redirect_url = implode(' ', $registrationData['redirectUris']);
            $clientBean->allowed_grant_type = implode(' ', $registrationData['grantTypes']);
            $clientBean->duration_value = 3600;
            $clientBean->duration_amount = 1;
            $clientBean->duration_unit = 'hour';
            $success = $clientBean->save();
            chdir('../mcp');

            if (!$success) {
                throw new Exception('Failed to save client');
            }

            Logger::getLogger()->info('Dynamic client registration successful', [
                'client_id' => $registrationData['clientId'],
                'client_name' => $registrationData['clientName']
            ]);

            // Prepare response according to RFC 7591
            $response = [
                'client_id' => $registrationData['clientId'],
                'client_name' => $registrationData['clientName'],
                'redirect_uris' => $registrationData['redirectUris'],
                'grant_types' => $registrationData['grantTypes'],
                'response_types' => $registrationData['responseTypes'],
                'scope' => $registrationData['scope'],
                'token_endpoint_auth_method' => $registrationData['clientSecret'] ? 'client_secret_post' : 'none',
                'client_id_issued_at' => time(),
            ];

            if ($registrationData['clientSecret']) {
                $response['client_secret'] = $registrationData['clientSecret'];
                $response['client_secret_expires_at'] = 0; // Never expires
            }

            return [
                'status' => 201,
                'data' => $response
            ];
        } catch (Exception $e) {
            Logger::getLogger()->error('Dynamic client registration failed', [
                'error' => $e->getMessage()
            ]);

            return [
                'status' => 500,
                'error' => 'server_error',
                'error_description' => 'Failed to register client'
            ];
        }
    }

    /**
     * Prepare client registration data from input
     */
    private function prepareClientRegistrationData(array $input): array
    {
        $clientName = $input['client_name'] ?? 'MCP Client';
        $redirectUris = $input['redirect_uris'] ?? [];
        $grantTypes = $input['grant_types'] ?? ['authorization_code', 'refresh_token'];
        $responseTypes = $input['response_types'] ?? ['code'];
        $scope = $input['scope'] ?? 'openid profile mcp:read mcp:write';

        // Generate client_id
        $clientId = 'dyn_' . bin2hex(random_bytes(16));

        $clientSecret = null;
        if (in_array('client_secret_basic', $input['token_endpoint_auth_methods'] ?? [])) {
            $clientSecret = bin2hex(random_bytes(32));
        }

        return [
            'clientId' => $clientId,
            'clientName' => $clientName,
            'redirectUris' => $redirectUris,
            'grantTypes' => $grantTypes,
            'responseTypes' => $responseTypes,
            'scope' => $scope,
            'clientSecret' => $clientSecret,
        ];
    }
}
