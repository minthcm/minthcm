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
    use MintHCM\Api\Entities\Users;
    use MintHCM\Api\Repositories\UsersRepository;
    use PHPUnit\Framework\MockObject\MockObject;
    use PHPUnit\Framework\TestCase;

    final class UsersRepositoryTest extends TestCase
    {
        private string $current_directory;

        protected function setUp(): void
        {
            $this->current_directory = getcwd() ?: '';
            chdir(realpath(__DIR__ . '/../../../'));

            $system_config = new \stdClass();
            $system_config->settings = ['system_ldap_enabled' => false];
            $GLOBALS['system_config'] = $system_config;

            \AuthenticationControllerAuthStub::$login_result = true;
        }

        protected function tearDown(): void
        {
            unset($GLOBALS['system_config']);

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

            $repository = $this->createUsersRepository();
            $repository->expects($this->once())
                ->method('findOneBy')
                ->with(['user_name' => 'john', 'deleted' => false])
                ->willReturn($user);

            $result = $repository->getUserEntityByUserCredentials('john', 'secret', 'password', $this->createClientEntity());

            self::assertSame($user, $result);
        }

        public function testThrowsExceptionWhenUserDoesNotExist(): void
        {
            $repository = $this->createUsersRepository();
            $repository->expects($this->once())
                ->method('findOneBy')
                ->with(['user_name' => 'missing', 'deleted' => false])
                ->willReturn(null);

            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('No user found with this username: missing');

            $repository->getUserEntityByUserCredentials('missing', 'secret', 'password', $this->createClientEntity());
        }

        public function testThrowsExceptionWhenPasswordIsInvalidAndLdapIsDisabled(): void
        {
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

            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('The password is invalid: wrong-password');

            $repository->getUserEntityByUserCredentials('john', 'wrong-password', 'password', $this->createClientEntity());
        }

        public function testThrowsExceptionWhenLdapAuthenticationFails(): void
        {
            $GLOBALS['system_config']->settings['system_ldap_enabled'] = true;
            \AuthenticationControllerAuthStub::$login_result = false;

            $repository = $this->createUsersRepository();
            $repository->expects($this->never())
                ->method('findOneBy');

            $this->expectException(\InvalidArgumentException::class);
            $this->expectExceptionMessage('The password is invalid: secret or username is invalid: john');

            $repository->getUserEntityByUserCredentials('john', 'secret', 'password', $this->createClientEntity());
        }

        public function testReturnsUserWhenLdapAuthenticationSucceeds(): void
        {
            $GLOBALS['system_config']->settings['system_ldap_enabled'] = true;
            \AuthenticationControllerAuthStub::$login_result = true;

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
            return $this->getMockBuilder(UsersRepository::class)
                ->setConstructorArgs([
                    $this->createMock(EntityManagerInterface::class),
                    new ClassMetadata(Users::class),
                ])
                ->onlyMethods(['findOneBy'])
                ->getMock();
        }

        private function createClientEntity(): ClientEntityInterface&MockObject
        {
            return $this->createMock(ClientEntityInterface::class);
        }
    }
}
