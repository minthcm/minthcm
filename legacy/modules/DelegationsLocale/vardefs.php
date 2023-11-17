<?php

/* * *******************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2011 SugarCRM Inc.
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

$dictionary['DelegationsLocale'] = array(
   'table' => 'delegationslocale',
   'audited' => true,
   'duplicate_merge' => true,
   'fields' => array(
      'regimen_value' => array(
         'required' => true,
         'name' => 'regimen_value',
         'vname' => 'LBL_REGIMEN_VALUE',
         'type' => 'currency',
         'massupdate' => 0,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'reportable' => true,
         'unified_search' => false,
         'len' => 26,
         'size' => '20',
         'enable_range_search' => false,
      ),
      'currency_id' => array(
         'required' => false,
         'name' => 'currency_id',
         'vname' => 'LBL_CURRENCY',
         'type' => 'id',
         'massupdate' => 0,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => 0,
         'audited' => true,
         'reportable' => true,
         'unified_search' => false,
         'len' => 36,
         'size' => '20',
         'studio' => 'visible',
         'function' => array(
            'name' => 'getCurrencyDropDown',
            'returns' => 'html',
         ),
      ),
      'accommodation_value' => array(
         'required' => true,
         'name' => 'accommodation_value',
         'vname' => 'LBL_ACCOMMODATION_VALUE',
         'type' => 'currency',
         'massupdate' => 0,
         'comments' => '',
         'help' => '',
         'importable' => 'true',
         'duplicate_merge' => 'disabled',
         'duplicate_merge_dom_value' => '0',
         'audited' => true,
         'reportable' => true,
         'unified_search' => false,
         'len' => 26,
         'size' => '20',
         'enable_range_search' => false,
      ),
      'archival' => array(
         'name' => 'archival',
         'vname' => 'LBL_ARCHIVAL',
         'comments' => '',
         'help' => '',
         'type' => 'bool',
         'required' => false,
         'default' => '0',
         'audited' => true,
         'massupdate' => 0,
         'duplicate_merge' => 'disabled',
         'reportable' => true,
         'importable' => true,
      ),
      "delegations" => array(
         'name' => 'delegations',
         'type' => 'link',
         'relationship' => 'delegationslocale_delegations',
         'source' => 'non-db',
         'side' => 'right',
         'vname' => 'LBL_DELEGATIONS',
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
VardefManager::createVardef('DelegationsLocale', 'DelegationsLocale', array(
   'basic',
   'assignable',
   'security_groups'
));
