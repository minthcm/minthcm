<?php

namespace SuiteCRM\Utility;

/**
 * Idle and absolute timeout of a browser session.
 *
 * The OAuth tokens of a browser session are refreshed silently on the server (AuthMiddleware), so
 * without this check a session never ends while the user comes back at least once per refresh token
 * TTL. Two markers in $_SESSION bound it:
 *  - login time    - the session ends `session_absolute_timeout` after the login, whatever the activity,
 *  - last activity - the session ends after `session_idle_timeout` without a user request.
 *
 * Both limits are ISO 8601 durations in $sugar_config ('PT8H', 'P7D'); an empty value or '0' disables
 * the limit, an invalid one falls back to the default. Used by the legacy front controller
 * (SugarApplication) and by the API (AuthMiddleware, AuthController) - both read the same session.
 */
class SessionTimeout
{
    public const LOGIN_TIME_KEY = 'mint_login_time';
    public const LAST_ACTIVITY_KEY = 'mint_last_activity';

    public const DEFAULT_IDLE_TIMEOUT = 'PT8H';
    public const DEFAULT_ABSOLUTE_TIMEOUT = 'P7D';

    /**
     * Marks the session as freshly logged in. Call it right after a successful login.
     */
    public function start(array &$session, int $now): void
    {
        $session[self::LOGIN_TIME_KEY] = $now;
        $session[self::LAST_ACTIVITY_KEY] = $now;
    }

    /**
     * Records user activity. A session logged in before the timeout existed has no markers - it is
     * started here instead of being treated as expired, so a deployment does not log everybody out.
     */
    public function touch(array &$session, int $now): void
    {
        if (empty($session[self::LOGIN_TIME_KEY])) {
            $this->start($session, $now);
            return;
        }
        $session[self::LAST_ACTIVITY_KEY] = $now;
    }

    public function isExpired(array $session, int $now): bool
    {
        $login_time = (int) ($session[self::LOGIN_TIME_KEY] ?? 0);
        if ($login_time <= 0) {
            return false;
        }

        $absolute_timeout = $this->getAbsoluteTimeoutSeconds();
        if ($absolute_timeout > 0 && $now - $login_time >= $absolute_timeout) {
            return true;
        }

        $last_activity = (int) ($session[self::LAST_ACTIVITY_KEY] ?? $login_time);
        $idle_timeout = $this->getIdleTimeoutSeconds();
        return $idle_timeout > 0 && $now - $last_activity >= $idle_timeout;
    }

    public function getIdleTimeoutSeconds(): int
    {
        return $this->getTimeoutSeconds('session_idle_timeout', self::DEFAULT_IDLE_TIMEOUT);
    }

    public function getAbsoluteTimeoutSeconds(): int
    {
        return $this->getTimeoutSeconds('session_absolute_timeout', self::DEFAULT_ABSOLUTE_TIMEOUT);
    }

    protected function getTimeoutSeconds(string $key, string $default): int
    {
        global $sugar_config;

        $value = $sugar_config[$key] ?? $default;
        if ($value === '' || $value === '0' || $value === 0 || $value === false) {
            return 0;
        }

        $seconds = $this->intervalToSeconds((string) $value);
        if ($seconds === null) {
            if (isset($GLOBALS['log'])) {
                $GLOBALS['log']->fatal("Invalid \$sugar_config['{$key}'] value '{$value}', using {$default}");
            }
            $seconds = $this->intervalToSeconds($default);
        }

        return $seconds;
    }

    private function intervalToSeconds(string $value): ?int
    {
        try {
            $interval = new \DateInterval($value);
        } catch (\Exception) {
            return null;
        }

        return (new \DateTimeImmutable('@0'))->add($interval)->getTimestamp();
    }
}
