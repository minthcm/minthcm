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
 * Copyright (C) 2018-2019 MintHCM
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

/**
 * Checks if current_user or typed user by ID is in typed role by ID.
 * EOU:
 * "isUserInRole( 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxx' )" will give us "true" if current_user is in role with ID: 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxx'
 * "isUserInRole( 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxx', 'yyyyyyyy-yyyy-yyyy-yyyy-yyyyyyyy' )" will give us "true" if user with ID: 'yyyyyyyy-yyyy-yyyy-yyyy-yyyyyyyy' is in role with ID: 'xxxxxxxx-xxxx-xxxx-xxxx-xxxxxxxx'
 */
class VTExpression_isUserInRole extends VTExpression {

   public $availability = array( 'vt_calculated', 'vt_dependency', 'vt_required', 'vt_readonly', 'vt_duplicate', 'vt_validation', 'related' );
   public $serversideFrontend = true;
   public $sqlBackendFormula = false;
   public $inputParams = array( 'role_id', 'user_id' );

   public function backend($arguments = array()) {
      global $current_user;
      $result = false;
      if ( !empty($arguments['role_id']) ) {
         $role_id = $arguments['role_id'];
         if ( empty($arguments['user_id']) ) {
            $result = $this->isRelatedUserWithRole($current_user->id, $role_id);
         } else {
            $user = BeanFactory::getBean('Users', $arguments['user_id']);
            if ( $user ) {
               $result = $this->isRelatedUserWithRole($user->id, $role_id);
            }
         }
      }
      return $result;
   }

   protected function isRelatedUserWithRole($user_id, $role_id) {
      $db = DBManagerFactory::getInstance();
      $result = false;
      if ( !empty($user_id) && !empty($role_id) ) {
         $sql = "
            SELECT
               1
            FROM
               acl_roles AS aclr
            LEFT JOIN
               acl_roles_users AS aclru
            ON
               aclru.role_id = aclr.id
            WHERE
               aclr.deleted = '0'
               AND aclr.id = '{$role_id}'
               AND aclru.deleted = '0'
               AND aclru.user_id = '{$user_id}'
            LIMIT 1
         ";
         $result = !empty($db->getOne($sql));
      }
      return $result;
   }

}
