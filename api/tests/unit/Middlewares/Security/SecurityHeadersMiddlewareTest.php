<?php

namespace MintHCM\Tests\Unit\Middlewares\Security;

use MintHCM\Api\Middlewares\Security\SecurityHeadersMiddleware;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Slim\Psr7\Factory\ServerRequestFactory;
use Slim\Psr7\Response;

final class SecurityHeadersMiddlewareTest extends TestCase
{
    protected function tearDown(): void
    {
        unset($GLOBALS['sugar_config']);
    }

    public function testAddsNoStoreAndNosniff(): void
    {
        $response = $this->process('http://localhost/api/login');

        self::assertSame('no-store', $response->getHeaderLine('Cache-Control'));
        self::assertSame('nosniff', $response->getHeaderLine('X-Content-Type-Options'));
    }

    public function testKeepsCacheControlSetByEndpoint(): void
    {
        $response = $this->process('http://localhost/api/files', (new Response())->withHeader('Cache-Control', 'private, max-age=60'));

        self::assertSame('private, max-age=60', $response->getHeaderLine('Cache-Control'));
    }

    public function testHstsIsOffByDefault(): void
    {
        $response = $this->process('https://localhost/api/login');

        self::assertFalse($response->hasHeader('Strict-Transport-Security'));
    }

    public function testAddsHstsOverHttpsWhenConfigured(): void
    {
        $GLOBALS['sugar_config'] = ['hsts_max_age' => 31536000];

        $response = $this->process('https://localhost/api/login');

        self::assertSame('max-age=31536000', $response->getHeaderLine('Strict-Transport-Security'));
    }

    public function testDoesNotAddHstsOverHttp(): void
    {
        $GLOBALS['sugar_config'] = ['hsts_max_age' => 31536000];

        $response = $this->process('http://localhost/api/login');

        self::assertFalse($response->hasHeader('Strict-Transport-Security'));
    }

    public function testIgnoresInvalidHstsValue(): void
    {
        $GLOBALS['sugar_config'] = ['hsts_max_age' => 'yes'];

        $response = $this->process('https://localhost/api/login');

        self::assertFalse($response->hasHeader('Strict-Transport-Security'));
    }

    private function process(string $uri, ?ResponseInterface $handler_response = null): ResponseInterface
    {
        $handler = $this->createMock(RequestHandlerInterface::class);
        $handler->method('handle')->willReturn($handler_response ?? new Response());

        $request = (new ServerRequestFactory())->createServerRequest('GET', $uri);

        return (new SecurityHeadersMiddleware())($request, $handler);
    }
}
