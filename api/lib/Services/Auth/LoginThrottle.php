<?php

namespace MintHCM\Lib\Services\Auth;

use DBManagerFactory;
use League\OAuth2\Server\Exception\OAuthServerException;
use MintHCM\Lib\Services\Http\ClientIpResolver;
use MintHCM\Utils\CustomLoader;

/**
 * Limits password guessing on the OAuth password-based grants (/api/login, /api/access_token).
 *
 * Two independent limits:
 *  - per account: attempts since the last successful login. An attempt is reserved atomically
 *    (MySQL named lock) in assertAllowed(), *before* the password is checked, so parallel requests
 *    cannot slip past the limit. The counter is kept in the user preferences (`loginfailed` - the
 *    same counter legacy SugarAuthenticate uses - and `loginfailed_last`); unknown usernames are
 *    tracked in the cache so the behaviour is the same for them. A successful login resets it.
 *  - per IP: failed attempts in a time window, kept in sugar_cache. The read-modify-write of the
 *    counter runs under a MySQL named lock, like the account counter. Without a persistent cache backend (APCu/Redis/Memcached/file cache) sugar_cache lives for a
 *    single request and this limit is effectively inactive - the per-account limit still works.
 *    Behind a reverse proxy set $sugar_config['trusted_proxies'] (list of proxy IPs), otherwise
 *    every client shares the proxy address.
 *
 * Thresholds come from $sugar_config['passwordsetting']:
 *  - lockoutexpirationlogin: attempts before lockout,
 *  - lockoutexpirationtime * lockoutexpirationtype: lockout length (type = minutes multiplier),
 * with the defaults below when they are empty. `lockoutexpiration` (on/off flag) is ignored on
 * purpose - upgraded instances carry '0' there and would stay unprotected.
 *
 * Settings from $sugar_config['login_throttle'] (defaults below when empty or invalid):
 *  - max_ip_failures: failed attempts from one IP within the lockout period,
 *  - lock_timeout: seconds to wait for the named lock before the attempt is rejected.
 */
class LoginThrottle
{
    public const DEFAULT_MAX_ATTEMPTS = 5;
    public const DEFAULT_LOCKOUT_MINUTES = 15;
    public const DEFAULT_MAX_IP_FAILURES = 20;
    public const DEFAULT_LOCK_TIMEOUT_SECONDS = 3;

    /**
     * Rejects the attempt when the IP or the account is locked, otherwise reserves one attempt for
     * the account. Call it before the password is verified.
     */
    public function assertAllowed(string $username, string $ip): void
    {
        $now = time();

        if ($this->isIpBlocked($ip, $now)) {
            throw $this->createTooManyAttemptsException();
        }

        $this->withLock($this->getAccountLockName($username), function () use ($username, $now): void {
            [$count, $last] = $this->getAccountState($username);
            if ($last === 0 || ($now - $last) >= $this->getLockoutSeconds()) {
                $count = 0;
            }
            if ($count >= $this->getMaxAttempts()) {
                throw $this->createTooManyAttemptsException();
            }
            $this->saveAccountState($username, $count + 1, $now);
        });
    }

    public function registerFailure(string $ip): void
    {
        $this->registerIpFailure($ip, time());
    }

    public function registerSuccess(string $username): void
    {
        $this->withLock($this->getAccountLockName($username), function () use ($username): void {
            [$count] = $this->getAccountState($username);
            if ($count > 0) {
                $this->saveAccountState($username, 0, 0);
            }
        });
    }

    /**
     * Client address used for the per IP limit - see ClientIpResolver.
     */
    public function resolveClientIp(array $server): string
    {
        return $this->getClientIpResolver()->resolve($server);
    }

    protected function getMaxAttempts(): int
    {
        $value = $this->getPasswordSetting('lockoutexpirationlogin');
        return is_numeric($value) && (int) $value > 0 ? (int) $value : self::DEFAULT_MAX_ATTEMPTS;
    }

    protected function getLockoutSeconds(): int
    {
        $time = $this->getPasswordSetting('lockoutexpirationtime');
        if (!is_numeric($time) || (int) $time <= 0) {
            return self::DEFAULT_LOCKOUT_MINUTES * 60;
        }

        $unit = $this->getPasswordSetting('lockoutexpirationtype');
        $multiplier = is_numeric($unit) && (int) $unit > 0 ? (int) $unit : 1;

        return (int) $time * $multiplier * 60;
    }

    protected function getMaxIpFailures(): int
    {
        $value = $this->getThrottleSetting('max_ip_failures');
        return is_numeric($value) && (int) $value > 0 ? (int) $value : self::DEFAULT_MAX_IP_FAILURES;
    }

    protected function getLockTimeoutSeconds(): int
    {
        $value = $this->getThrottleSetting('lock_timeout');
        return is_numeric($value) && (int) $value > 0 ? (int) $value : self::DEFAULT_LOCK_TIMEOUT_SECONDS;
    }

    protected function getClientIpResolver(): ClientIpResolver
    {
        return CustomLoader::getObject(ClientIpResolver::class);
    }

