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
$dictionary['Alert'] = array(
   'table' => 'alerts',
   'audited' => false,
   'duplicate_merge' => true,
   'fields' => array(
      'is_read' => array(
         'name' => 'is_read',
         'vname' => 'LBL_IS_READ',
         'type' => 'bool',
         'massupdate' => false,
         'studio' => 'false',
      ),
      'is_closed' => array(
         'name' => 'is_closed',
         'vname' => 'LBL_IS_CLOSED',
         'type' => 'bool',
         'massupdate' => false,
         'studio' => 'false',
      ),
      'target_module' => array(
         'name' => 'target_module',
         'vname' => 'LBL_TYPE',
         'type' => 'varchar',
         'massupdate' => false,
         'studio' => 'false',
      ),
      'type' => array(
         'name' => 'type',
         'vname' => 'LBL_TYPE',
         'type' => 'varchar',
         'massupdate' => false,
         'studio' => 'false',
      ),
      'url_redirect' => array(
         'name' => 'url_redirect',
         'vname' => 'LBL_TYPE',
         'type' => 'varchar',
         'massupdate' => false,
         'studio' => 'false',
      ),
      'reminder_id' => array(
         'name' => 'reminder_id',
         'type' => 'id',
         'required' => false,
         'reportable' => false,
         'studio' => 'false',
         'comment' => 'The id of the reminder that created this alert',
      ),
      'alert_type' => array(
         'required' => false,
         'name' => 'alert_type',
         'vname' => 'LBL_ALERT_TYPE',
         'type' => 'enum',
         'massupdate' => false,
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'importable' => true,
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => false,
         'reportable' => true,
         'unified_search' => false,
         'merge_filter' => 'disabled',
         'options' => 'alert_type_list',
         'len' => 100,
         'size' => 20,
         'studio' => false,
      ),
      'parent_name' => array(
         'name' => 'parent_name',
         'parent_type' => 'record_type_display',
         'type_name' => 'parent_type',
         'id_name' => 'parent_id',
         'vname' => 'LBL_LIST_RELATED_TO',
         'type' => 'parent',
         'group' => 'parent_name',
         'source' => 'non-db',
         'options' => 'parent_type_display_notifications',
         'studio' => true,
         'massupdate' => false,
         'readonly' => true,
      ),
      'parent_type' => array(
         'name' => 'parent_type',
         'vname' => 'LBL_PARENT_TYPE',
         'type' => 'parent_type',
         'dbType' => 'varchar',
         'group' => 'parent_name',
         'options' => 'parent_type_display_notifications',
         'len' => 100,
         'comment' => 'Module notification is associated with.',
         'studio' => array(
            'searchview' => false,
            'wirelesslistview' => false
         ),
      ),
      'parent_id' => array(
         'name' => 'parent_id',
         'vname' => 'LBL_PARENT_ID',
         'type' => 'id',
         'group' => 'parent_name',
         'reportable' => false,
         'comment' => 'ID of item indicated by parent_type.',
         'studio' => array(
            'searchview' => false
         ),
      ),
   ),
   'relationships' => array(),
   'indices' => [
      array(
         'name' => 'idx_notifications_my_unread_items',
         'type' => 'index',
         'fields' => array(
            'assigned_user_id',
            'is_read',
            'deleted',
         ),
      ),
   ],
   'optimistic_locking' => true,
   'unified_search' => false,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('Alerts', 'Alert', array('basic', 'assignable'));
