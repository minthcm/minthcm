<?php

namespace MintHCM\Tests\Unit\Lib\Services\Auth;

use League\OAuth2\Server\Exception\OAuthServerException;
use MintHCM\Lib\Services\Auth\LoginThrottle;
use MintHCM\Lib\Services\Http\ClientIpResolver;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

final class LoginThrottleTest extends TestCase
{
    private const IP = '203.0.113.7';

    public function testAllowsLoginWhenThereWereNoAttempts(): void
    {
        $this->createThrottle()->assertAllowed('john', self::IP);

        $this->addToAssertionCount(1);
    }

    public function testAllowsAttemptsUpToTheLimit(): void
    {
        $throttle = $this->createThrottle();

        $this->attempt($throttle, 'john', LoginThrottle::DEFAULT_MAX_ATTEMPTS);

        $this->addToAssertionCount(1);
    }

    public function testRejectsAttemptAfterTheLimitIsReached(): void
    {
        $throttle = $this->createThrottle();
        $this->attempt($throttle, 'john', LoginThrottle::DEFAULT_MAX_ATTEMPTS);

        $this->assertThrottled($throttle, 'john');
    }

    public function testReservesTheAttemptBeforeThePasswordIsChecked(): void
    {
        // No failure is ever reported (as when parallel requests are still verifying passwords):
        // the attempts themselves must use up the limit.
        $throttle = $this->createThrottle();
        $this->attempt($throttle, 'john', LoginThrottle::DEFAULT_MAX_ATTEMPTS);

        $this->assertThrottled($throttle, 'john');
    }

    public function testRejectedAttemptDoesNotExtendTheLockout(): void
    {
        $throttle = $this->createThrottle();
        $this->attempt($throttle, 'john', LoginThrottle::DEFAULT_MAX_ATTEMPTS);
        $throttle->age(LoginThrottle::DEFAULT_LOCKOUT_MINUTES * 60 - 10);

        try {
            $throttle->assertAllowed('john', self::IP);
            self::fail('Expected the account to be locked');
        } catch (OAuthServerException) {
            // expected
        }
        $throttle->age(11);

        $throttle->assertAllowed('john', '198.51.100.1');
        $this->addToAssertionCount(1);
    }

    public function testLockIsPerAccount(): void
    {
        $throttle = $this->createThrottle();
        $this->attempt($throttle, 'john', LoginThrottle::DEFAULT_MAX_ATTEMPTS);

        $throttle->assertAllowed('jane', '198.51.100.1');

        $this->addToAssertionCount(1);
    }

    public function testUsernameCaseDoesNotBypassTheLock(): void
    {
        $throttle = $this->createThrottle();
        $this->attempt($throttle, 'John', LoginThrottle::DEFAULT_MAX_ATTEMPTS);

        $this->assertThrottled($throttle, 'john');
    }

    public function testLockExpiresAfterTheLockoutPeriod(): void
    {
        $throttle = $this->createThrottle();
        $this->attempt($throttle, 'john', LoginThrottle::DEFAULT_MAX_ATTEMPTS);

        $throttle->age(LoginThrottle::DEFAULT_LOCKOUT_MINUTES * 60 + 1);

        $throttle->assertAllowed('john', '198.51.100.1');
        $this->addToAssertionCount(1);
    }

    public function testCountingStartsAgainAfterTheLockoutPeriod(): void
    {
        $throttle = $this->createThrottle();
        $this->attempt($throttle, 'john', LoginThrottle::DEFAULT_MAX_ATTEMPTS);
        $throttle->age(LoginThrottle::DEFAULT_LOCKOUT_MINUTES * 60 + 1);

        $this->attempt($throttle, 'john', LoginThrottle::DEFAULT_MAX_ATTEMPTS, '198.51.100.1');

        $this->assertThrottled($throttle, 'john');
    }

    public function testSuccessResetsTheAttemptCounter(): void
    {
        $throttle = $this->createThrottle();
        $this->attempt($throttle, 'john', LoginThrottle::DEFAULT_MAX_ATTEMPTS - 1);

        $throttle->registerSuccess('john');
        $this->attempt($throttle, 'john', LoginThrottle::DEFAULT_MAX_ATTEMPTS);

        $this->assertThrottled($throttle, 'john');
    }

    public function testCounterWithoutTimestampFromLegacyLoginIsNotALockout(): void
    {
        // legacy SugarAuthenticate raises `loginfailed` without `loginfailed_last`
        $throttle = $this->createThrottle();
        $throttle->seedAccount('john', 99, 0);

        $throttle->assertAllowed('john', self::IP);

        $this->addToAssertionCount(1);
    }

