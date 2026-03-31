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

namespace MintHCM\Api\Entities\OAuth2;

use Doctrine\ORM\Mapping as ORM;
use MintHCM\Data\TimeDate;
use Ramsey\Uuid\Doctrine\UuidGenerator;

/**
 * @ORM\Entity(repositoryClass="MintHCM\Api\Repositories\OAuth2\MintTokenRepository")
 * @ORM\Table(name="oauth2tokens")
 */
class MintToken
{
    /**
     * @ORM\Id
     * @ORM\Column(type="uuid", unique=true)
     * @ORM\GeneratedValue(strategy="CUSTOM")
     * @ORM\CustomIdGenerator(class=UuidGenerator::class)
     */
    public $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public $name;
    
    /**
     * @ORM\Column(type="string", nullable=true)
     */
    public $assigned_user_id;
    
    /**
     * @ORM\Column(type="string", length=36)
     */
    public $created_by;

    /**
     * @ORM\Column(type="datetime")
     */
    public $date_entered;
    
    /**
     * @ORM\Column(type="string", length=36)
     */
    public $modified_user_id;

    /**
     * @ORM\Column(type="datetime")
     */
    public $date_modified;

    /**
     * @ORM\Column(type="datetime")
     */
    public $date_indexed;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public $description;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    public $token_is_revoked;

    /**
     * @ORM\Column(type="string")
     */
    public $token_type;

    /**
     * @ORM\Column(type="datetime")
     */
    public $access_token_expires;

    /**
     * @ORM\Column(type="string", length=4000)
     */
    public $access_token;

    /**
     * @ORM\Column(type="datetime")
     */
    public $refresh_token_expires;

    /**
     * @ORM\Column(type="string", length=4000)
     */
    public $refresh_token;

    /**
     * @ORM\Column(type="string", length=100)
     */
    public $grant_type;

    /**
     * @ORM\Column(type="string", length=1024)
     */
    public $state;

    /**
     * @ORM\Column(type="string", length=36)
     */
    public $client;

    /**
     * @ORM\Column(type="boolean")
     */
    public $deleted = false;

    public function __construct()
    {
        $timedate = new TimeDate();
        $this->date_entered = $timedate->getNow();
        $this->date_modified = $timedate->getNow();
        $this->token_is_revoked = false;
    }

    public function setDataFromAccessToken(AccessToken $access_token)
    {
        $this->access_token = $access_token->getIdentifier();
        $this->access_token_expires = $access_token->getExpiryDateTime();

        /** @var Client */
        $client = $access_token->getClient();
        $this->client = $client->getIdentifier();

        switch ($client->allowed_grant_type) {
            case 'password':
            case 'mobile':
                $userId = $access_token->getUserIdentifier();
                break;
            case 'client_credentials':
                $userId = $client->assigned_user_id;
                break;
        }
        $this->assigned_user_id = $userId;
    }

    public function setDataFromRefreshToken(RefreshToken $refresh_token)
    {
        $this->refresh_token = $refresh_token->getIdentifier();
        $this->refresh_token_expires = $refresh_token->getExpiryDateTime();
    }

}
