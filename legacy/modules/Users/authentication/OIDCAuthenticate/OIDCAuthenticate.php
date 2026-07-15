<?php
/**
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
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once __DIR__ . '/../../../../modules/Users/authentication/SugarAuthenticate/SugarAuthenticate.php';

/**
 * Class OIDCAuthenticate
 *
 * Implements OpenID Connect (OIDC) authentication for MintHCM.
 * Mirrors the SAML2Authenticate pattern: pre_login() builds the authorization
 * URL or handles the callback; redirectToLogin() sets the oauth-pending session
 * markers used by the API layer to finalize the token exchange.
 *
 * Required sugar_config keys:
 *   OIDC_clientId             – OAuth 2.0 client identifier
 *   OIDC_clientSecret         – OAuth 2.0 client secret
 *   OIDC_authorizationEndpoint – IdP authorization endpoint URL
 *   OIDC_tokenEndpoint        – IdP token endpoint URL
 *   OIDC_scope                – Space-separated scopes (default: "openid profile email")
 *   OIDC_usernameClaim        – ID-token claim used as MintHCM username
 *                               (default: "preferred_username")
 *
 * Optional:
 *   OIDC_logoutEndpoint       – IdP end-session endpoint for RP-Initiated Logout
 */
class OIDCAuthenticate extends SugarAuthenticate
{
    private const OIDC_OAUTH_PENDING_TTL = 300;

    public $userAuthenticateClass = 'OIDCAuthenticateUser';
    public $authenticationDir = 'OIDCAuthenticate';

    /** @var string|null ID token saved by preLogout() for RP-Initiated Logout */
    private $logout_id_token = null;

    /**
     * Pre-login hook called by SugarApplication / AuthenticationController.
     *
     * When no authorization code is present the user is redirected to the IdP.
     * When the IdP returns with ?code=… the code is exchanged for tokens and
     * the authenticated user is loaded into the session.
     *
     * @param bool $stay  When true the redirect URL is returned instead of
     *                    executing the redirect (used by the API layer).
     * @return string|null  Authorization URL when $stay is true, null otherwise.
     */
    public function pre_login(bool $stay = false)
    {
        // A previous OIDC attempt resolved an identity that is not permitted to log in.
        // Render the login page with the error instead of bouncing back to the IdP, which
        // would otherwise loop indefinitely (callback → load fails → redirect → callback …).
        if (!empty($_SESSION['oidc_auth_aborted']) || !empty($_COOKIE['oidc_auth_aborted'])) {
            unset($_SESSION['oidc_auth_aborted']);
            $this->clearAbortedCookie();
            return null;
        }

        if (!empty($_GET['code'])) {
            $username = $this->handleCallback((string) $_GET['code']);
            if ($username) {
                $this->redirectToLogin($GLOBALS['app']);
            } else {
                $GLOBALS['log']->warn('OIDCAuthenticate: callback handling failed, redirecting to login');
                SugarApplication::redirect('index.php?module=Users&action=Login');
            }
            return null;
        }

        $url = $this->buildAuthorizationUrl();
        if ($stay) {
            return $url;
        }
        $this->redirectToIdp($url);
        return null;
    }

    /**
     * Redirects the browser to the IdP authorization endpoint with a real Location header.
     *
     * SugarApplication::redirect() must NOT be used here: it is built for internal legacy/SPA
     * targets and prepends the local origin + path to the URL, which corrupts an absolute
     * external IdP URL (e.g. "https://host/legacy/https://idp/authorize?…") and bounces the
     * user straight back into Mint without ever reaching the IdP. The session is flushed first
     * so the freshly stored oidc_state survives for the callback.
     */
    private function redirectToIdp(string $url): void
    {
        session_write_close();
        if (!headers_sent()) {
            header('Location: ' . $url, true, 302);
        } else {
            echo '<script type="text/javascript">window.location.replace(' . json_encode($url) . ');</script>';
        }
        if (!defined('SUITE_PHPUNIT_RUNNER')) {
            exit();
        }
    }

    /**
     * Exchanges the authorization code for tokens and stores the user identity
     * in the session.
     *
     * @param string $code  Authorization code received from the IdP.
     * @return string  Resolved username on success, empty string on failure.
     */
    private function handleCallback(string $code): string
    {
        global $sugar_config;

        if (!$this->validateState()) {
            return '';
        }

        $token_data = $this->fetchTokens($code);
        if (empty($token_data['id_token'])) {
            $GLOBALS['log']->error('OIDCAuthenticate: no id_token in token endpoint response');
            return '';
        }

        $claims = $this->decodeIdToken($token_data['id_token']);
        if (empty($claims)) {
            $GLOBALS['log']->error('OIDCAuthenticate: failed to decode id_token');
            return '';
        }

        $username_claim = !empty($sugar_config['OIDC_usernameClaim']) ? $sugar_config['OIDC_usernameClaim'] : 'preferred_username';
        $username = $claims[$username_claim] ?? '';

        if (empty($username)) {
            $GLOBALS['log']->error('OIDCAuthenticate: claim "' . $username_claim . '" not found in id_token');
            return '';
        }

        $_SESSION['oidcUserdata'] = $claims;
        $_SESSION['oidcNameId'] = $username;
        $_SESSION['oidcIdToken'] = $token_data['id_token'];

        return $username;
    }

