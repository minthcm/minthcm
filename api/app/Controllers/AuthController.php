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

namespace MintHCM\Api\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use MintHCM\Api\Controllers\OAuth2\Controller;
use MintHCM\Api\Entities\OAuth2\Client;
use MintHCM\Api\Entities\UsersPasswordLink;
use MintHCM\Api\Utils\AuthHelper;
use MintHCM\Lib\Services\Auth\ForgotPasswordThrottle;
use MintHCM\Lib\Services\Auth\SessionTokenRevoker;
use MintHCM\Lib\Services\Http\ClientIpResolver;
use MintHCM\Utils\CustomLoader;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpBadRequestException;
use Slim\Exception\HttpException;
use Slim\Exception\HttpUnauthorizedException;
use Slim\Psr7\Response;
use SuiteCRM\Utility\SessionTimeout;

class AuthController
{
    public const SAML_OAUTH_PENDING_TTL = 300;
    public const OIDC_OAUTH_PENDING_TTL = 300;

    protected EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * Returns the secret of the `frontend` OAuth2 client without authentication.
     *
     * The secret is public by design: the SPA is a public client, so the value cannot be kept
     * confidential and must not be treated as such. Decision: GHSA-h46x-ffpv-f4g3 / #187657,
     * reconfirmed in #193558.
     */
    public function getInternalFrontendToken(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');
        $client = $this->entityManager->getRepository(Client::class)->find('frontend');
        if (empty($client)) {
            throw new HttpUnauthorizedException($request);
        }
        $data = json_encode(['client_secret' => $client->secret]);
        $response->getBody()->write($data);
        return $response;
    }

    public function login(Request $request, Response $response, array $args): Response
    {
        $username = trim($request->getAttribute('username'));
        $password = trim($request->getAttribute('password'));
        $login_language = $request->getAttribute('login_language');

        if (!empty($login_language) && !preg_match('/^\w{2,3}_\w{2,3}$/', $login_language)) {
            throw new HttpBadRequestException($request, 'Invalid language specified');
        }

        $request_body = $request->getParsedBody();
        $request_body['grant_type'] = 'frontend';
        $request_body['client_id'] = 'frontend';
        $request = $request->withParsedBody($request_body);
        $oauth_controller = $this->createOAuthController();
        $token_response = $oauth_controller->accessToken($request, $response, $args);

        if ($token_response->getStatusCode() === 429) {
            throw new HttpException($request, 'Too many failed login attempts.', 429);
        }
        if ($token_response->getStatusCode() !== 200) {
            throw new HttpUnauthorizedException($request);
        }

        chdir('../legacy/');
        require_once 'include/MVC/SugarApplication.php';
        $app = new \SugarApplication();
        $app->startSession();
        require_once 'modules/Users/authentication/SugarAuthenticate/SugarAuthenticateUser.php';
        require_once 'modules/Users/authentication/AuthenticationController.php';

        if ($this->IsLdapOn() && (new \AuthenticationController('LDAPAuthenticate'))->authController->loginAuthenticate($username, $password, false, [])) {
            $loginSuccess = true;
        } else {
            $sugar_auth = \AuthenticationController::getInstance();
            $loginSuccess = $sugar_auth->login($username, $password);
        }
        chdir('../api/');

        if (!$loginSuccess) {
            throw new HttpUnauthorizedException($request);
        }

        $this->regenerateSession();

        if (!empty($login_language)) {
            $_SESSION['authenticated_user_language'] = $login_language;
        }

        $token_body = $token_response->getBody();
        $token_data = json_decode($token_body, true);
        $_SESSION['oauth_access_token'] = $token_data['access_token'];
        $_SESSION['oauth_refresh_token'] = $token_data['refresh_token'];
        $_SESSION['oauth_secrect'] = $request_body['client_secret'];
        $this->startSessionTimeout();

        $response = new Response();
        $response = $response->withHeader('Content-type', 'application/json');
        $data = ['message' => 'Login success'];
        $response->getBody()->write(json_encode($data));
        return $response;
    }

