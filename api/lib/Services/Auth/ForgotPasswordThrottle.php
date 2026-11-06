<?php

namespace MintHCM\Lib\Services\Auth;

/**
 * Rate limit of POST /api/forget_password.
 *
 * Every request counts - not only the ones for an unknown account - otherwise the limit itself would
 * tell which login + e-mail pairs are valid. For the same reason the counters live in sugar_cache for
 * every username alike (a per-user counter in the database would exist only for real accounts).
 *
 *  - per IP: $sugar_config['forgot_password_throttle']['max_per_ip'] requests per window,
 *  - per username: ['max_per_username'] requests per window,
 *  - window: ['window_minutes'] - a fixed window starting with the first request.
 *
 * Like the per-IP login limit this needs a persistent cache backend (APCu, Redis, Memcached or the file
 * cache); without one sugar_cache lives for a single request and the limit is inactive.
 */
class ForgotPasswordThrottle
{
    public const DEFAULT_MAX_PER_USERNAME = 5;
    public const DEFAULT_MAX_PER_IP = 20;
    public const DEFAULT_WINDOW_MINUTES = 15;

    /**
     * Counts the request and tells whether it may be processed.
     */
    public function isAllowed(string $username, string $ip): bool
    {
        $now = time();

        // a request already rejected per IP is not counted against the username, so a flood from one
        // address does not lock out the account owner on top of being blocked itself
        if ($ip !== '' && !$this->hit($this->getIpCacheKey($ip), $this->getSetting('max_per_ip', self::DEFAULT_MAX_PER_IP), $now)) {
            return false;
        }

        return $this->hit(
            $this->getUsernameCacheKey($username),
            $this->getSetting('max_per_username', self::DEFAULT_MAX_PER_USERNAME),
            $now
        );
    }

    private function hit(string $key, int $limit, int $now): bool
    {
        $state = $this->cacheGet($key);
        if (empty($state['until']) || (int) $state['until'] <= $now) {
            $state = ['count' => 0, 'until' => $now + $this->getWindowSeconds()];
        }
        if ((int) $state['count'] >= $limit) {
            return false;
        }

        $state['count'] = (int) $state['count'] + 1;
        $this->cachePut($key, $state, max(1, (int) $state['until'] - $now));

        return true;
    }

    private function getWindowSeconds(): int
    {
        return $this->getSetting('window_minutes', self::DEFAULT_WINDOW_MINUTES) * 60;
    }

    protected function getSetting(string $key, int $default): int
    {
        global $sugar_config;

        $value = $sugar_config['forgot_password_throttle'][$key] ?? null;
        return is_numeric($value) && (int) $value > 0 ? (int) $value : $default;
    }

    protected function cacheGet(string $key): array
    {
        $value = sugar_cache_retrieve($key);
        return is_array($value) ? $value : [];
    }

    protected function cachePut(string $key, array $value, int $ttl): void
    {
        sugar_cache_put($key, $value, $ttl);
    }

    private function getIpCacheKey(string $ip): string
    {
        return 'forgot_password_throttle_ip_' . sha1($ip);
    }

    private function getUsernameCacheKey(string $username): string
    {
        return 'forgot_password_throttle_user_' . sha1(mb_strtolower(trim($username)));
    }
}
