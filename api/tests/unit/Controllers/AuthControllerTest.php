<?php

namespace MintHCM\Tests\Unit\Controllers;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use Doctrine\Persistence\ObjectRepository;
use MintHCM\Api\Controllers\AuthController;
use MintHCM\Api\Controllers\OAuth2\Controller as OAuth2Controller;
use MintHCM\Api\Entities\OAuth2\Client;
use MintHCM\Api\Entities\UsersPasswordLink;
use MintHCM\Api\Repositories\UsersPasswordLinkRepository;
use MintHCM\Lib\Services\Auth\ForgotPasswordThrottle;
use MintHCM\Lib\Services\Http\ClientIpResolver;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\HttpException;
use Slim\Psr7\Factory\ResponseFactory;
use Slim\Psr7\Response;
use SuiteCRM\Utility\SessionTimeout;

/**
 * Session id rotation after the SSO finalize step (session fixation defence)
 * and password reset bound to the token owner.
 */
// shared with the legacy layer, which the API test bootstrap does not autoload
require_once __DIR__ . '/../../../../legacy/lib/Utility/SessionTimeout.php';

final class AuthControllerTest extends TestCase
{
    private const PROVIDERS = [
        'OIDC' => ['authenticationClass' => 'OIDCAuthenticate', 'prefix' => 'oidc_oauth_pending', 'method' => 'finalizeOIDCAuth'],
        'SAML' => ['authenticationClass' => 'SAML2Authenticate', 'prefix' => 'saml_oauth_pending', 'method' => 'finalizeSAMLAuth'],
    ];

    private mixed $originalLog = null;

    protected function setUp(): void
    {
        $this->originalLog = $GLOBALS['log'] ?? null;
    }

    protected function tearDown(): void
    {
        unset($GLOBALS['sugar_config']);
        $_SESSION = [];
        if ($this->originalLog === null) {
            unset($GLOBALS['log']);
        } else {
            $GLOBALS['log'] = $this->originalLog;
        }
    }

    #[DataProvider('providers')]
    public function testSessionIdIsRotatedBeforeTokensAreStored(string $provider): void
    {
        $config = self::PROVIDERS[$provider];
        $this->givenPendingSsoLogin($config);

        $tokensStoredAtRotation = null;
        $controller = $this->createController($this->createOAuthController(200));
        $controller->expects($this->once())
            ->method('regenerateSession')
            ->willReturnCallback(function () use (&$tokensStoredAtRotation): void {
                $tokensStoredAtRotation = isset($_SESSION['oauth_access_token']);
            });

        $result = $controller->{$config['method']}($this->createRequest());

        self::assertTrue($result);
        self::assertFalse($tokensStoredAtRotation, 'the session id must change before the tokens are written');
        self::assertSame('access-token', $_SESSION['oauth_access_token']);
        self::assertSame('refresh-token', $_SESSION['oauth_refresh_token']);
        self::assertArrayHasKey(SessionTimeout::LOGIN_TIME_KEY, $_SESSION, 'SSO login must start the session timeout');
        self::assertArrayHasKey(SessionTimeout::LAST_ACTIVITY_KEY, $_SESSION);
    }

    #[DataProvider('providers')]
    public function testSessionIdIsNotRotatedWhenTokenRequestFails(string $provider): void
    {
        $config = self::PROVIDERS[$provider];
        $this->givenPendingSsoLogin($config);

        $controller = $this->createController($this->createOAuthController(401));
        $controller->expects($this->never())->method('regenerateSession');

        $result = $controller->{$config['method']}($this->createRequest());

        self::assertFalse($result);
        self::assertArrayNotHasKey('oauth_access_token', $_SESSION);
        self::assertArrayNotHasKey(SessionTimeout::LOGIN_TIME_KEY, $_SESSION);
    }

    public function testForgetPasswordIsRejectedWhenThrottled(): void
    {
        $throttle = $this->createMock(ForgotPasswordThrottle::class);
        $throttle->expects($this->once())->method('isAllowed')->with('john', '203.0.113.7')->willReturn(false);

        $controller = $this->getMockBuilder(AuthController::class)
            ->setConstructorArgs([$this->createMock(EntityManagerInterface::class)])
            ->onlyMethods(['getForgotPasswordThrottle', 'getClientIpResolver', 'runAfterResponse'])
            ->getMock();
        $controller->method('getForgotPasswordThrottle')->willReturn($throttle);
        $controller->method('getClientIpResolver')->willReturn(new ClientIpResolver());
        $controller->expects($this->never())->method('runAfterResponse');

        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')->willReturnMap([['username', null, 'john'], ['email', null, 'john@example.com']]);
        $request->method('getServerParams')->willReturn(['REMOTE_ADDR' => '203.0.113.7']);

        try {
            $controller->forgetPassword($request, (new ResponseFactory())->createResponse(), []);
            self::fail('Expected HttpException');
        } catch (HttpException $exception) {
            self::assertSame(429, $exception->getCode());
        }
    }

    public function testFailingResetTaskDoesNotChangeTheResponse(): void
    {
        // without fastcgi_finish_request (mod_php, CLI) the task runs before the response - an exception
        // from it must not become an error response only for valid login + e-mail pairs
        self::assertFalse(function_exists('fastcgi_finish_request'), 'test needs the synchronous path');
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

        $controller = new AuthController($this->createMock(EntityManagerInterface::class));
        $run_after_response = new \ReflectionMethod($controller, 'runAfterResponse');
        $run_after_response->invoke($controller, function (): void {
            throw new \RuntimeException('SMTP down');
        });

        unset($GLOBALS['log']);
        self::assertCount(1, $logged);
        self::assertStringContainsString('SMTP down', $logged[0]);
    }