    public function isSamlOAuthPending(): bool
    {
        if (!AuthHelper::isSAML2On()) {
            return false;
        }

        if (empty($_SESSION['saml_oauth_pending']) || empty($_SESSION['saml_oauth_pending_user']) || empty($_SESSION['saml_oauth_pending_at'])) {
            return false;
        }

        $pending_at = (int) $_SESSION['saml_oauth_pending_at'];
        if ((time() - $pending_at) > self::SAML_OAUTH_PENDING_TTL) {
            $this->clearSamlOAuthPending();
            return false;
        }

        return true;
    }

    public function isOIDCOAuthPending(): bool
    {
        if (!AuthHelper::isOIDCOn()) {
            return false;
        }

        if (empty($_SESSION['oidc_oauth_pending']) || empty($_SESSION['oidc_oauth_pending_user']) || empty($_SESSION['oidc_oauth_pending_at'])) {
            return false;
        }

        $pending_at = (int) $_SESSION['oidc_oauth_pending_at'];
        if ((time() - $pending_at) > self::OIDC_OAUTH_PENDING_TTL) {
            $this->clearOIDCOAuthPending();
            return false;
        }

        return true;
    }

    public function finalizeSAMLAuth(Request $request): bool
    {
        if (!$this->isSamlOAuthPending()) {
            $this->clearSamlOAuthPending();
            return false;
        }

        $username = $_SESSION['saml_oauth_pending_user'];

        $client = $this->entityManager->getRepository(Client::class)->find('frontend');
        if (empty($client)) {
            $this->clearSamlOAuthPending();
            return false;
        }

        $request_body = [];
        $request_body['username'] = $username;
        $request_body['password'] = '';
        $request_body['grant_type'] = 'frontend';
        $request_body['client_id'] = 'frontend';
        $request_body['client_secret'] = $client->secret;
        $request = $request->withParsedBody($request_body);
        
        $oauth_controller = $this->createOAuthController();
        $token_response = $oauth_controller->accessToken($request, new Response(), []);

        if ($token_response->getStatusCode() !== 200) {
            $this->clearSamlOAuthPending();
            return false;
        }

        $token_data = json_decode((string) $token_response->getBody(), true);
        $this->regenerateSession();
        $_SESSION['oauth_access_token'] = $token_data['access_token'];
        $_SESSION['oauth_refresh_token'] = $token_data['refresh_token'];
        $_SESSION['oauth_secrect'] = $request_body['client_secret'];
        $this->startSessionTimeout();
        $this->clearSamlOAuthPending();
        return true;
    }

    public function finalizeOIDCAuth(Request $request): bool
    {
        if (!$this->isOIDCOAuthPending()) {
            $this->clearOIDCOAuthPending();
            return false;
        }

        $username = $_SESSION['oidc_oauth_pending_user'];

        $client = $this->entityManager->getRepository(Client::class)->find('frontend');
        if (empty($client)) {
            $this->clearOIDCOAuthPending();
            return false;
        }

        $request_body = [];
        $request_body['username'] = $username;
        $request_body['password'] = '';
        $request_body['grant_type'] = 'frontend';
        $request_body['client_id'] = 'frontend';
        $request_body['client_secret'] = $client->secret;
        $request = $request->withParsedBody($request_body);

        $oauth_controller = $this->createOAuthController();
        $token_response = $oauth_controller->accessToken($request, new Response(), []);
        $token_body = (string) $token_response->getBody();

        if ($token_response->getStatusCode() !== 200) {
            $this->clearOIDCOAuthPending();
            return false;
        }

        $token_data = json_decode($token_body, true);
        $this->regenerateSession();
        $_SESSION['oauth_access_token'] = $token_data['access_token'];
        $_SESSION['oauth_refresh_token'] = $token_data['refresh_token'];
        $_SESSION['oauth_secrect'] = $request_body['client_secret'];
        $this->startSessionTimeout();
        $this->clearOIDCOAuthPending();
        return true;
    }

    protected function createOAuthController(): Controller
    {
        return new Controller($this->entityManager);
    }