    /**
     * Validates the OAuth 2.0 state parameter to prevent CSRF / login code injection.
     *
     * The expected value is read from the PHP session, falling back to the
     * double-submit `oidc_state` cookie so the check still works when the server-side
     * session is lost (e.g. load balancer without sticky/shared sessions, restart).
     * A missing or mismatched state is always rejected.
     */
    private function validateState(): bool
    {
        $received_state = (string) ($_GET['state'] ?? '');
        $expected_state = (string) ($_SESSION['oidc_state'] ?? $_COOKIE['oidc_state'] ?? '');

        // State is single-use: clear both the session copy and the browser cookie
        // regardless of the outcome so it cannot be replayed.
        unset($_SESSION['oidc_state']);
        $this->clearStateCookie();

        if ($expected_state === '' || !hash_equals($expected_state, $received_state)) {
            $GLOBALS['log']->warn('OIDCAuthenticate: missing or mismatched state – rejecting callback (possible CSRF attempt)');
            return false;
        }
        return true;
    }

    /**
     * Stores the CSRF state in a short-lived, browser-bound cookie (double-submit).
     * The value lives in the browser, so the binding survives loss of the server-side
     * session. SameSite=Lax keeps the cookie attached to the top-level GET redirect the
     * IdP sends back to the callback.
     */
    private function setStateCookie(string $state): void
    {
        if (!headers_sent()) {
            setcookie('oidc_state', $state, [
                'expires' => time() + self::OIDC_OAUTH_PENDING_TTL,
                'path' => '/',
                'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        }
        $_COOKIE['oidc_state'] = $state;
    }

    /**
     * Expires the double-submit state cookie (single-use).
     */
    private function clearStateCookie(): void
    {
        if (!headers_sent()) {
            setcookie('oidc_state', '', ['expires' => time() - 3600, 'path' => '/']);
        }
        unset($_COOKIE['oidc_state']);
    }

    /**
     * Mirrors oidc_auth_aborted into a short-lived cookie so the redirect loop
     * guard in pre_login() survives loss of the server-side session (e.g. load
     * balancer without sticky/shared sessions) between the abort and the next request.
     */
    private function setAbortedCookie(): void
    {
        if (!headers_sent()) {
            setcookie('oidc_auth_aborted', '1', [
                'expires' => time() + self::OIDC_OAUTH_PENDING_TTL,
                'path' => '/',
                'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
                'httponly' => true,
                'samesite' => 'Lax',
            ]);
        }
        $_COOKIE['oidc_auth_aborted'] = '1';
    }

    /**
     * Expires the double-submit aborted-auth cookie (single-use).
     */
    private function clearAbortedCookie(): void
    {
        if (!headers_sent()) {
            setcookie('oidc_auth_aborted', '', ['expires' => time() - 3600, 'path' => '/']);
        }
        unset($_COOKIE['oidc_auth_aborted']);
    }

    /**
     * Performs the authorization_code token exchange against the IdP token endpoint.
     *
     * @param string $code
     * @return array Decoded JSON response from the token endpoint.
     */
    private function fetchTokens(string $code): array
    {
        global $sugar_config;

        $token_endpoint = $sugar_config['OIDC_tokenEndpoint'] ?? '';
        $client_id = $sugar_config['OIDC_clientId'] ?? '';
        $client_secret = $sugar_config['OIDC_clientSecret'] ?? '';
        $redirect_uri = $this->getRedirectUri();

        $GLOBALS['log']->info('OIDCAuthenticate: token request to "' . $token_endpoint . '" with client_id="' . $client_id . '" redirect_uri="' . $redirect_uri . '"');

        $ch = curl_init($token_endpoint);
        if ($ch === false) {
            $GLOBALS['log']->error('OIDCAuthenticate: curl_init() failed for token endpoint "' . $token_endpoint . '"');
            return [];
        }

        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => http_build_query([
                'grant_type' => 'authorization_code',
                'code' => $code,
                'redirect_uri' => $redirect_uri,
                'client_id' => $client_id,
                'client_secret' => $client_secret,
            ]),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Content-Type: application/x-www-form-urlencoded',
                'Accept: application/json',
            ],
        ]);

        $result = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($result === false || $http_code !== 200) {
            $GLOBALS['log']->error('OIDCAuthenticate: token endpoint returned HTTP ' . $http_code . ' body: ' . $result);
            return [];
        }

        return json_decode($result, true) ?: [];
    }

    /**
     * Decodes an ID token (JWT) payload without signature verification.
     *
     * Signature verification is intentionally skipped because the token was
     * received directly from the IdP token endpoint over TLS, which provides
     * equivalent authenticity guarantees for confidential clients.
     *
     * @param string $id_token  Compact JWT string.
     * @return array Decoded claims, or empty array on failure.
     */
    private function decodeIdToken(string $id_token): array
    {
        $parts = explode('.', $id_token);
        if (count($parts) !== 3) {
            return [];
        }

        $payload = base64_decode(strtr($parts[1], '-_', '+/'), true);
        if ($payload === false) {
            return [];
        }

        $claims = json_decode($payload, true);
        return is_array($claims) ? $claims : [];
    }