    // Regression: resetForgetPassword() must derive the target account from the token owner —
    // a valid token for one account must not be usable to reset a different account's password
    // just by changing the "username" field. The mismatch is rejected, logged, and the token is
    // invalidated even though the reset did not succeed.
    public function testRejectsUsernameNotMatchingTokenOwner(): void
    {
        $this->givenPasswordLinkSettings();

        $link = new UsersPasswordLink();
        $link->username = 'token-owner';
        $link->deleted = false;

        $repository = $this->createUsersPasswordLinkRepository();
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 'owner-token'])
            ->willReturn($link);
        $repository->expects($this->once())
            ->method('markAllAsDeletedByUsername')
            ->with('token-owner');

        $logger = new class {
            public array $warnings = [];

            public function warn(string $message): void
            {
                $this->warnings[] = $message;
            }
        };
        $GLOBALS['log'] = $logger;

        $controller = new AuthController($this->createPasswordLinkEntityManager($repository));
        $response = $controller->resetForgetPassword(
            $this->createRequestWithAttributes(['token' => 'owner-token', 'username' => 'victim', 'new_password' => 'P4$$w0rd']),
            new Response(),
            []
        );

        self::assertSame(400, $response->getStatusCode());
        self::assertCount(1, $logger->warnings);
        self::assertStringContainsString('token-owner', $logger->warnings[0]);
    }

    public function testResetReturns400WithoutInvalidationWhenTokenDoesNotExist(): void
    {
        $this->givenPasswordLinkSettings();

        $repository = $this->createUsersPasswordLinkRepository();
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 'unknown-token'])
            ->willReturn(null);
        $repository->expects($this->never())
            ->method('markAllAsDeletedByUsername');

        $controller = new AuthController($this->createPasswordLinkEntityManager($repository));
        $response = $controller->resetForgetPassword(
            $this->createRequestWithAttributes(['token' => 'unknown-token', 'username' => 'user', 'new_password' => 'P4$$w0rd']),
            new Response(),
            []
        );

        self::assertSame(400, $response->getStatusCode());
    }

    public function testValidTokenReturns400WhenTokenDoesNotExist(): void
    {
        $this->givenPasswordLinkSettings();

        $repository = $this->createUsersPasswordLinkRepository();
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 'unknown-token'])
            ->willReturn(null);

        $controller = new AuthController($this->createPasswordLinkEntityManager($repository));
        $response = $controller->validToken(
            $this->createRequestWithAttributes(['token' => 'unknown-token']),
            new Response(),
            []
        );

        self::assertSame(400, $response->getStatusCode());
    }

    public static function providers(): array
    {
        return ['OIDC' => ['OIDC'], 'SAML' => ['SAML']];
    }

    private function givenPendingSsoLogin(array $config): void
    {
        $GLOBALS['sugar_config'] = ['authenticationClass' => $config['authenticationClass']];
        $_SESSION = [
            $config['prefix'] => true,
            $config['prefix'] . '_user' => 'john',
            $config['prefix'] . '_at' => time(),
        ];
    }

    private function givenPasswordLinkSettings(): void
    {
        $GLOBALS['sugar_config']['passwordsetting'] = ['linkexpiration' => false];
    }

    private function createController(OAuth2Controller $oauthController): AuthController&MockObject
    {
        $client = new Client();
        $client->secret = 'frontend-secret';

        $repository = $this->createMock(ObjectRepository::class);
        $repository->method('find')->with('frontend')->willReturn($client);
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('getRepository')->with(Client::class)->willReturn($repository);

        $controller = $this->getMockBuilder(AuthController::class)
            ->setConstructorArgs([$entityManager])
            ->onlyMethods(['createOAuthController', 'regenerateSession'])
            ->getMock();
        $controller->method('createOAuthController')->willReturn($oauthController);

        return $controller;
    }

    private function createOAuthController(int $status): OAuth2Controller&MockObject
    {
        $response = (new ResponseFactory())->createResponse($status);
        $response->getBody()->write(json_encode(['access_token' => 'access-token', 'refresh_token' => 'refresh-token']));

        $oauthController = $this->createMock(OAuth2Controller::class);
        $oauthController->method('accessToken')->willReturn($response);

        return $oauthController;
    }

    private function createRequest(): ServerRequestInterface&MockObject
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('withParsedBody')->willReturnSelf();

        return $request;
    }

    private function createUsersPasswordLinkRepository(): UsersPasswordLinkRepository&MockObject
    {
        // Map the identifier so Doctrine's magic findOneById() resolves to the mocked findOneBy().
        $metadata = new ClassMetadata(UsersPasswordLink::class);
        $metadata->mapField(['fieldName' => 'id', 'type' => 'string', 'id' => true]);

        return $this->getMockBuilder(UsersPasswordLinkRepository::class)
            ->setConstructorArgs([
                $this->createMock(EntityManagerInterface::class),
                $metadata,
            ])
            ->onlyMethods(['findOneBy', 'markAllAsDeletedByUsername'])
            ->getMock();
    }

    private function createPasswordLinkEntityManager(UsersPasswordLinkRepository $repository): EntityManagerInterface
    {
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('getRepository')
            ->with(UsersPasswordLink::class)
            ->willReturn($repository);

        return $entityManager;
    }

    private function createRequestWithAttributes(array $attributes): ServerRequestInterface
    {
        $request = $this->createMock(ServerRequestInterface::class);
        $request->method('getAttribute')
            ->willReturnCallback(fn(string $name) => $attributes[$name] ?? null);

        return $request;
    }
}
