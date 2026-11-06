<?php

namespace MintHCM\Tests\Unit\Legacy;

use PHPUnit\Framework\TestCase;
use SuiteCRM\Utility\SessionTimeout;

// shared with the legacy layer, which the API test bootstrap does not autoload
require_once __DIR__ . '/../../../../legacy/lib/Utility/SessionTimeout.php';

final class SessionTimeoutTest extends TestCase
{
    private const NOW = 1_800_000_000;
    private const HOUR = 3600;
    private const DAY = 86400;

    private SessionTimeout $timeout;

    protected function setUp(): void
    {
        $this->timeout = new SessionTimeout();
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['sugar_config'], $GLOBALS['log']);
    }

    public function testDefaultsAreEightHoursIdleAndSevenDaysAbsolute(): void
    {
        self::assertSame(8 * self::HOUR, $this->timeout->getIdleTimeoutSeconds());
        self::assertSame(7 * self::DAY, $this->timeout->getAbsoluteTimeoutSeconds());
    }

    public function testFreshSessionIsNotExpired(): void
    {
        $session = [];
        $this->timeout->start($session, self::NOW);

        self::assertFalse($this->timeout->isExpired($session, self::NOW + self::HOUR));
    }

    public function testExpiresAfterIdleTimeout(): void
    {
        $session = [];
        $this->timeout->start($session, self::NOW);

        self::assertTrue($this->timeout->isExpired($session, self::NOW + 8 * self::HOUR));
    }

    public function testActivityMovesIdleDeadline(): void
    {
        $session = [];
        $this->timeout->start($session, self::NOW);
        $this->timeout->touch($session, self::NOW + 7 * self::HOUR);

        self::assertFalse($this->timeout->isExpired($session, self::NOW + 14 * self::HOUR));
    }

    public function testExpiresAfterAbsoluteTimeoutDespiteActivity(): void
    {
        $session = [];
        $this->timeout->start($session, self::NOW);
        for ($time = self::NOW; $time < self::NOW + 7 * self::DAY; $time += self::HOUR) {
            $this->timeout->touch($session, $time);
        }

        self::assertTrue($this->timeout->isExpired($session, self::NOW + 7 * self::DAY));
        self::assertSame(self::NOW, $session[SessionTimeout::LOGIN_TIME_KEY], 'touch must not move the login time');
    }

    public function testSessionWithoutMarkersIsNotExpiredAndGetsStartedOnTouch(): void
    {
        $session = ['authenticated_user_id' => '1'];

        self::assertFalse($this->timeout->isExpired($session, self::NOW));

        $this->timeout->touch($session, self::NOW);
        self::assertSame(self::NOW, $session[SessionTimeout::LOGIN_TIME_KEY]);
        self::assertSame(self::NOW, $session[SessionTimeout::LAST_ACTIVITY_KEY]);
    }

    public function testZeroDisablesLimits(): void
    {
        $GLOBALS['sugar_config'] = ['session_idle_timeout' => '0', 'session_absolute_timeout' => ''];
        $session = [];
        $this->timeout->start($session, self::NOW);

        self::assertFalse($this->timeout->isExpired($session, self::NOW + 365 * self::DAY));
    }

    public function testReadsConfiguredIntervals(): void
    {
        $GLOBALS['sugar_config'] = ['session_idle_timeout' => 'PT30M', 'session_absolute_timeout' => 'PT12H'];

        self::assertSame(1800, $this->timeout->getIdleTimeoutSeconds());
        self::assertSame(12 * self::HOUR, $this->timeout->getAbsoluteTimeoutSeconds());
    }

    public function testInvalidValueFallsBackToDefault(): void
    {
        $GLOBALS['sugar_config'] = ['session_idle_timeout' => '8 hours'];
        $logged = [];
        $GLOBALS['log'] = new class($logged) {
            private array $messages;

            public function __construct(array &$messages)
            {
                $this->messages = &$messages;
            }

            public function fatal(string $message): void
            {
                $this->messages[] = $message;
            }
        };

        self::assertSame(8 * self::HOUR, $this->timeout->getIdleTimeoutSeconds());
        self::assertCount(1, $logged);
    }
}
