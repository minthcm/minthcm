<?php


/**
 *
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
 *
 * SuiteCRM is an extension to SugarCRM Community Edition developed by SalesAgility Ltd.
 * Copyright (C) 2011 - 2018 SalesAgility Ltd.
 *
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM, 
 * Copyright (C) 2018-2023 MintHCM
 *
 * This program is free software; you can redistribute it and/or modify it under
 * the terms of the GNU Affero General Public License version 3 as published by the
 * Free Software Foundation with the addition of the following permission added
 * to Section 15 as permitted in Section 7(a): FOR ANY PART OF THE COVERED WORK
 * IN WHICH THE COPYRIGHT IS OWNED BY SUGARCRM, SUGARCRM DISCLAIMS THE WARRANTY
 * OF NON INFRINGEMENT OF THIRD PARTY RIGHTS.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT
 * ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS
 * FOR A PARTICULAR PURPOSE. See the GNU Affero General Public License for more
 * details.
 *
 * You should have received a copy of the GNU Affero General Public License along with
 * this program; if not, see http://www.gnu.org/licenses or write to the Free
 * Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA
 * 02110-1301 USA.
 *
 * You can contact SugarCRM, Inc. headquarters at 10050 North Wolfe Road,
 * SW2-130, Cupertino, CA 95014, USA. or at email address contact@sugarcrm.com.
 *
 * The interactive user interfaces in modified source and object code versions
 * of this program must display Appropriate Legal Notices, as required under
 * Section 5 of the GNU Affero General Public License version 3.
 *
 * In accordance with Section 7(b) of the GNU Affero General Public License version 3,
 * these Appropriate Legal Notices must retain the display of the "Powered by SugarCRM" 
 * logo and "Supercharged by SuiteCRM" logo and "Reinvented by MintHCM" logo. 
 * If the display of the logos is not reasonably feasible for technical reasons, the 
 * Appropriate Legal Notices must display the words "Powered by SugarCRM" and 
 * "Supercharged by SuiteCRM" and "Reinvented by MintHCM".
 */

namespace MintHCM\Api\Repositories\OAuth2;

use Doctrine\ORM\EntityManagerInterface;
use InvalidArgumentException;
use League\OAuth2\Server\Entities\AccessTokenEntityInterface;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\AccessTokenRepositoryInterface;
use MintHCM\Api\Entities\OAuth2\AccessToken;
use MintHCM\Api\Entities\OAuth2\MintToken;

class AccessTokenRepository implements AccessTokenRepositoryInterface
{
    protected $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    /**
     * @inheritdoc
     */
    public function getNewToken(ClientEntityInterface $client, array $scopes, $userIdentifier = null)
    {
        $access_token = new AccessToken();
        $access_token->setClient($client);

        // we keep this even we don't have scopes atm
        foreach ($scopes as $scope) {
            $access_token->addScope($scope);
        }

        $access_token->setUserIdentifier($userIdentifier);

        return $access_token;
    }

    /**
     * @inheritdoc
     */
    public function persistNewAccessToken(AccessTokenEntityInterface $access_token)
    {
        $mint_token = new MintToken();
        $mint_token->setDataFromAccessToken($access_token);
        $this->entityManager->persist($mint_token);
        $this->entityManager->flush();
    }

    /**
     * @inheritdoc
     *
     * @throws InvalidArgumentException When access token is not found.
     */
    public function revokeAccessToken($tokenId)
    {
        $mint_token_repository = $this->entityManager->getRepository(MintToken::class);
        $token = $mint_token_repository->findOneBy(['access_token' => $tokenId]);

        if (empty($token)) {
            throw new InvalidArgumentException('Access token is not found for this client');
        }

        $token->deleted = true;
        $this->entityManager->persist($token);
        $this->entityManager->flush();
    }

    /**
     * @inheritdoc
     */
    public function isAccessTokenRevoked($tokenId)
    {
        $mint_token_repository = $this->entityManager->getRepository(MintToken::class);
        $token = $mint_token_repository->findOneBy(['access_token' => $tokenId]);

        return $token->id === null || $token->token_is_revoked === '1' || new \DateTime() > $token->access_token_expires;
    }
}