    public function testUsesThresholdsFromPasswordSettings(): void
    {
        $throttle = $this->createThrottle([
            'lockoutexpirationlogin' => '3',
            'lockoutexpirationtime' => '2',
            'lockoutexpirationtype' => '1',
        ]);

        $this->attempt($throttle, 'john', 3);
        try {
            $throttle->assertAllowed('john', self::IP);
            self::fail('Expected the account to be locked after 3 attempts');
        } catch (OAuthServerException) {
            // expected
        }

        $throttle->age(2 * 60 + 1);
        $throttle->assertAllowed('john', self::IP);
        $this->addToAssertionCount(1);
    }

    public function testLockoutTypeMultipliesTheLockoutTime(): void
    {
        $throttle = $this->createThrottle([
            'lockoutexpirationlogin' => '1',
            'lockoutexpirationtime' => '2',
            'lockoutexpirationtype' => '60',
        ]);
        $this->attempt($throttle, 'john', 1);

        $throttle->age(119 * 60);
        try {
            $throttle->assertAllowed('john', self::IP);
            self::fail('Expected the account to be locked');
        } catch (OAuthServerException) {
            // expected
        }

        $throttle->age(2 * 60);
        $throttle->assertAllowed('john', self::IP);
        $this->addToAssertionCount(1);
    }

    public function testFallsBackToDefaultsWhenSettingsAreEmptyOrInvalid(): void
    {
        $throttle = $this->createThrottle([
            'lockoutexpirationlogin' => '',
            'lockoutexpirationtime' => 'abc',
            'lockoutexpirationtype' => '0',
        ]);
        $this->attempt($throttle, 'john', LoginThrottle::DEFAULT_MAX_ATTEMPTS);

        $this->assertThrottled($throttle, 'john');
    }

    public function testDisabledLockoutFlagDoesNotDisableTheThrottle(): void
    {
        // upgraded instances carry lockoutexpiration = '0'
        $throttle = $this->createThrottle(['lockoutexpiration' => '0']);
        $this->attempt($throttle, 'john', LoginThrottle::DEFAULT_MAX_ATTEMPTS);

        $this->assertThrottled($throttle, 'john');
    }

    public function testBlocksIpAfterTooManyFailuresAcrossAccounts(): void
    {
        $throttle = $this->createThrottle();
        for ($i = 0; $i < LoginThrottle::DEFAULT_MAX_IP_FAILURES; $i++) {
            $throttle->registerFailure(self::IP);
        }

        try {
            $throttle->assertAllowed('somebody-else', self::IP);
            self::fail('Expected the IP to be throttled');
        } catch (OAuthServerException $exception) {
            self::assertSame(429, $exception->getHttpStatusCode());
        }

        $throttle->assertAllowed('somebody-else', '198.51.100.1');
        $this->addToAssertionCount(1);
    }

    public function testIpBlockExpires(): void
    {
        $throttle = $this->createThrottle();
        for ($i = 0; $i < LoginThrottle::DEFAULT_MAX_IP_FAILURES; $i++) {
            $throttle->registerFailure(self::IP);
        }

        $throttle->age(LoginThrottle::DEFAULT_LOCKOUT_MINUTES * 60 + 1);

        $throttle->assertAllowed('somebody-else', self::IP);
        $this->addToAssertionCount(1);
    }

    public function testMaxIpFailuresComesFromSettings(): void
    {
        $throttle = $this->createThrottle(throttleSettings: ['max_ip_failures' => '3']);
        for ($i = 0; $i < 3; $i++) {
            $throttle->registerFailure(self::IP);
        }

        $this->assertThrottled($throttle, 'somebody-else');
    }

    public function testInvalidMaxIpFailuresFallsBackToDefault(): void
    {
        $throttle = $this->createThrottle(throttleSettings: ['max_ip_failures' => 'abc']);
        for ($i = 0; $i < LoginThrottle::DEFAULT_MAX_IP_FAILURES - 1; $i++) {
            $throttle->registerFailure(self::IP);
        }

        $throttle->assertAllowed('somebody-else', self::IP);
        $this->addToAssertionCount(1);
    }

