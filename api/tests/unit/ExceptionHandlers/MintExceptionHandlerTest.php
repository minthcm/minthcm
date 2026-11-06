<?php

namespace MintHCM\Tests\Unit\ExceptionHandlers;

use MintHCM\Api\ExceptionHandlers\MintExceptionHandler;
use MintHCM\Lib\MintLogic\Exceptions\ValidationException;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpNotFoundException;

final class MintExceptionHandlerTest extends TestCase
{
    private ServerRequestInterface $request;

    /** @var string[] */
    private array $logged = [];

    protected function setUp(): void
    {
        $this->request = $this->createMock(ServerRequestInterface::class);
        $this->logged = [];
        $logged = &$this->logged;
        $GLOBALS['log'] = new class($logged) {
            private array $messages;

            public function __construct(array &$messages)
            {
                $this->messages = &$messages;
            }

            public function error(string $message): void
            {
                $this->messages[] = $message;
            }
        };
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['log'], $GLOBALS['sugar_config']);
    }

    public function testHidesDetailsOfServerErrorWithoutDeveloperMode(): void
    {
        $body = $this->handle(new \RuntimeException('SQLSTATE[42S22]: Column not found: users.secret'), false);

        self::assertSame(['message' => MintExceptionHandler::GENERIC_SERVER_ERROR_MESSAGE, 'code' => 500], $body['json']);
        self::assertSame(500, $body['status']);
    }

    public function testKeepsClientErrorMessageWithoutDeveloperMode(): void
    {
        $body = $this->handle(new ValidationException('LBL_DATE_END_ERROR'), false);

        self::assertSame(['message' => 'LBL_DATE_END_ERROR', 'code' => 422], $body['json']);
        self::assertSame(422, $body['status']);
    }

    public function testKeepsHttpExceptionMessageWithoutDeveloperMode(): void
    {
        $body = $this->handle(new HttpNotFoundException($this->request), false);

        self::assertSame(404, $body['status']);
        self::assertArrayNotHasKey('stack', $body['json']);
        self::assertNotSame(MintExceptionHandler::GENERIC_SERVER_ERROR_MESSAGE, $body['json']['message']);
    }

    public function testShowsDetailsInDeveloperMode(): void
    {
        $body = $this->handle(new \RuntimeException('boom'), true);

        self::assertSame('boom', $body['json']['message']);
        self::assertArrayHasKey('file', $body['json']);
        self::assertArrayHasKey('line', $body['json']);
        self::assertArrayHasKey('stack', $body['json']);
    }

    public function testNormalizesInvalidCodeToServerError(): void
    {
        $body = $this->handle(new \RuntimeException('duplicate', 23000), false);

        self::assertSame(500, $body['status']);
        self::assertSame(500, $body['json']['code']);
    }

    public function testLogsServerErrorDetails(): void
    {
        $this->handle(new \RuntimeException('boom'), false);

        self::assertCount(1, $this->logged);
        self::assertStringContainsString('boom', $this->logged[0]);
        self::assertStringContainsString(__FILE__, $this->logged[0]);
    }

    public function testIgnoresDisplayErrorDetailsFlagOfTheErrorMiddleware(): void
    {
        $response = (new MintExceptionHandler())($this->request, new \RuntimeException('boom'), true, true, true);
        $json = json_decode((string) $response->getBody(), true);

        self::assertSame(['message' => MintExceptionHandler::GENERIC_SERVER_ERROR_MESSAGE, 'code' => 500], $json);
    }

    public function testRespondsWithJsonContentType(): void
    {
        $response = (new MintExceptionHandler())($this->request, new \RuntimeException('boom'), false, true, true);

        self::assertSame('application/json', $response->getHeaderLine('Content-Type'));
    }

    /**
     * @return array{status: int, json: array}
     */
    private function handle(\Throwable $exception, bool $developer_mode): array
    {
        $GLOBALS['sugar_config'] = ['developerMode' => $developer_mode];
        $response = (new MintExceptionHandler())($this->request, $exception, false, true, true);

        return [
            'status' => $response->getStatusCode(),
            'json' => json_decode((string) $response->getBody(), true),
        ];
    }
}
