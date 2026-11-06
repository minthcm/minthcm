<?php

namespace MintHCM\Lib\Services\Http;

/**
 * Client address for rate limits (login, forgotten password).
 *
 * X-Forwarded-For is honoured only when the request comes from a proxy listed in
 * $sugar_config['trusted_proxies']; the right-most address that is not a trusted proxy wins, so an
 * entry the client put in the header itself is ignored. Without trusted proxies it is REMOTE_ADDR.
 */
class ClientIpResolver
{
    public function resolve(array $server): string
    {
        $remote = (string) ($server['REMOTE_ADDR'] ?? '');
        $forwarded = (string) ($server['HTTP_X_FORWARDED_FOR'] ?? '');
        $trusted = $this->getTrustedProxies();
        if ($remote === '' || $forwarded === '' || !in_array($remote, $trusted, true)) {
            return $remote;
        }

        foreach (array_reverse(array_map('trim', explode(',', $forwarded))) as $candidate) {
            if (filter_var($candidate, FILTER_VALIDATE_IP) === false) {
                return $remote;
            }
            if (!in_array($candidate, $trusted, true)) {
                return $candidate;
            }
        }

        return $remote;
    }

    /**
     * @return string[]
     */
    protected function getTrustedProxies(): array
    {
        global $sugar_config;

        $proxies = $sugar_config['trusted_proxies'] ?? [];
        return is_array($proxies) ? array_map('strval', $proxies) : [];
    }
}
