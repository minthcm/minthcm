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

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

require_once('include/MVC/View/views/view.edit.php');

class SpentTimeViewEdit extends ViewEdit {

   protected $user;

   public function __construct() {
      if ( !empty($_REQUEST['workschedules_id']) ) {
         $ws = BeanFactory::getBean('WorkSchedules', $_REQUEST['workschedules_id']);
         $this->user = BeanFactory::getBean('Users', $ws->assigned_user_id);
      } else {
         $this->user = $GLOBALS['current_user'];
      }
      parent::__construct();
   }

   public function display() {
      global $current_user;
      if ( ($this->bean->assigned_user_id && is_null($this->bean->employee_id)) || !$this->bean->assigned_user_id ) {
         $this->bean->assigned_user_id = $this->user->id;
         $this->bean->assigned_user_name = $this->user->name;
      } elseif ( $this->bean->employee_id && $this->bean->employee_id != $current_user->id ) {
         $this->bean->assigned_user_id = $this->bean->employee_id;
         $this->bean->assigned_user_name = $this->bean->employee_name;
      }

      if ( !$this->bean->employee_id ) {
         $this->bean->employee_id = $this->user->id;
         $this->bean->employee_name = $this->user->name;
      }
      $this->ev->ss->assign('CURRENT_USER_IS_ADMIN', is_admin($current_user));
      return parent::display();
   }

}
