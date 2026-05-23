<?php



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
