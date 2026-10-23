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

/**
 * Class OIDCIdTokenValidator
 *
 * Validates the claims of an ID token received from the IdP token endpoint
 * (OpenID Connect Core 1.0, section 3.1.3.7).
 *
 * The token signature is deliberately not verified: the token is received
 * directly from the token endpoint over TLS, authenticated with the client
 * secret, which the specification allows in place of signature validation.
 * The claims below must still be checked.
 */
class OIDCIdTokenValidator
{
    /** Tolerated clock difference between MintHCM and the IdP, in seconds. */
    public const CLOCK_SKEW_SECONDS = 120;

    /** @var string */
    private $last_error = '';

    /**
     * @param array  $claims           Decoded ID token payload.
     * @param string $expected_issuer  Configured OIDC_issuer.
     * @param string $client_id        Configured OIDC_clientId.
     * @param string $expected_nonce   Nonce sent in the authorization request.
     * @param int    $now              Current Unix timestamp.
     * @return bool True when every claim is valid.
     */
    public function validate(array $claims, string $expected_issuer, string $client_id, string $expected_nonce, int $now): bool
    {
        $this->last_error = '';

        if ($expected_issuer === '') {
            return $this->fail('OIDC_issuer is not configured');
        }
        if (!is_string($claims['iss'] ?? null) || $claims['iss'] !== $expected_issuer) {
            return $this->fail('issuer mismatch');
        }
        if (!$this->isAudienceValid($claims, $client_id)) {
            return $this->fail('audience mismatch');
        }
        if (!is_numeric($claims['exp'] ?? null) || (int) $claims['exp'] <= $now - self::CLOCK_SKEW_SECONDS) {
            return $this->fail('token expired or exp missing');
        }
        if (!is_numeric($claims['iat'] ?? null) || (int) $claims['iat'] > $now + self::CLOCK_SKEW_SECONDS) {
            return $this->fail('iat missing or in the future');
        }
        if ($expected_nonce === '' || !is_string($claims['nonce'] ?? null) || !hash_equals($expected_nonce, $claims['nonce'])) {
            return $this->fail('nonce mismatch');
        }

        return true;
    }

    /**
     * Reason of the last failed validation, for logging.
     */
    public function getLastError(): string
    {
        return $this->last_error;
    }

    /**
     * `aud` may be a string or an array. With several audiences the
     * authorized party (`azp`) must be this client.
     */
    private function isAudienceValid(array $claims, string $client_id): bool
    {
        if ($client_id === '') {
            return false;
        }

        $audiences = $claims['aud'] ?? null;
        if (is_string($audiences)) {
            $audiences = [$audiences];
        }
        if (!is_array($audiences) || !in_array($client_id, $audiences, true)) {
            return false;
        }

        return count($audiences) === 1 || ($claims['azp'] ?? null) === $client_id;
    }

    private function fail(string $reason): bool
    {
        $this->last_error = $reason;
        return false;
    }
}
