<?php

/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM,
 * Copyright (C) 2018-2024 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM"
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo.
 * If the display of the logos is not reasonably feasible for technical reasons, the
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */

namespace MintHCM\Api\Middlewares\Auth;

use Doctrine\ORM\EntityManagerInterface;
use League\OAuth2\Server\Exception\OAuthServerException;
use League\OAuth2\Server\ResourceServer;
use MintHCM\Api\Controllers\OAuth2\Controller;
use MintHCM\Api\Controllers\OAuth2\Server;
use MintHCM\Api\Entities\OAuth2\AccessToken;
use MintHCM\Api\Entities\OAuth2\MintToken;
use MintHCM\Api\Middlewares\Middleware;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Psr7\Response;

#[\AllowDynamicProperties]
class AuthMiddleware extends Middleware
{
    /** @var ResourceServer */
    private $server;

    /** @var EntityManagerInterface */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->server = Server::getResourceServer($entityManager);
        $this->entityManager = $entityManager;
    }
    public function __invoke(Request $request, RequestHandler $handler): Response
    {
        [$runLogic, $optionalAuth] = $this->getAuthOptions($request);
        if ($runLogic || $optionalAuth) {
            $session_cookie_exists = !empty($_COOKIE['PHPSESSID']);
            if (!$session_cookie_exists) {
                $token_result = $this->runTokenValidation($request);
                if ($token_result instanceof Response) {
                    return $token_result;
                }
                $this->setCurrentUserGlobal($token_result);
            } else {
                $validate_legacy = $this->runLegacyAuthorization($request);
                if (!$validate_legacy && !$optionalAuth) {
                    throw new HttpUnauthorizedException($request);
                }
            }
        }
        if (str_contains($request->getRequestTarget(), "api/forget_password")) {
            $this->validateForgotPassword($request, $handler);
        }
        return $handler->handle($request);
    }

    protected function validateForgotPassword(Request $request, RequestHandler $handler)
    {
        $username = $request->getAttribute('username');
        $email = $request->getAttribute('email');
        if (empty(trim($username)) || empty(trim($email))) {
            throw new HttpBadRequestException($request, translate('LBL_MINT4_AUTH_FORGOT_PASSWORD_MISSING_CREDENTIALS_ERROR'));
        }
    }

    private function runTokenValidation(Request $request): Request | Response
    {
        global $mint_app;
        $response = $mint_app->getResponseFactory()->createResponse();
        try {
            $request = $this->server->validateAuthenticatedRequest($request);
        } catch (OAuthServerException $exception) {
            return $exception->generateHttpResponse($response);
            // @codeCoverageIgnoreStart
        } catch (\Exception $exception) {
            return (new OAuthServerException($exception->getMessage(), 0, 'unknown_error', 500))
                ->generateHttpResponse($response);
            // @codeCoverageIgnoreEnd
        }

        return $request;
    }

    private function runLegacyAuthorization(Request $request)
    {
        session_start();
        $token = $_SESSION['oauth_access_token'];

        if (empty($token)) {
            session_destroy();
            return false;
        }

        $request = $request->withHeader('authorization', 'Bearer ' . $token);
        $token_result = $this->runTokenValidation($request);
        if ($token_result instanceof Response && !$this->refreshToken($request)) {
            session_destroy();
            return false;
        }

        if ($token_result instanceof Request && $token_result->getAttribute('oauth_client_id') !== 'frontend') {
            session_destroy();
            return false;
        }

        chdir('../legacy/');
        require_once 'modules/Users/authentication/AuthenticationController.php';
        $sugar_auth = \AuthenticationController::getInstance();
        $authenticated = $sugar_auth->sessionAuthenticate();
        chdir('../api/');
        if ($authenticated) {
            global $api_client;
            $api_client = 'frontend';
            return true;
        }

        return false;
    }

    private function refreshToken(Request $request): bool
    {
        global $mint_app;
        $response = $mint_app->getResponseFactory()->createResponse();

        $request_body = $request->getParsedBody();
        $request_body['grant_type'] = 'refresh_token';
        $request_body['client_id'] = 'frontend';
        $request_body['client_secret'] = $_SESSION['oauth_secrect'] ?? '';
        $request_body['refresh_token'] = $_SESSION['oauth_refresh_token'] ?? '';
        $request = $request->withParsedBody($request_body);
        $oauth_controller = new Controller($this->entityManager);

        $token_response = $oauth_controller->accessToken($request, $response, []);
        if ($token_response->getStatusCode() !== 200) {
            return false;
        }

        $token_body = $token_response->getBody();
        $token_data = json_decode($token_body, true);
        $_SESSION['oauth_access_token'] = $token_data['access_token'];
        $_SESSION['oauth_refresh_token'] = $token_data['refresh_token'];
        return true;
    }

    private function setCurrentUserGlobal(Request $request): void
    {
        $mint_token_repository = $this->entityManager->getRepository(MintToken::class);
        /** @var MintToken */
        $mint_token = $mint_token_repository->findOneBy(['access_token' => $request->getAttribute('oauth_access_token_id')]);
        $user_id = $mint_token->assigned_user_id ?? '';

        global $current_user, $api_client;
        $api_client = $mint_token->client;
        chdir('../legacy/');
        $user = new \User();
        $user->retrieve($user_id);
        if (empty($user->id)) {
            chdir('../api/');
            throw new HttpUnauthorizedException($request, 'User not found');
        }
        $current_user = $user;
        chdir('../api/');
    }

    protected function getAuthOptions(Request $request): array
    {
        $route_data = $this->getRouteData($request);
        return [$route_data['options']['auth'] ?? true, $route_data['options']['optional_auth'] ?? false];
    }
}
