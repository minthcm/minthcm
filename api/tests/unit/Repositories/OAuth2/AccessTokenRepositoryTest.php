<?php

namespace MintHCM\Tests\Unit\Repositories\OAuth2;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use MintHCM\Api\Entities\OAuth2\MintToken;
use MintHCM\Api\Repositories\OAuth2\AccessTokenRepository;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class AccessTokenRepositoryTest extends TestCase
{
    public function testReturnsTrueWhenAccessTokenIsNotFound(): void
    {
        $repository = new AccessTokenRepository($this->createEntityManager(null));

        self::assertTrue($repository->isAccessTokenRevoked('token-id'));
    }

    public function testReturnsTrueWhenAccessTokenIsExplicitlyRevoked(): void
    {
        $token = $this->createMintToken();
        $token->token_is_revoked = true;
        $token->access_token_expires = new \DateTime('+1 hour');

        $repository = new AccessTokenRepository($this->createEntityManager($token));

        self::assertTrue($repository->isAccessTokenRevoked('token-id'));
    }

    public function testReturnsTrueWhenAccessTokenIsExpired(): void
    {
        $token = $this->createMintToken();
        $token->token_is_revoked = false;
        $token->access_token_expires = new \DateTime('-1 hour');

        $repository = new AccessTokenRepository($this->createEntityManager($token));

        self::assertTrue($repository->isAccessTokenRevoked('token-id'));
    }

    public function testReturnsFalseWhenAccessTokenIsValid(): void
    {
        $token = $this->createMintToken();
        $token->token_is_revoked = false;
        $token->access_token_expires = new \DateTime('+1 hour');

        $repository = new AccessTokenRepository($this->createEntityManager($token));

        self::assertFalse($repository->isAccessTokenRevoked('token-id'));
    }

    public function testThrowsExceptionWhenRevokingNonExistentAccessToken(): void
    {
        $repository = new AccessTokenRepository($this->createEntityManager(null));

        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Access token is not found for this client');

        $repository->revokeAccessToken('token-id');
    }

    public function testSetsDeletedAndFlushesWhenRevokingAccessToken(): void
    {
        $token = $this->createMintToken();
        $token->deleted = false;

        $em = $this->createEntityManager($token);
        $em->expects($this->once())->method('persist')->with($token);
        $em->expects($this->once())->method('flush');

        $repository = new AccessTokenRepository($em);
        $repository->revokeAccessToken('token-id');

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
            ->with(['access_token' => 'token-id'])
            ->willReturn($token);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->method('getRepository')->with(MintToken::class)->willReturn($token_repository);

        return $em;
    }
}