    public function testIpFailureIsCountedUnderALock(): void
    {
        $throttle = $this->createThrottle([], useRealLock: true, lockResult: '1');

        $throttle->registerFailure(self::IP);

        self::assertCount(2, $throttle->queries);
        self::assertMatchesRegularExpression("/^SELECT GET_LOCK\('mint_login_ip_[0-9a-f]{40}', \d+\)$/", $throttle->queries[0]);
        self::assertMatchesRegularExpression("/^SELECT RELEASE_LOCK\('mint_login_ip_[0-9a-f]{40}'\)$/", $throttle->queries[1]);
        self::assertSame(1, reset($throttle->cache)['count']);
    }

    public function testIpFailureIsRejectedWhenTheLockCannotBeObtained(): void
    {
        $throttle = $this->createThrottle([], useRealLock: true, lockResult: '0');

        try {
            $throttle->registerFailure(self::IP);
            self::fail('Expected OAuthServerException');
        } catch (OAuthServerException $exception) {
            self::assertSame(429, $exception->getHttpStatusCode());
        }
        self::assertSame([], $throttle->cache);
    }

    public function testLockTimeoutComesFromSettings(): void
    {
        $throttle = $this->createThrottle([], useRealLock: true, lockResult: '1', throttleSettings: ['lock_timeout' => '7']);

        $throttle->assertAllowed('john', self::IP);

        self::assertStringEndsWith(', 7)', $throttle->queries[0]);
    }

    public function testInvalidLockTimeoutFallsBackToDefault(): void
    {
        $throttle = $this->createThrottle([], useRealLock: true, lockResult: '1', throttleSettings: ['lock_timeout' => '0']);

        $throttle->assertAllowed('john', self::IP);

        self::assertStringEndsWith(', ' . LoginThrottle::DEFAULT_LOCK_TIMEOUT_SECONDS . ')', $throttle->queries[0]);
    }

    public function testEmptyIpIsNotTracked(): void
    {
        $throttle = $this->createThrottle();
        for ($i = 0; $i < LoginThrottle::DEFAULT_MAX_IP_FAILURES + 1; $i++) {
            $throttle->registerFailure('');
        }

        $throttle->assertAllowed('somebody-else', '');
        $this->addToAssertionCount(1);
    }

    public function testThrottleResponseIsTooManyRequests(): void
    {
        $throttle = $this->createThrottle();
        $this->attempt($throttle, 'john', LoginThrottle::DEFAULT_MAX_ATTEMPTS);

        try {
            $throttle->assertAllowed('john', self::IP);
            self::fail('Expected OAuthServerException');
        } catch (OAuthServerException $exception) {
            self::assertSame(429, $exception->getHttpStatusCode());
            self::assertSame('too_many_attempts', $exception->getErrorType());
        }
    }

    public function testRejectsAttemptWhenTheAccountLockCannotBeObtained(): void
    {
        $throttle = $this->createThrottle([], useRealLock: true, lockResult: '0');

        $this->assertThrottled($throttle, 'john');
    }

    #[DataProvider('unsupportedLockResults')]
    public function testLoginWorksWhenTheDatabaseDoesNotSupportNamedLocks(false|null $lockResult): void
    {
        // e.g. Percona XtraDB Cluster with pxc_strict_mode = ENFORCING rejects GET_LOCK() with an error
        $throttle = $this->createThrottle([], useRealLock: true, lockResult: $lockResult);

        $throttle->assertAllowed('john', self::IP);
        $throttle->registerFailure(self::IP);
        $throttle->registerSuccess('john');

        self::assertSame(3, $throttle->lockUnavailableLogs);
        foreach ($throttle->queries as $query) {
            self::assertStringStartsNotWith('SELECT RELEASE_LOCK', $query);
        }
        self::assertSame(1, $throttle->cache['login_throttle_ip_' . sha1(self::IP)]['count']);
    }

    #[DataProvider('unsupportedLockResults')]
    public function testLimitsStillApplyWhenTheDatabaseDoesNotSupportNamedLocks(false|null $lockResult): void
    {
        $throttle = $this->createThrottle([], useRealLock: true, lockResult: $lockResult);
        $this->attempt($throttle, 'john', LoginThrottle::DEFAULT_MAX_ATTEMPTS);

        $this->assertThrottled($throttle, 'john');
    }

    public static function unsupportedLockResults(): array
    {
        return ['query error' => [false], 'NULL result' => [null]];
    }

