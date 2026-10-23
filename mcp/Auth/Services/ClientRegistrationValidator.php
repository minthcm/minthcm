<?php

namespace MintMCP\Auth\Services;

/**
 * Validates Dynamic Client Registration requests (RFC 7591).
 *
 * Registration is open to anyone, so only metadata needed by an MCP client using the
 * authorization code flow with PKCE is accepted: redirect URIs must be https or a
 * loopback http address (native/CLI clients), and only the code flow grants are allowed.
 */
class ClientRegistrationValidator
{
    public const ALLOWED_GRANT_TYPES = ['authorization_code', 'refresh_token'];
    public const ALLOWED_RESPONSE_TYPES = ['code'];
    public const LOOPBACK_HOSTS = ['127.0.0.1', 'localhost', '[::1]'];
    public const MAX_CLIENT_NAME_LENGTH = 100;

    /**
     * @return array|null Null when the request is valid, otherwise an RFC 7591 §3.2.2 error
     *                    ['error' => ..., 'error_description' => ...].
     */
    public function validate(array $input): ?array
    {
        $redirectUris = $input['redirect_uris'] ?? null;
        if (!is_array($redirectUris) || $redirectUris === []) {
            return $this->error('invalid_redirect_uri', 'redirect_uris must be a non-empty array');
        }
        foreach ($redirectUris as $redirectUri) {
            if (!is_string($redirectUri) || !$this->isAllowedRedirectUri($redirectUri)) {
                return $this->error('invalid_redirect_uri', 'Redirect URIs must use https, or http on a loopback address, and must not contain a fragment');
            }
        }

        $grantTypes = $input['grant_types'] ?? self::ALLOWED_GRANT_TYPES;
        if (!is_array($grantTypes) || $grantTypes === [] || array_diff($grantTypes, self::ALLOWED_GRANT_TYPES) !== []) {
            return $this->error('invalid_client_metadata', 'Only authorization_code and refresh_token grant types are supported');
        }

        $responseTypes = $input['response_types'] ?? self::ALLOWED_RESPONSE_TYPES;
        if (!is_array($responseTypes) || array_values($responseTypes) !== self::ALLOWED_RESPONSE_TYPES) {
            return $this->error('invalid_client_metadata', 'Only the code response type is supported');
        }

        if (isset($input['client_name']) && !is_string($input['client_name'])) {
            return $this->error('invalid_client_metadata', 'client_name must be a string');
        }

        return null;
    }

    /**
     * Client name safe to store and show on the consent screen: control characters removed,
     * length limited.
     */
    public function sanitizeClientName(?string $clientName, string $default): string
    {
        $clientName = trim(preg_replace('/[\x00-\x1F\x7F]/u', '', (string) $clientName) ?? '');
        if ($clientName === '') {
            return $default;
        }
        return mb_substr($clientName, 0, self::MAX_CLIENT_NAME_LENGTH);
    }

    private function isAllowedRedirectUri(string $redirectUri): bool
    {
        if (str_contains($redirectUri, '#')) {
            return false;
        }

        $parts = parse_url($redirectUri);
        if ($parts === false || empty($parts['scheme']) || empty($parts['host'])) {
            return false;
        }

        $scheme = strtolower($parts['scheme']);
        $host = strtolower($parts['host']);

        return $scheme === 'https'
            || ($scheme === 'http' && in_array($host, self::LOOPBACK_HOSTS, true));
    }

    private function error(string $error, string $description): array
    {
        return ['error' => $error, 'error_description' => $description];
    }
}