    /**
     * Issues a new session id once the user is authenticated (session fixation defence).
     * The session data is kept, only the identifier changes.
     */
    protected function regenerateSession(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE && !headers_sent()) {
            session_regenerate_id(true);
        }
    }

    private function IsLdapOn()
    {
        global $system_config;
        return !empty($system_config->settings['system_ldap_enabled']) && $system_config->settings['system_ldap_enabled'] == true;
    }

    private function clearSamlOAuthPending(): void
    {
        unset($_SESSION['saml_oauth_pending']);
        unset($_SESSION['saml_oauth_pending_user']);
        unset($_SESSION['saml_oauth_pending_at']);
    }

    private function clearOIDCOAuthPending(): void
    {
        unset($_SESSION['oidc_oauth_pending']);
        unset($_SESSION['oidc_oauth_pending_user']);
        unset($_SESSION['oidc_oauth_pending_at']);
        unset($_SESSION['oidc_oauth_pending_ttl']);
    }


    public function logout(Request $request, Response $response, array $args): Response
    {
        global $current_user;

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $this->revokeSessionTokens($request);

        chdir('../legacy/');
        $current_user->call_custom_logic('before_logout');

        require_once 'modules/Users/authentication/AuthenticationController.php';
        /** @var \AuthenticationController $authController */
        $authController = \AuthenticationController::getInstance();
        if (method_exists($authController->authController, 'preLogout')) {
            $authController->authController->preLogout();
        }

        foreach ($_SESSION as $key => $val) {
            $_SESSION[$key] = '';
        }
        $_COOKIE['PHPSESSID'] = "";
        session_destroy();
        $current_user->call_custom_logic('after_logout');
        $url = $authController->authController->logout(true);
        if (!empty($url)) {
            $response->getBody()->write(json_encode(['redirect_url' => $url]));
        }
        chdir('../api/');
        return $response;
    }

    /**
     * Revokes the OAuth token pair behind the current session (or Bearer header),
     * so a stolen token stops working at logout instead of at its natural expiry.
     */
    private function revokeSessionTokens(Request $request): void
    {
        global $current_user;

        $revoker = CustomLoader::getObject(SessionTokenRevoker::class);

        // The session token was put there by the server - no ownership check. It is needed only for a
        // token from the request: frontend-grant tokens have no assigned_user_id, so checking it would
        // skip exactly the browser sessions.
        $session_jwt = $_SESSION['oauth_access_token'] ?? '';
        if (!empty($session_jwt)) {
            $revoker->revoke($session_jwt);
            return;
        }

        $bearer_jwt = preg_replace('/^Bearer\s+/i', '', $request->getHeaderLine('Authorization'));
        if (!empty($bearer_jwt) && !empty($current_user->id)) {
            $revoker->revoke($bearer_jwt, $current_user->id);
        }
    }

    /**
     * Sets the idle / absolute timeout markers of a freshly logged in browser session.
     */
    protected function startSessionTimeout(): void
    {
        (new SessionTimeout())->start($_SESSION, time());
    }

    public function forgetPassword(Request $request, Response $response, array $args): Response
    {
        $response = $response->withHeader('Content-type', 'application/json');

        $username = (string) $request->getAttribute('username');
        $email = $request->getAttribute('email');

        $ip = $this->getClientIpResolver()->resolve($request->getServerParams());
        if (!$this->getForgotPasswordThrottle()->isAllowed($username, $ip)) {
            throw new HttpException($request, 'Too many password reset requests.', 429);
        }

        chdir('../legacy/');
        $user = new \User();
        $user_id = $user->retrieve_user_id($username);
        $user->retrieve($user_id);
        $is_primary_email = !empty($user) ? $user->isPrimaryEmail($email) : false;
        chdir('../api/');

        $is_valid_request = !empty($user->id)
            && $user->id === $user_id
            && $is_primary_email
            && !$user->portal_only
            && !$user->is_group;

        // The reset link is stored and e-mailed after the response has been sent: doing it before would
        // make the answer for a valid login + e-mail pair measurably slower (SMTP), revealing the account.
        if ($is_valid_request) {
            $this->runAfterResponse(function () use ($user, $username, $user_id): void {
                $this->sendPasswordResetLink($user, $username, $user_id);
            });
        }

        $response->getBody()->write(json_encode(array('message' => 'LBL_MINT4_AUTH_FORGET_SUCCESS')));
        return $response;
    }

    protected function sendPasswordResetLink(\User $user, string $username, string $user_id): void
    {
        global $sugar_config;

        $usersPasswordLink = new UsersPasswordLink();
        $usersPasswordLink->username = $username;
        $usersPasswordLink->user_id = $user_id;
        $usersPasswordLink->deleted = 0;
        $usersPasswordLink->date_generated = date('Y-m-d H:i:s');
        $this->entityManager->persist($usersPasswordLink);
        $this->entityManager->flush();

        $emailTemp_id = $sugar_config['passwordsetting']['lostpasswordtmpl'];

        $url = $sugar_config['site_url'] . "/#/auth/reset?token={$usersPasswordLink->id}";
        $additionalData = array(
            'link' => true,
            'password' => '',
            'url' => $url,
        );

        chdir('../legacy/');
        try {
            $result = $user->sendEmailForPassword($emailTemp_id, $additionalData);
        } finally {
            chdir('../api/');
        }

        if (true !== $result['status']) {
            $GLOBALS['log']->error("Password reset e-mail for user {$user_id} was not sent: " . ($result['message'] ?? ''));
        }
    }

    /**
     * Runs the task once the response is on its way to the client. Needs PHP-FPM
     * (fastcgi_finish_request); under other SAPIs (mod_php) the task runs right away, before the response.
     * A failing task is logged, never turned into an error response - in either case the response must
     * not depend on what the task did.
     */
    protected function runAfterResponse(callable $task): void
    {
        $guarded_task = function () use ($task): void {
            try {
                $task();
            } catch (\Throwable $exception) {
                $GLOBALS['log']->fatal('Deferred task failed: ' . $exception->getMessage());
            }
        };

        if (!function_exists('fastcgi_finish_request')) {
            $guarded_task();
            return;
        }

        $working_directory = getcwd();
        register_shutdown_function(function () use ($guarded_task, $working_directory): void {
            fastcgi_finish_request();
            // some SAPIs change the working directory before shutdown functions run
            chdir($working_directory);
            $guarded_task();
        });
    }

    protected function getForgotPasswordThrottle(): ForgotPasswordThrottle
    {
        return CustomLoader::getObject(ForgotPasswordThrottle::class);
    }

    protected function getClientIpResolver(): ClientIpResolver
    {
        return CustomLoader::getObject(ClientIpResolver::class);
    }

    public function validToken(Request $request, Response $response, array $args): Response
    {
        global $sugar_config;

        $response = $response->withHeader('Content-type', 'application/json');

        $usersPasswordLink = $this->entityManager->getRepository(UsersPasswordLink::class)
            ->findOneById($request->getAttribute('token'));
        $errorResponse = $this->getPasswordLinkErrorResponse($usersPasswordLink, $response);
        if ($errorResponse !== null) {
            return $errorResponse;
        }

        $pwd_settings = $sugar_config['passwordsetting'];
        $response->getBody()->write(json_encode(array(
            'username' => $usersPasswordLink->username,
            'password_settings' => array(
                "oneupper" => !empty($pwd_settings['oneupper']) ? true : false,
                "onelower" => !empty($pwd_settings['onelower']) ? true : false,
                "onenumber" => !empty($pwd_settings['onenumber']) ? true : false,
                "onespecial" => !empty($pwd_settings['onespecial']) ? true : false,
                "minpwdlength" => !empty($pwd_settings['minpwdlength']) ? (int) $pwd_settings['minpwdlength'] : false,
            ),

        )));

        return $response;
    }

    public function resetForgetPassword(Request $request, Response $response, array $args): Response
    {
        global $mod_strings;

        $new_password = $request->getAttribute('new_password');

        $response = $response->withHeader('Content-type', 'application/json');

        $usersPasswordLink = $this->entityManager->getRepository(UsersPasswordLink::class)
            ->findOneById($request->getAttribute('token'));
        $errorResponse = $this->getPasswordLinkErrorResponse($usersPasswordLink, $response);
        if ($errorResponse !== null) {
            return $errorResponse;
        }

        // The target account is always the token owner, never the username sent in the request
        $username = $usersPasswordLink->username;

        // The token is single-use: invalidate it on first use, regardless of the outcome
        $this->entityManager->getRepository(UsersPasswordLink::class)
            ->markAllAsDeletedByUsername($username);

        if ($request->getAttribute('username') !== $username) {
            $GLOBALS['log']->warn(
                "Password reset rejected: username from request does not match the token owner ({$username}) - possible manipulation attempt"
            );
            $response = $response->withStatus(400);
            $response->getBody()->write(json_encode(array('message' => 'LBL_TOKEN_USERNAME_MISMATCH')));
            return $response;
        }

        chdir('../legacy/');
        $user = new \User();
        $mod_strings = return_module_language($GLOBALS['current_language'], 'Users');
        $errors = $user->passwordValidationCheck($new_password);
        chdir('../api/');

        if (!empty($errors)) {
            $response = $response->withStatus(400);
            $response->getBody()->write(json_encode(array('message' => $errors)));
            return $response;
        }

        chdir('../legacy/');
        $user_id = $user->retrieve_user_id($username);
        $user->retrieve($user_id);
        $user->setNewPassword($new_password);
        chdir('../api/');

        return $response;
    }

    protected function getPasswordLinkErrorResponse(?UsersPasswordLink $usersPasswordLink, Response $response): ?Response
    {
        global $sugar_config;

        if (!$usersPasswordLink) {
            return $response->withStatus(400);
        }

        $pwd_settings = $sugar_config['passwordsetting'];
        $expired = false;
        if ($pwd_settings['linkexpiration']) {
            $delay = $pwd_settings['linkexpirationtime'] * $pwd_settings['linkexpirationtype'];
            $stim = $usersPasswordLink->date_generated->getTimestamp() + date('Z');
            $expiretime = \TimeDate::getInstance()->fromTimestamp($stim)->get("+$delay  minutes")->asDb();
            $timenow = \TimeDate::getInstance()->nowDb();
            if ($timenow > $expiretime) {
                $expired = true;
            }
        }

        if ($expired) {
            $response = $response->withStatus(403);
            $response->getBody()->write(json_encode(array('message' => 'LBL_TOKEN_EXPIRED')));
            return $response;
        }

        if ($usersPasswordLink->deleted) {
            $response = $response->withStatus(403);
            $response->getBody()->write(json_encode(array('message' => 'LBL_TOKEN_USED')));
            return $response;
        }

        return null;
    }

    public function confirmLoginWizard(Request $request, Response $response, array $args): Response
    {
        $first_name = $request->getAttribute('first_name');
        $last_name = $request->getAttribute('last_name');
        $email = $request->getAttribute('email');
        $preferences = [];
        $preferences['timezone'] = $request->getAttribute('time_zone');
        $preferences['timef'] = $request->getAttribute('time_format');
        $preferences['datef'] = $request->getAttribute('date_format');
        $preferences['default_locale_name_format'] = $request->getAttribute('display_name_format');

        $response = new Response();
        $response = $response->withHeader('Content-type', 'application/json');

        global $current_user;
        if (empty($current_user->id)) {
            $response = $response->withStatus(403);
            return $response;
        }
        chdir('../legacy/');
        $current_user->first_name = $first_name;
        $current_user->last_name = $last_name;
        $current_user->email1 = $email;

        $current_user->save(false);

        foreach ($preferences as $k => $v) {
            if (!empty($v)) {
                $current_user->setPreference($k, $v, 0, 'global');
            }
        }
        $current_user->setPreference('ut', '1', 0, 'global');

        chdir('../api/');
        return $response;
    }
}
