<?php

namespace {

    if (!class_exists('AuthenticationController')) {
        class AuthenticationController
        {
            public AuthenticationControllerAuthStub $authController;

            public function __construct()
            {
                $this->authController = new AuthenticationControllerAuthStub();
            }
        }

        class AuthenticationControllerAuthStub
        {
            public static bool $login_result = true;

            public function loginAuthenticate($username, $password, $use_password_as_hash, $params): bool
            {
                return self::$login_result;
            }
        }
    }
}

namespace MintHCM\Tests\Unit\Repositories {

    use Doctrine\ORM\EntityManagerInterface;
    use Doctrine\ORM\Mapping\ClassMetadata;
    use League\OAuth2\Server\Entities\ClientEntityInterface;
    use League\OAuth2\Server\Exception\OAuthServerException;
    use MintHCM\Api\Entities\Users;
    use MintHCM\Api\Repositories\UsersRepository;
    use MintHCM\Lib\Services\Auth\LoginThrottle;
    use PHPUnit\Framework\MockObject\MockObject;
    use PHPUnit\Framework\TestCase;

    final class UsersRepositoryTest extends TestCase
    {
        private string $current_directory;

        private LoginThrottle&MockObject $throttle;

        protected function setUp(): void
        {
            $this->current_directory = getcwd() ?: '';
            chdir(realpath(__DIR__ . '/../../../'));

            $system_config = new \stdClass();
            $system_config->settings = ['system_ldap_enabled' => false];
            $GLOBALS['system_config'] = $system_config;

            \AuthenticationControllerAuthStub::$login_result = true;

            $this->throttle = $this->createMock(LoginThrottle::class);
            $this->throttle->method('resolveClientIp')->willReturn('203.0.113.7');
            $_SERVER['REMOTE_ADDR'] = '203.0.113.7';
        }

        protected function tearDown(): void
        {
            unset($GLOBALS['system_config'], $GLOBALS['sugar_config'], $_SERVER['REMOTE_ADDR']);
            $_SESSION = [];

            if ($this->current_directory !== '') {
                chdir($this->current_directory);
            }
        }

        public function testReturnsUserWhenPasswordIsValidAndLdapIsDisabled(): void
        {
            $user = $this->createMock(Users::class);
            $user->expects($this->once())
                ->method('checkPassword')
                ->with('secret')
                ->willReturn(true);

            $this->throttle->expects($this->once())->method('assertAllowed')->with('john', '203.0.113.7');
            $this->throttle->expects($this->once())->method('registerSuccess')->with('john');
            $this->throttle->expects($this->never())->method('registerFailure');

            $repository = $this->createUsersRepository();
            $repository->expects($this->once())
                ->method('findOneBy')
                ->with(['user_name' => 'john', 'deleted' => false])
                ->willReturn($user);

            $result = $repository->getUserEntityByUserCredentials('john', 'secret', 'password', $this->createClientEntity());

            self::assertSame($user, $result);
        }

        public function testReturnsNullWhenUserDoesNotExist(): void
        {
            $this->throttle->expects($this->once())->method('registerFailure')->with('203.0.113.7');
            $this->throttle->expects($this->never())->method('registerSuccess');

            $repository = $this->createUsersRepository();
            $repository->expects($this->once())
                ->method('findOneBy')
                ->with(['user_name' => 'missing', 'deleted' => false])
                ->willReturn(null);

            $result = $repository->getUserEntityByUserCredentials('missing', 'secret', 'password', $this->createClientEntity());

            self::assertNull($result);
        }

        public function testReturnsNullWhenPasswordIsInvalidAndLdapIsDisabled(): void
        {
            $this->throttle->expects($this->once())->method('registerFailure')->with('203.0.113.7');
            $this->throttle->expects($this->never())->method('registerSuccess');

            $user = $this->createMock(Users::class);
            $user->expects($this->once())
                ->method('checkPassword')
                ->with('wrong-password')
                ->willReturn(false);

            $repository = $this->createUsersRepository();
            $repository->expects($this->once())
                ->method('findOneBy')
                ->with(['user_name' => 'john', 'deleted' => false])
                ->willReturn($user);

            $result = $repository->getUserEntityByUserCredentials('john', 'wrong-password', 'password', $this->createClientEntity());

            self::assertNull($result);
        }

        public function testReturnsNullWhenLdapAuthenticationFails(): void
        {
            $this->throttle->expects($this->once())->method('registerFailure')->with('203.0.113.7');

            $GLOBALS['system_config']->settings['system_ldap_enabled'] = true;
            \AuthenticationControllerAuthStub::$login_result = false;

            $repository = $this->createUsersRepository();
            $repository->expects($this->never())
                ->method('findOneBy');

            $result = $repository->getUserEntityByUserCredentials('john', 'secret', 'password', $this->createClientEntity());

            self::assertNull($result);
        }