    /**
     * Builds the OIDC authorization URL and stores a random state value in the
     * session to prevent CSRF attacks.
     *
     * @return string Full authorization URL including query parameters.
     */
    private function buildAuthorizationUrl(): string
    {
        global $sugar_config;

        $auth_endpoint = $sugar_config['OIDC_authorizationEndpoint'] ?? '';
        $client_id = $sugar_config['OIDC_clientId'] ?? '';
        $scope = !empty($sugar_config['OIDC_scope']) ? $sugar_config['OIDC_scope'] : 'openid profile email';

        $state = bin2hex(random_bytes(32));
        $_SESSION['oidc_state'] = $state;
        // Mirror the state into a short-lived browser cookie (double-submit) so CSRF
        // protection survives PHP session loss without being weakened: the value lives
        // in the browser, not server-side, yet stays bound to this user agent.
        $this->setStateCookie($state);

        return $auth_endpoint . '?' . http_build_query([
            'response_type' => 'code',
            'client_id' => $client_id,
            'redirect_uri' => $this->getRedirectUri(),
            'scope' => $scope,
            'state' => $state,
        ]);
    }

    /**
     * Overrides SugarAuthenticate to authenticate via the OIDC session data.
     * Sets the oauth-pending session markers read by the API layer.
     *
     * @param SugarApplication $app
     * @return bool
     */
    public function redirectToLogin(SugarApplication $app)
    {
        if (!isset($_SESSION['oidcNameId']) || empty($_SESSION['oidcNameId'])) {
            return false;
        }

        if (!$this->userAuthenticate->loadUserOnLogin($_SESSION['oidcNameId'], null)) {
            // The identity authenticated at the IdP but no matching active MintHCM user exists.
            // Abort with an explicit error instead of redirecting to LoggedOut, which would
            // silently bounce back to the IdP and loop indefinitely, leaving the user stuck
            // with no feedback.
            $GLOBALS['log']->fatal('SECURITY: OIDC identity "' . $_SESSION['oidcNameId'] . '" is not permitted to log in (no matching active user)');
            unset($_SESSION['oidcUserdata'], $_SESSION['oidcNameId'], $_SESSION['oidcIdToken']);
            $_SESSION['login_error'] = 'Your account is not permitted to sign in via single sign-on. Please contact your administrator.';
            $_SESSION['oidc_auth_aborted'] = true;
            $this->setAbortedCookie();
            SugarApplication::redirect('index.php?module=Users&action=Login');
            return false;
        }

        global $authController;
        $auth = $authController->login($_SESSION['oidcNameId'], null);
        if ($auth) {
            $_SESSION['oidc_oauth_pending'] = true;
            $_SESSION['oidc_oauth_pending_user'] = $_SESSION['oidcNameId'];
            $_SESSION['oidc_oauth_pending_at'] = time();
            $_SESSION['oidc_oauth_pending_ttl'] = self::OIDC_OAUTH_PENDING_TTL;
        }

        SugarApplication::redirect('index.php?module=Users&action=LoggedOut');
        return true;
    }

    /**
     * Performs RP-Initiated Logout if an end-session endpoint is configured.
     *
     * @param bool $stay  When true returns the logout URL instead of redirecting.
     * @return string|null  Logout URL when $stay is true, null otherwise.
     */
    public function logout(bool $stay = false)
    {
        global $sugar_config;

        $logout_endpoint = $sugar_config['OIDC_logoutEndpoint'] ?? '';
        if (!empty($logout_endpoint)) {
            $params = [
                'post_logout_redirect_uri' => $sugar_config['site_url'] . '/legacy/index.php?module=Users&action=Login',
            ];
            if (!empty($this->logout_id_token)) {
                $params['id_token_hint'] = $this->logout_id_token;
            }

            $logout_url = $logout_endpoint . '?' . http_build_query($params);
            if ($stay) {
                return $logout_url;
            }
            SugarApplication::redirect($logout_url);
            return null;
        }

        if (!$stay) {
            SugarApplication::redirect('index.php');
        }
        return null;
    }

    /**
     * Called before session destruction during logout.
     * Saves the id_token to an instance variable so it survives session_destroy()
     * and can be passed to the IdP end-session endpoint in logout().
     * This mirrors how SAML2Authenticate::preLogout() stores its logout args.
     */
    public function preLogout()
    {
        $this->logout_id_token = $_SESSION['oidcIdToken'] ?? null;
    }

    /**
     * Returns the OAuth 2.0 redirect URI (the SP callback URL).
     */
    private function getRedirectUri(): string
    {
        global $sugar_config;
        return rtrim($sugar_config['site_url'], '/') . '/legacy/index.php?action=Login&module=Users';
    }
}