    /**
     * Serialises the read-modify-write of a counter across parallel requests.
     *
     * GET_LOCK() returns 1 when the lock is taken and 0 when another request held it for the whole
     * timeout - then the attempt is rejected (fail closed). When the database cannot run GET_LOCK()
     * at all (query error or NULL, e.g. Percona XtraDB Cluster with pxc_strict_mode = ENFORCING or
     * a SQL proxy that blocks it) the counter is updated without the lock: the limits keep working,
     * only parallel requests may slip a few attempts past them. Rejecting every login there would
     * lock everybody out.
     *
     * @param string $name lock name built from a hash, so it is safe to put into the query
     */
    protected function withLock(string $name, callable $callback)
    {
        $db = $this->getDatabase();

        $result = $db->getOne("SELECT GET_LOCK('{$name}', " . $this->getLockTimeoutSeconds() . ')');
        if ($result === false || $result === null) {
            $this->logLockUnavailable($name);
            return $callback();
        }
        if ((string) $result !== '1') {
            throw $this->createTooManyAttemptsException();
        }

        try {
            return $callback();
        } finally {
            $db->getOne("SELECT RELEASE_LOCK('{$name}')");
        }
    }

    protected function logLockUnavailable(string $name): void
    {
        if (isset($GLOBALS['log'])) {
            $GLOBALS['log']->warn(
                "LoginThrottle: GET_LOCK('{$name}') is not available in this database, the login counter is updated without a lock."
            );
        }
    }

    protected function getDatabase()
    {
        return DBManagerFactory::getInstance();
    }

    protected function getPasswordSetting(string $key)
    {
        global $sugar_config;

        return $sugar_config['passwordsetting'][$key] ?? null;
    }

    protected function getThrottleSetting(string $key)
    {
        global $sugar_config;

        return $sugar_config['login_throttle'][$key] ?? null;
    }

    /**
     * @return array{0: int, 1: int} failed attempts in a row, timestamp of the last one
     */
    protected function getAccountState(string $username): array
    {
        $user_id = $this->findUserId($username);
        if ($user_id === '') {
            $state = $this->cacheGet($this->getAccountCacheKey($username));
            return [(int) ($state['count'] ?? 0), (int) ($state['last'] ?? 0)];
        }

        return $this->inLegacyDirectory(function () use ($user_id): array {
            $user = $this->loadUser($user_id);
            return [(int) $user->getPreference('loginfailed'), (int) $user->getPreference('loginfailed_last')];
        });
    }

    protected function saveAccountState(string $username, int $count, int $last): void
    {
        $user_id = $this->findUserId($username);
        if ($user_id === '') {
            $this->cachePut(
                $this->getAccountCacheKey($username),
                ['count' => $count, 'last' => $last],
                $this->getLockoutSeconds()
            );
            return;
        }

        $this->inLegacyDirectory(function () use ($user_id, $count, $last): void {
            $user = $this->loadUser($user_id);
            $user->setPreference('loginfailed', (string) $count);
            $user->setPreference('loginfailed_last', (string) $last);
            $user->savePreferencesToDB();
        });
    }

    protected function findUserId(string $username): string
    {
        return $this->inLegacyDirectory(function () use ($username): string {
            return (string) (new \User())->retrieve_user_id($username);
        });
    }

    protected function loadUser(string $user_id): \User
    {
        $user = new \User();
        $user->retrieve($user_id);
        return $user;
    }

    protected function isIpBlocked(string $ip, int $now): bool
    {
        if ($ip === '') {
            return false;
        }

        $state = $this->cacheGet($this->getIpCacheKey($ip));
        return (int) ($state['count'] ?? 0) >= $this->getMaxIpFailures()
            && (int) ($state['until'] ?? 0) > $now;
    }

    protected function registerIpFailure(string $ip, int $now): void
    {
        if ($ip === '') {
            return;
        }

        $this->withLock('mint_login_ip_' . sha1($ip), function () use ($ip, $now): void {
            $key = $this->getIpCacheKey($ip);
            $state = $this->cacheGet($key);
            if (empty($state['until']) || (int) $state['until'] <= $now) {
                $state = ['count' => 0, 'until' => $now + $this->getLockoutSeconds()];
            }
            $state['count'] = (int) $state['count'] + 1;

            $this->cachePut($key, $state, max(1, (int) $state['until'] - $now));
        });
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

    private function getAccountLockName(string $username): string
    {
        return 'mint_login_' . sha1(mb_strtolower($username));
    }

    private function getIpCacheKey(string $ip): string
    {
        return 'login_throttle_ip_' . sha1($ip);
    }

    private function getAccountCacheKey(string $username): string
    {
        return 'login_throttle_user_' . sha1(mb_strtolower($username));
    }

    private function createTooManyAttemptsException(): OAuthServerException
    {
        return new OAuthServerException(
            'Too many failed login attempts.',
            0,
            'too_many_attempts',
            429,
            'Try again later.'
        );
    }

    private function inLegacyDirectory(callable $callback)
    {
        $previous_directory = getcwd();
        chdir('../legacy/');
        try {
            return $callback();
        } finally {
            chdir($previous_directory);
        }
    }
}
