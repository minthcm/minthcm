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

require_once __DIR__ . '/../../../../modules/Users/authentication/SugarAuthenticate/SugarAuthenticateUser.php';

/**
 * Class OIDCAuthenticateUser
 *
 * Handles user lookup and session loading for OIDC authentication.
 * The user must exist in MintHCM, be active, and their user_name must match
 * the value of the configured OIDC username claim. `external_auth_only` does
 * not gate this path: when set to 1 it additionally blocks local password
 * login for that user (enforced in SugarAuthenticateUser::authenticateUser()),
 * but SSO login itself is available regardless of the flag's value.
 */
class OIDCAuthenticateUser extends SugarAuthenticateUser
{
    /**
     * Authenticates the user by verifying that the session-stored oidcNameId
     * matches the requested username and that a matching active, external-auth
     * user record exists.
     *
     * @param string $name            Username resolved from the OIDC id_token.
     * @param string $password        Not used for OIDC; always null.
     * @param bool   $fallback        Whether this is a fallback authentication attempt.
     * @param bool   $checkPasswordMD5 Not used for OIDC (always false).
     * @return string  User ID on success, empty string on failure.
     */
    public function authenticateUser($name, $password, $fallback = false, $checkPasswordMD5 = false)
    {
        if (empty($_SESSION['oidcNameId']) || $_SESSION['oidcNameId'] !== $name) {
            return '';
        }

        $row = User::findUserPassword(
            $name,
            null,
            "(portal_only IS NULL OR portal_only != '1') AND (is_group IS NULL OR is_group != '1') AND status != 'Inactive'",
            $checkPasswordMD5
        );

        if (empty($row)) {
            return '';
        }

        return $row['id'];
    }

    /**
     * Called during login to load the user into the global current_user.
     *
     * @param string $name     Username from the OIDC id_token.
     * @param string $password Not used.
     * @param bool   $fallback Whether this is a fallback attempt.
     * @param array  $PARAMS   Additional parameters (unused).
     * @return bool  True on success, false on failure.
     */
    public function loadUserOnLogin($name, $password, $fallback = false, $PARAMS = array())
    {
        $GLOBALS['log']->debug('OIDCAuthenticateUser: loading user "' . $name . '"');

        $user_id = $this->authenticateUser($name, null, $fallback);
        if (empty($user_id)) {
            $GLOBALS['log']->fatal('SECURITY: OIDC authentication failed for user "' . $name . '"');
            return false;
        }

        $this->loadUserOnSession($user_id);
        return true;
    }
}
