<?php

/* * *******************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.
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
 * FOR A PARTICULAR PURPOSE.  See the GNU Affero General Public License for more
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
 * these Appropriate Legal Notices must retain the display of the "Powered by
 * SugarCRM" logo. If the display of the logo is not reasonably feasible for
 * technical reasons, the Appropriate Legal Notices must display the words
 * "Powered by SugarCRM".
 * ****************************************************************************** */

$dictionary['WorkingMonths'] = array(
   'table' => 'workingmonths',
   'audited' => false,
   'duplicate_merge' => true,
   'fields' => array(
      'year' =>
      array(
         'required' => true,
         'name' => 'year',
         'vname' => 'LBL_YEAR',
         'type' => 'int',
         'massupdate' => 0,
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => false,
         'reportable' => true,
         'unified_search' => false,
         'merge_filter' => 'disabled',
         'len' => '255',
         'size' => '20',
         'enable_range_search' => false,
         'disable_num_format' => true,
         'min' => 2015,
         'max' => 2050,
         'validation' =>
         array(
            'type' => 'range',
            'min' => 2015,
            'max' => 2050,
         ),
      ),
      'months' =>
      array(
         'required' => true,
         'name' => 'months',
         'vname' => 'LBL_MONTHS',
         'type' => 'enum',
         'massupdate' => 0,
         'default' => 'january',
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => false,
         'reportable' => true,
         'unified_search' => false,
         'merge_filter' => 'disabled',
         'len' => 100,
         'size' => '20',
         'options' => 'months_list',
         'studio' => 'visible',
         'dependency' => false,
      ),
      'working_days' =>
      array(
         'required' => true,
         'name' => 'working_days',
         'vname' => 'LBL_WORKING_DAYS',
         'type' => 'int',
         'massupdate' => 0,
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => false,
         'reportable' => true,
         'unified_search' => false,
         'merge_filter' => 'disabled',
         'len' => '255',
         'size' => '20',
         'enable_range_search' => false,
         'disable_num_format' => '',
         'min' => 0,
         'max' => false,
         'validation' =>
         array(
            'type' => 'range',
            'min' => 0,
            'max' => false,
         ),
      ),
      'working_hours' =>
      array(
         'required' => true,
         'name' => 'working_hours',
         'vname' => 'LBL_WORKING_HOURS',
         'type' => 'int',
         'massupdate' => 0,
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => false,
         'reportable' => true,
         'unified_search' => false,
         'merge_filter' => 'disabled',
         'len' => '255',
         'size' => '20',
         'enable_range_search' => false,
         'disable_num_format' => '',
         'min' => 0,
         'max' => false,
         'validation' =>
         array(
            'type' => 'range',
            'min' => 0,
            'max' => false,
         ),
      ),
   ),
   'relationships' => array(
   ),
   'optimistic_locking' => true,
   'unified_search' => true,
);
if ( !class_exists('VardefManager') ) {
   require_once('include/SugarObjects/VardefManager.php');
}
VardefManager::createVardef('WorkingMonths', 'WorkingMonths', array( 'basic', 'assignable', 'security_groups' ));