    public function testAccountLockIsTakenAndReleased(): void
    {
        $throttle = $this->createThrottle([], useRealLock: true, lockResult: '1');

        $throttle->assertAllowed('john', self::IP);

        self::assertCount(2, $throttle->queries);
        self::assertMatchesRegularExpression("/^SELECT GET_LOCK\('mint_login_[0-9a-f]{40}', \d+\)$/", $throttle->queries[0]);
        self::assertMatchesRegularExpression("/^SELECT RELEASE_LOCK\('mint_login_[0-9a-f]{40}'\)$/", $throttle->queries[1]);
    }

    public function testAccountLockIsReleasedWhenTheAttemptIsRejected(): void
    {
        $throttle = $this->createThrottle([], useRealLock: true, lockResult: '1');
        $throttle->seedAccount('john', LoginThrottle::DEFAULT_MAX_ATTEMPTS, time());

        try {
            $throttle->assertAllowed('john', self::IP);
            self::fail('Expected the account to be locked');
        } catch (OAuthServerException) {
            // expected
        }

        self::assertStringStartsWith('SELECT RELEASE_LOCK', end($throttle->queries));
    }

    public function testClientIpIsResolvedByClientIpResolver(): void
    {
        $GLOBALS['sugar_config'] = ['trusted_proxies' => ['10.0.0.5']];

        $ip = $this->createThrottle()->resolveClientIp(['REMOTE_ADDR' => '10.0.0.5', 'HTTP_X_FORWARDED_FOR' => '198.51.100.9']);

        unset($GLOBALS['sugar_config']);
        self::assertSame('198.51.100.9', $ip);
    }

    private function attempt(LoginThrottle $throttle, string $username, int $times, string $ip = self::IP): void
    {
        for ($i = 0; $i < $times; $i++) {
            $throttle->assertAllowed($username, $ip);
        }
    }

    private function assertThrottled(LoginThrottle $throttle, string $username): void
    {
        $this->expectException(OAuthServerException::class);
        $this->expectExceptionMessage('Too many failed login attempts.');

        $throttle->assertAllowed($username, self::IP);
    }

    /**
     * Throttle with in-memory storage: every username is treated as unknown to the legacy user
     * table, so the account counter goes through the (replaced) cache. The named locks are a no-op
     * unless $useRealLock is set - then the lock queries go to a recording fake database.
     */
    private function createThrottle(
        array $settings = [],
        bool $useRealLock = false,
        string|false|null $lockResult = '1',
        array $throttleSettings = []
    ): LoginThrottle {
        return new class ($settings, $useRealLock, $lockResult, $throttleSettings) extends LoginThrottle {
            public array $cache = [];
            public array $queries = [];
            public int $lockUnavailableLogs = 0;

            public function __construct(
                private array $settings,
                private bool $useRealLock,
                private string|false|null $lockResult,
                private array $throttleSettings
            ) {
            }

            /** Moves every stored timestamp into the past, as if $seconds have elapsed. */
            public function age(int $seconds): void
            {
                foreach ($this->cache as $key => $state) {
                    foreach (['last', 'until'] as $field) {
                        if (isset($state[$field])) {
                            $this->cache[$key][$field] -= $seconds;
                        }
                    }
                }
            }

            public function seedAccount(string $username, int $count, int $last): void
            {
                $this->saveAccountState($username, $count, $last);
            }

            protected function getClientIpResolver(): ClientIpResolver
            {
                return new ClientIpResolver();
            }

            protected function getPasswordSetting(string $key)
            {
                return $this->settings[$key] ?? null;
            }

            protected function getThrottleSetting(string $key)
            {
                return $this->throttleSettings[$key] ?? null;
            }

            protected function withLock(string $name, callable $callback)
            {
                return $this->useRealLock ? parent::withLock($name, $callback) : $callback();
            }

            protected function logLockUnavailable(string $name): void
            {
                $this->lockUnavailableLogs++;
            }

            protected function getDatabase()
            {
                $throttle = $this;

                return new class ($throttle) {
                    public function __construct(private object $throttle)
                    {
                    }

                    public function getOne(string $sql): string|false|null
                    {
                        $this->throttle->queries[] = $sql;
                        return str_starts_with($sql, 'SELECT GET_LOCK') ? $this->throttle->lockResultValue() : '1';
                    }
                };
            }

            public function lockResultValue(): string|false|null
            {
                return $this->lockResult;
            }

            protected function findUserId(string $username): string
            {
                return '';
            }

            protected function cacheGet(string $key): array
            {
                return $this->cache[$key] ?? [];
            }

            protected function cachePut(string $key, array $value, int $ttl): void
            {
                $this->cache[$key] = $value;
            }
        };
    }
}
