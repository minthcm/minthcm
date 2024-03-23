<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
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

$dictionary["allocations_employees"] = array(
    'true_relationship_type' => 'many-to-many',
    'from_studio' => true,
    'relationships' => array(
       'allocations_employees' => array(
          'lhs_module' => 'Allocations',
          'lhs_table' => 'allocations',
          'lhs_key' => 'id',
          'rhs_module' => 'Employees',
          'rhs_table' => 'users',
          'rhs_key' => 'id',
          'relationship_type' => 'many-to-many',
          'join_table' => 'allocations_employees',
          'join_key_lhs' => 'allocation_id',
          'join_key_rhs' => 'employee_id',
       ),
    ),
    'table' => 'allocations_employees',
    'fields' => array(
       array(
          'name' => 'id',
          'type' => 'varchar',
          'len' => 36,
       ),
       array(
          'name' => 'date_modified',
          'type' => 'datetime',
       ),
       array(
          'name' => 'deleted',
          'type' => 'bool',
          'len' => '1',
          'default' => '0',
          'required' => true,
       ),
       array(
          'name' => 'allocation_id',
          'type' => 'varchar',
          'len' => 36,
       ),
       array(
          'name' => 'employee_id',
          'type' => 'varchar',
          'len' => 36,
       ),
    ),
    'indices' => array(
       array(
          'name' => 'allocations_users_spk',
          'type' => 'primary',
          'fields' => array(
             'id',
          ),
       ),
       array(
          'name' => 'allocation_lhs_alt',
          'type' => 'index',
          'fields' => array(
             'allocation_id',
          ),
       ),
       array(
          'name' => 'user_rhs_alt',
          'type' => 'index',
          'fields' => array(
             'employee_id',
          ),
       ),
    ),
 );
