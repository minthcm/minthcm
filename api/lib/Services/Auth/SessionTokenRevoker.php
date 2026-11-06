<?php

namespace MintHCM\Lib\Services\Auth;

use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use Lcobucci\JWT\Encoding\JoseEncoder;
use Lcobucci\JWT\Token\Parser;
use MintHCM\Api\Entities\OAuth2\MintToken;
use MintHCM\Api\Repositories\OAuth2\AccessTokenRepository;

/**
 * Revokes the OAuth token pair behind a JWT access token, so it stops working now instead of at its
 * natural expiry (logout, session timeout). Revoking the access token flags the whole MintToken row,
 * which also holds the refresh token, so both are rejected afterwards.
 */
class SessionTokenRevoker
{
    public function __construct(private EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param string      $jwt           access token as issued to the client
     * @param string|null $owner_user_id when the JWT comes from the request (Bearer header) the stored
     *                                   token must belong to this user; null when it comes from our own
     *                                   session and is trusted
     */
    public function revoke(string $jwt, ?string $owner_user_id = null): void
    {
        if ($jwt === '') {
            return;
        }

        // The token may already be expired (its refresh token is still live), so the
        // signature is not validated here; ownership of the stored token is checked instead.
        try {
            $token_id = (new Parser(new JoseEncoder()))->parse($jwt)->claims()->get('jti');
        } catch (\Throwable) {
            return;
        }
        if (!is_string($token_id) || $token_id === '') {
            return;
        }

        if ($owner_user_id !== null) {
            $mint_token = $this->entityManager->getRepository(MintToken::class)->findOneBy(['access_token' => $token_id]);
            if (empty($mint_token) || $mint_token->assigned_user_id !== $owner_user_id) {
                return;
            }
        }

        try {
            (new AccessTokenRepository($this->entityManager))->revokeAccessToken($token_id);
        } catch (InvalidArgumentException) {
            // token already gone - nothing to revoke
        }
    }
}
