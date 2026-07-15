<?php

namespace MintHCM\Tests\Unit\Repositories\OAuth2;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use MintHCM\Api\Entities\OAuth2\MintToken;
use MintHCM\Api\Repositories\OAuth2\RefreshTokenRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class RefreshTokenRepositoryTest extends TestCase
{
    public function testReturnsTrueWhenRefreshTokenIsNotFound(): void
    {
        $repository = new RefreshTokenRepository($this->createEntityManager(null));

        self::assertTrue($repository->isRefreshTokenRevoked('token-id'));
    }

    public function testReturnsTrueWhenRefreshTokenIsExpired(): void
    {
        $token = $this->createMintToken();
        $token->refresh_token_expires = new \DateTime('-1 hour');

        $repository = new RefreshTokenRepository($this->createEntityManager($token));

        self::assertTrue($repository->isRefreshTokenRevoked('token-id'));
    }

    public function testReturnsFalseWhenRefreshTokenIsValid(): void
    {
        $token = $this->createMintToken();
        $token->refresh_token_expires = new \DateTime('+1 hour');

        $repository = new RefreshTokenRepository($this->createEntityManager($token));

        self::assertFalse($repository->isRefreshTokenRevoked('token-id'));
    }

    public function testThrowsExceptionWhenRevokingNonExistentRefreshToken(): void
    {
        $repository = new RefreshTokenRepository($this->createEntityManager(null));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Refresh token is not found for this client');

        $repository->revokeRefreshToken('token-id');
    }

    public function testSetsDeletedAndFlushesWhenRevokingRefreshToken(): void
    {
        $token = $this->createMintToken();
        $token->deleted = false;

        $em = $this->createEntityManager($token);
        $em->expects($this->once())->method('persist')->with($token);
        $em->expects($this->once())->method('flush');

        $repository = new RefreshTokenRepository($em);
        $repository->revokeRefreshToken('token-id');

        self::assertTrue($token->deleted);
    }

    private function createMintToken(): MintToken&MockObject
    {
        return $this->getMockBuilder(MintToken::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    private function createEntityManager(?MintToken $token): EntityManagerInterface&MockObject
    {
        $token_repository = $this->createMock(ObjectRepository::class);
        $token_repository->method('findOneBy')
            ->with(['refresh_token' => 'token-id'])
            ->willReturn($token);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->method('getRepository')->with(MintToken::class)->willReturn($token_repository);

        return $em;
    }
}
