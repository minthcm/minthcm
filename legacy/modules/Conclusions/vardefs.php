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

$dictionary['Conclusions'] = array(
   'table' => 'conclusions',
   'audited' => true,
   'inline_edit' => true,
   'duplicate_merge' => true,
   'fields' => array(
      "improvements" => array( 
         'name' => 'improvements',
         'type' => 'link',
         'relationship' => 'conclusions_improvements',
         'source' => 'non-db',
         'module' => 'Improvements',
         'bean_name' => 'Improvements',
         'vname' => 'LBL_IMPROVEMENTS',
      ),
      "problems" => array( 
         'name' => 'problems',
         'type' => 'link',
         'relationship' => 'conclusions_problems',
         'source' => 'non-db',
         'module' => 'Problems',
         'bean_name' => 'Problems',
         'vname' => 'LBL_PROBLEMS', 
      ),
      "meetings" => array(
         'name' => 'meetings',
         'type' => 'link',
         'relationship' => 'conclusions_meetings',
         'source' => 'non-db',
         'module' => 'Meetings',
         'bean_name' => 'Meeting',
         'vname' => 'LBL_MEETINGS',
         'id_name' => 'meeting_id',
      ),
      "meeting_name" => array(
         'name' => 'meeting_name',
         'type' => 'relate',
         'source' => 'non-db',
         'vname' => 'LBL_MEETING_NAME',
         'save' => true,
         'id_name' => 'meeting_id',
         'link' => 'meetings',
         'module' => 'Meetings',
         'table' => 'meetings',
         'rname' => 'name',
         'audited' => true,
         'importable' => true,
         'reportable' => true,
         'massupdate' => true,
         'duplicate_merge' => 'enabled',
      ),
      "meeting_id" => array(
         'name' => 'meeting_id',
         'relationship' => 'conclusions_meetings',
         'type' => 'id',
         'vname' => 'LBL_MEETING_ID',
         'audited' => false,
         'reportable' => false,
      ),
   ),
   'relationships' => array(
      "conclusions_meetings" => array(
         'lhs_module' => 'Meetings',
         'lhs_table' => 'meetings',
         'lhs_key' => 'id',
         'rhs_module' => 'Conclusions',
         'rhs_table' => 'conclusions',
         'rhs_key' => 'meeting_id',
         'relationship_type' => 'one-to-many',
      ),
   ),
   'optimistic_locking' => true,
   'unified_search' => true,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('Conclusions', 'Conclusions', array( 'basic', 'assignable', 'security_groups', 'employee_related' ));

$dictionary['Conclusions']['fields']['name']['audited'] = true;