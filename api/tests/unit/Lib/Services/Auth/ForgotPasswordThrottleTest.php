<?php

namespace MintHCM\Tests\Unit\Lib\Services\Auth;

use MintHCM\Lib\Services\Auth\ForgotPasswordThrottle;
use PHPUnit\Framework\TestCase;

final class ForgotPasswordThrottleTest extends TestCase
{
    private const IP = '203.0.113.7';

    protected function tearDown(): void
    {
        unset($GLOBALS['sugar_config']);
    }

    public function testAllowsRequestsUpToTheUsernameLimit(): void
    {
        $throttle = $this->createThrottle();

        for ($i = 0; $i < ForgotPasswordThrottle::DEFAULT_MAX_PER_USERNAME; $i++) {
            self::assertTrue($throttle->isAllowed('john', self::IP));
        }
    }

    public function testRejectsRequestOverTheUsernameLimit(): void
    {
        $throttle = $this->createThrottle();
        $this->request($throttle, 'john', ForgotPasswordThrottle::DEFAULT_MAX_PER_USERNAME);

        self::assertFalse($throttle->isAllowed('john', '198.51.100.9'), 'another address must not bypass the username limit');
    }

    public function testUnknownAndKnownUsernamesAreLimitedAlike(): void
    {
        // the throttle never looks the user up - the limit cannot reveal whether an account exists
        $throttle = $this->createThrottle();
        $this->request($throttle, 'no-such-user', ForgotPasswordThrottle::DEFAULT_MAX_PER_USERNAME);

        self::assertFalse($throttle->isAllowed('no-such-user', self::IP));
    }

    public function testUsernameCaseAndSpacesDoNotBypassTheLimit(): void
    {
        $throttle = $this->createThrottle();
        $this->request($throttle, 'john', ForgotPasswordThrottle::DEFAULT_MAX_PER_USERNAME);

        self::assertFalse($throttle->isAllowed(' John ', self::IP));
    }

    public function testRejectsRequestOverTheIpLimitAcrossUsernames(): void
    {
        $throttle = $this->createThrottle();
        for ($i = 0; $i < ForgotPasswordThrottle::DEFAULT_MAX_PER_IP; $i++) {
            self::assertTrue($throttle->isAllowed("user{$i}", self::IP));
        }

        self::assertFalse($throttle->isAllowed('another', self::IP));
        self::assertTrue($throttle->isAllowed('another', '198.51.100.9'));
    }

    public function testRequestRejectedPerIpDoesNotCountAgainstUsername(): void
    {
        $GLOBALS['sugar_config'] = ['forgot_password_throttle' => ['max_per_ip' => 1, 'max_per_username' => 2]];
        $throttle = $this->createThrottle();
        $throttle->isAllowed('other', self::IP);
        $this->request($throttle, 'john', 5);

        self::assertTrue($throttle->isAllowed('john', '198.51.100.9'));
    }

    public function testLimitResetsAfterTheWindow(): void
    {
        $throttle = $this->createThrottle();
        $this->request($throttle, 'john', ForgotPasswordThrottle::DEFAULT_MAX_PER_USERNAME);

        $throttle->age(ForgotPasswordThrottle::DEFAULT_WINDOW_MINUTES * 60);

        self::assertTrue($throttle->isAllowed('john', self::IP));
    }

    public function testEmptyIpIsNotTracked(): void
    {
        $GLOBALS['sugar_config'] = ['forgot_password_throttle' => ['max_per_ip' => 1, 'max_per_username' => 100]];
        $throttle = $this->createThrottle();

        self::assertTrue($throttle->isAllowed('a', ''));
        self::assertTrue($throttle->isAllowed('b', ''));
    }

    public function testUsesConfiguredLimitsAndFallsBackOnInvalidValues(): void
    {
        $GLOBALS['sugar_config'] = ['forgot_password_throttle' => ['max_per_username' => '2', 'max_per_ip' => 'many']];
        $throttle = $this->createThrottle();
        $this->request($throttle, 'john', 2);

        self::assertFalse($throttle->isAllowed('john', self::IP));
    }

    private function request(ForgotPasswordThrottle $throttle, string $username, int $times): void
    {
        for ($i = 0; $i < $times; $i++) {
            $throttle->isAllowed($username, self::IP);
        }
    }

    private function createThrottle(): ForgotPasswordThrottle
    {
        return new class () extends ForgotPasswordThrottle {
            public array $cache = [];

            /** Moves every window into the past, as if $seconds have elapsed. */
            public function age(int $seconds): void
            {
                foreach ($this->cache as $key => $state) {
                    $this->cache[$key]['until'] -= $seconds;
                }
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