        public function testPropagatesThrottleExceptionBeforeCheckingCredentials(): void
        {
            $this->throttle->method('assertAllowed')
                ->willThrowException(new OAuthServerException('Too many failed login attempts.', 0, 'too_many_attempts', 429));
            $this->throttle->expects($this->never())->method('registerFailure');
            $this->throttle->expects($this->never())->method('registerSuccess');

            $repository = $this->createUsersRepository();
            $repository->expects($this->never())->method('findOneBy');

            try {
                $repository->getUserEntityByUserCredentials('john', 'secret', 'password', $this->createClientEntity());
                self::fail('Expected OAuthServerException');
            } catch (OAuthServerException $exception) {
                self::assertSame(429, $exception->getHttpStatusCode());
            }
        }

        public function testSsoFinalizeIsNotThrottled(): void
        {
            $GLOBALS['sugar_config'] = ['authenticationClass' => 'SAML2Authenticate'];
            $_SESSION = [
                'saml_oauth_pending' => true,
                'saml_oauth_pending_user' => 'john',
                'saml_oauth_pending_at' => time(),
            ];

            $this->throttle->expects($this->never())->method('assertAllowed');
            $this->throttle->expects($this->never())->method('registerFailure');
            $this->throttle->expects($this->never())->method('registerSuccess');

            $user = $this->createMock(Users::class);
            $user->expects($this->never())->method('checkPassword');

            $repository = $this->createUsersRepository();
            $repository->method('findOneBy')->willReturn($user);

            $result = $repository->getUserEntityByUserCredentials('john', '', 'frontend', $this->createClientEntity());

            self::assertSame($user, $result);
        }

        public function testUpgradesOutdatedHashAfterSuccessfulLogin(): void
        {
            $user = $this->createMock(Users::class);
            $user->method('checkPassword')->willReturn(true);
            $user->method('passwordNeedsRehash')->willReturn(true);

            $repository = $this->createUsersRepository();
            $repository->method('findOneBy')->willReturn($user);
            $repository->expects($this->once())
                ->method('upgradePasswordHash')
                ->with($user, 'secret');

            $result = $repository->getUserEntityByUserCredentials('john', 'secret', 'password', $this->createClientEntity());

            self::assertSame($user, $result);
        }

        public function testDoesNotUpgradeCurrentHash(): void
        {
            $user = $this->createMock(Users::class);
            $user->method('checkPassword')->willReturn(true);
            $user->method('passwordNeedsRehash')->willReturn(false);

            $repository = $this->createUsersRepository();
            $repository->method('findOneBy')->willReturn($user);
            $repository->expects($this->never())->method('upgradePasswordHash');

            $repository->getUserEntityByUserCredentials('john', 'secret', 'password', $this->createClientEntity());
        }

        public function testDoesNotUpgradeHashAfterFailedLogin(): void
        {
            $user = $this->createMock(Users::class);
            $user->method('checkPassword')->willReturn(false);
            $user->method('passwordNeedsRehash')->willReturn(true);

            $repository = $this->createUsersRepository();
            $repository->method('findOneBy')->willReturn($user);
            $repository->expects($this->never())->method('upgradePasswordHash');

            self::assertNull($repository->getUserEntityByUserCredentials('john', 'wrong', 'password', $this->createClientEntity()));
        }

        public function testDoesNotUpgradeHashWhenPasswordWasNotChecked(): void
        {
            $GLOBALS['system_config']->settings['system_ldap_enabled'] = true;

            $user = $this->createMock(Users::class);
            $user->method('passwordNeedsRehash')->willReturn(true);

            $repository = $this->createUsersRepository();
            $repository->method('findOneBy')->willReturn($user);
            $repository->expects($this->never())->method('upgradePasswordHash');

            $repository->getUserEntityByUserCredentials('john', 'secret', 'password', $this->createClientEntity());
        }

        public function testReturnsUserWhenLdapAuthenticationSucceeds(): void
        {
            $GLOBALS['system_config']->settings['system_ldap_enabled'] = true;
            \AuthenticationControllerAuthStub::$login_result = true;

            $this->throttle->expects($this->once())->method('registerSuccess')->with('john');

            $user = $this->createMock(Users::class);
            $user->expects($this->never())
                ->method('checkPassword');

            $repository = $this->createUsersRepository();
            $repository->expects($this->once())
                ->method('findOneBy')
                ->with(['user_name' => 'john', 'deleted' => false])
                ->willReturn($user);

            $result = $repository->getUserEntityByUserCredentials('john', 'secret', 'password', $this->createClientEntity());

            self::assertSame($user, $result);
        }

        private function createUsersRepository(): UsersRepository&MockObject
        {
            $repository = $this->getMockBuilder(UsersRepository::class)
                ->setConstructorArgs([
                    $this->createMock(EntityManagerInterface::class),
                    new ClassMetadata(Users::class),
                ])
                ->onlyMethods(['findOneBy', 'getLoginThrottle', 'upgradePasswordHash'])
                ->getMock();
            $repository->method('getLoginThrottle')->willReturn($this->throttle);

            return $repository;
        }

        private function createClientEntity(): ClientEntityInterface&MockObject
        {
            return $this->createMock(ClientEntityInterface::class);
        }
    }
}
