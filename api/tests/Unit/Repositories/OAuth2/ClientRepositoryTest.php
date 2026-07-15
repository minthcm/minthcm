<?php

namespace MintHCM\Tests\Unit\Repositories\OAuth2;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Mapping\ClassMetadata;
use MintHCM\Api\Entities\OAuth2\Client;
use MintHCM\Api\Repositories\OAuth2\ClientRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class ClientRepositoryTest extends TestCase
{
    public function testReturnsClientEntityWhenItExists(): void
    {
        $client = new Client();
        $client->id = 'client-1';

        $repository = $this->createClientRepository();
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 'client-1', 'deleted' => false])
            ->willReturn($client);

        self::assertSame($client, $repository->getClientEntity('client-1'));
    }

    public function testReturnsNullWhenClientEntityDoesNotExist(): void
    {
        $repository = $this->createClientRepository();
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 'missing-client', 'deleted' => false])
            ->willReturn(null);

        self::assertNull($repository->getClientEntity('missing-client'));
    }

    public function testValidatesPlaintextSecret(): void
    {
        $client = new Client();
        $client->secret = 'plain-secret';
        $client->allowed_grant_type = 'password';

        $repository = $this->createClientRepository();
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 'client-1', 'deleted' => false])
            ->willReturn($client);

        self::assertTrue($repository->validateClient('client-1', 'plain-secret', 'password'));
    }

    public function testValidatesHashedSecretForNonFrontendGrant(): void
    {
        $client = new Client();
        $client->secret = hash('sha256', 'plain-secret');
        $client->allowed_grant_type = 'password';

        $repository = $this->createClientRepository();
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 'client-1', 'deleted' => false])
            ->willReturn($client);

        self::assertTrue($repository->validateClient('client-1', 'plain-secret', 'password'));
    }

    public function testAcceptsRefreshTokenForPasswordBasedClient(): void
    {
        $client = new Client();
        $client->secret = 'plain-secret';
        $client->allowed_grant_type = 'password';

        $repository = $this->createClientRepository();
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 'client-1', 'deleted' => false])
            ->willReturn($client);

        self::assertTrue($repository->validateClient('client-1', 'plain-secret', 'refresh_token'));
    }

    public function testReturnsFalseWhenClientDoesNotExistDuringValidation(): void
    {
        $repository = $this->createClientRepository();
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 'missing-client', 'deleted' => false])
            ->willReturn(null);

        self::assertFalse($repository->validateClient('missing-client', 'any-secret', 'password'));
    }

    public function testRejectsHashedSecretForFrontendGrant(): void
    {
        $client = new Client();
        $client->secret = hash('sha256', 'plain-secret');
        $client->allowed_grant_type = 'frontend';

        $repository = $this->createClientRepository();
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with(['id' => 'client-1', 'deleted' => false])
            ->willReturn($client);

        self::assertFalse($repository->validateClient('client-1', 'plain-secret', 'frontend'));
    }

    private function createClientRepository(): ClientRepository&MockObject
    {
        return $this->getMockBuilder(ClientRepository::class)
            ->setConstructorArgs([
                $this->createMock(EntityManagerInterface::class),
                new ClassMetadata(Client::class),
            ])
            ->onlyMethods(['findOneBy'])
            ->getMock();
    }
}
