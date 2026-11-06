<?php

namespace MintHCM\Tests\Unit\Lib\Services\Auth;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ObjectRepository;
use MintHCM\Api\Entities\OAuth2\MintToken;
use MintHCM\Lib\Services\Auth\SessionTokenRevoker;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

final class SessionTokenRevokerTest extends TestCase
{
    private const TOKEN_ID = 'token-id';

    public function testRevokesTokenFromSessionWithoutOwnerCheck(): void
    {
        $token = $this->createMintToken('user-1');
        $em = $this->createEntityManager($token);
        $em->expects($this->once())->method('flush');

        (new SessionTokenRevoker($em))->revoke($this->createJwt(self::TOKEN_ID));

        self::assertTrue($token->token_is_revoked);
    }

    public function testRevokesTokenOwnedByUser(): void
    {
        $token = $this->createMintToken('user-1');

        (new SessionTokenRevoker($this->createEntityManager($token)))->revoke($this->createJwt(self::TOKEN_ID), 'user-1');

        self::assertTrue($token->token_is_revoked);
    }

    public function testDoesNotRevokeTokenOfAnotherUser(): void
    {
        $token = $this->createMintToken('user-2');
        $em = $this->createEntityManager($token);
        $em->expects($this->never())->method('flush');

        (new SessionTokenRevoker($em))->revoke($this->createJwt(self::TOKEN_ID), 'user-1');

        self::assertFalse($token->token_is_revoked);
    }

    public function testIgnoresMalformedJwt(): void
    {
        $em = $this->createMock(EntityManagerInterface::class);
        $em->expects($this->never())->method('getRepository');

        (new SessionTokenRevoker($em))->revoke('not-a-jwt');
        (new SessionTokenRevoker($em))->revoke('');
    }

    public function testIgnoresUnknownToken(): void
    {
        $em = $this->createEntityManager(null);
        $em->expects($this->never())->method('flush');

        (new SessionTokenRevoker($em))->revoke($this->createJwt(self::TOKEN_ID));
    }

    private function createJwt(string $token_id): string
    {
        $encode = static fn(array $data): string => rtrim(strtr(base64_encode(json_encode($data)), '+/', '-_'), '=');

        return $encode(['typ' => 'JWT', 'alg' => 'RS256']) . '.' . $encode(['jti' => $token_id]) . '.c2lnbmF0dXJl';
    }

    private function createMintToken(string $owner): MintToken
    {
        $token = $this->getMockBuilder(MintToken::class)->disableOriginalConstructor()->getMock();
        $token->assigned_user_id = $owner;
        $token->token_is_revoked = false;

        return $token;
    }

    private function createEntityManager(?MintToken $token): EntityManagerInterface&MockObject
    {
        $repository = $this->createMock(ObjectRepository::class);
        $repository->method('findOneBy')->with(['access_token' => self::TOKEN_ID])->willReturn($token);

        $em = $this->createMock(EntityManagerInterface::class);
        $em->method('getRepository')->with(MintToken::class)->willReturn($repository);

        return $em;
    }
}
