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

namespace MintHCM\Api\Repositories;

use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;
use MintHCM\Api\Entities\Users;
use MintHCM\Data\ORM\Doctrine\MintRepository\MintEntityRepository;

class UsersRepository extends MintEntityRepository implements UserRepositoryInterface
{
    /**
     * @inheritdoc
     *
     * @throws \InvalidArgumentException If user does not exist or the password is invalid.
     */
    public function getUserEntityByUserCredentials(
        $username,
        $password,
        $grantType,
        ClientEntityInterface $clientEntity
    ) {

        chdir('../legacy/');
        $is_ldap_enabled = $this->IsLdapOn();
        if($is_ldap_enabled && !(new \AuthenticationController())->authController->loginAuthenticate($username, $password, false, [])){
            throw new \InvalidArgumentException("The password is invalid: {$password} or username is invalid: {$username}");   
        }
        chdir('../api/');
        
        /** @var Users */
        $user = $this->findOneBy(['user_name' => $username, 'deleted' => false]);
        if (!$user) {
            throw new \InvalidArgumentException('No user found with this username: ' . $username);
        }

        if (!$is_ldap_enabled && $user->checkPassword($password) === false) {
            throw new \InvalidArgumentException('The password is invalid: ' . $password);
        }

        return $user;
    }
    
    /*
     * Get active users list
     *
     * @param string|null $user_id User ID to exclude from the list
     * @return Users[] List of active users
     */
    public function getActiveUsers($user_id = null): array
    {
        $where_user_id = !empty($user_id) ? 'AND u.id != :user_id' : '';
        $qb = $this->createQueryBuilder('u');
        $qb
            ->where("u.deleted = 0 AND u.status = 'Active' {$where_user_id}")
            ->orderBy('u.first_name', 'ASC')
            ->addOrderBy('u.last_name', 'ASC')
        ;
        if (!empty($user_id)) {
            $qb->setParameter('user_id', $user_id);
        }
        
        return $qb->getQuery()->getResult();
    }

    private function IsLdapOn(): bool
    {
        global $system_config;
        return !empty($system_config->settings['system_ldap_enabled']) && $system_config->settings['system_ldap_enabled'] == true;
    }

    public function getActiveEmployedUsers($user_id = null): array
    {
        $where_user_id = !empty($user_id) ? 'AND u.id != :user_id' : '';
        $qb = $this->createQueryBuilder('u');
        $qb
            ->where("u.deleted = 0 AND u.status = 'Active' AND u.employee_status = 'Active' {$where_user_id}")
            ->orderBy('u.first_name', 'ASC')
            ->addOrderBy('u.last_name', 'ASC')
        ;
        if (!empty($user_id)) {
            $qb->setParameter('user_id', $user_id);
        }
        
        return $qb->getQuery()->getResult();
    }
}
