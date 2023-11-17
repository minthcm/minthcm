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

$dictionary['PeriodsOfEmployment'] = array(
   'table' => 'periodsofemployment',
   'audited' => true,
   'inline_edit' => true,
   'duplicate_merge' => true,
   'fields' => array(
      'name' =>
      array(
         'name' => 'name',
         'vname' => 'LBL_NAME',
         'type' => 'name',
         'link' => true,
         'dbType' => 'varchar',
         'len' => '255',
         'unified_search' => false,
         'full_text_search' =>
         array(
            'boost' => 3,
         ),
         'required' => true,
         'importable' => 'required',
         'duplicate_merge' => 'enabled',
         'merge_filter' => 'disabled',
         'massupdate' => 0,
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'duplicate_merge_dom_value' => '1',
         'audited' => true,
         'inline_edit' => true,
         'reportable' => true,
         'size' => '20',
         'vt_readonly' => 'equals(1,1)',
      ),
      'period_ending_date' =>
      array(
         'name' => 'period_ending_date',
         'vname' => 'LBL_PERIOD_ENDING_DATE',
         'type' => 'date',
         'massupdate' => '1',
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'inline_edit' => true,
         'reportable' => true,
         'unified_search' => false,
         'merge_filter' => 'disabled',
         'size' => '20',
         'enable_range_search' => true,
         'dbType' => 'datetime',
         'options' => 'date_range_search_dom',
      ),
      'period_starting_date' =>
      array(
         'name' => 'period_starting_date',
         'vname' => 'LBL_PERIOD_STARTING_DATE',
         'type' => 'date',
         'massupdate' => '1',
         'no_default' => false,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'inline_edit' => true,
         'reportable' => true,
         'unified_search' => false,
         'merge_filter' => 'disabled',
         'size' => '20',
         'enable_range_search' => true,
         'dbType' => 'datetime',
         'validation' => array( 'type' => 'isbefore', 'compareto' => 'period_ending_date' ),
         'options' => 'date_range_search_dom',
      ),
      "contracts" => array(
         'name' => 'contracts',
         'type' => 'link',
         'relationship' => 'periodsofemployment_contracts',
         'source' => 'non-db',
         'module' => 'Contracts',
         'bean_name' => 'Contracts',
         'side' => 'right',
         'vname' => 'LBL_CONTRACTS',
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
VardefManager::createVardef('PeriodsOfEmployment', 'PeriodsOfEmployment', array(
   'basic',
   'assignable',
   'security_groups',
   'employee_related'
        )
);
