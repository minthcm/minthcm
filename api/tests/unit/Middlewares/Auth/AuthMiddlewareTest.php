<?php

namespace MintHCM\Tests\Unit\Middlewares\Auth;

use MintHCM\Api\Middlewares\Auth\AuthMiddleware;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionMethod;

/**
 * runLegacyAuthorization() itself is not covered here: it drives the legacy bootstrap
 * (chdir, AuthenticationController::getInstance(), real $_SESSION writes for OAuth/SAML/OIDC)
 * and would require mocking that whole stack to exercise in isolation, which the existing
 * unit suite avoids (see MetaControllerTest/ParamTypesTest — pure-logic slices only). What
 * is unit-testable in isolation is closeSessionIfOpen() itself: it must only ever close an
 * active session, never touch one that is not active (e.g. already destroyed, or none
 * started because auth went through the token-only path in __invoke()).
 */
final class AuthMiddlewareTest extends TestCase
{
    private ReflectionMethod $closeSessionIfOpen;

    protected function setUp(): void
    {
        $this->closeSessionIfOpen = new ReflectionMethod(AuthMiddleware::class, 'closeSessionIfOpen');
        $this->closeSessionIfOpen->setAccessible(true);
    }

    protected function tearDown(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
    }

    public function testClosesAnActiveSession(): void
    {
        session_start();
        self::assertSame(PHP_SESSION_ACTIVE, session_status());

        $this->invoke();

        self::assertSame(PHP_SESSION_NONE, session_status());
    }

    public function testIsNoOpWhenNoSessionWasStarted(): void
    {
        self::assertSame(PHP_SESSION_NONE, session_status());

        $this->invoke();

        self::assertSame(PHP_SESSION_NONE, session_status());
    }

    public function testIsSafeToCallTwiceInARow(): void
    {
        session_start();

        $this->invoke();
        $this->invoke();

        self::assertSame(PHP_SESSION_NONE, session_status());
    }

    private function invoke(): void
    {
        $middleware = (new ReflectionClass(AuthMiddleware::class))->newInstanceWithoutConstructor();
        $this->closeSessionIfOpen->invoke($middleware);
    }
}
