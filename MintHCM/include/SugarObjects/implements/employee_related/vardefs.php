<?php

/* * *******************************************************************************
 * SugarCRM Community Edition is a customer relationship management program developed by
 * SugarCRM, Inc. Copyright (C) 2004-2013 SugarCRM Inc.

 * SuiteCRM is an extension to SugarCRM Community Edition developed by Salesagility Ltd.
 * Copyright (C) 2011 - 2014 Salesagility Ltd.
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
 * SugarCRM" logo and "Supercharged by SuiteCRM" logo. If the display of the logos is not
 * reasonably feasible for  technical reasons, the Appropriate Legal Notices must
 * display the words  "Powered by SugarCRM" and "Supercharged by SuiteCRM".
 * ****************************************************************************** */

$vardefs = array(
    'fields' => array(
        'employee_id' => array(
            'name' => 'employee_id',
            'rname' => 'user_name',
            'id_name' => 'employee_id',
            'vname' => 'LBL_EMPLOYEE_ID',
            'group' => 'employee_name',
            'type' => 'relate',
            'table' => 'users',
            'module' => 'Employees',
            'reportable' => true,
            'isnull' => 'false',
            'dbType' => 'id',
            'audited' => true,
            'comment' => 'Employee assigned to record',
            'duplicate_merge' => 'disabled',
        ),
        'employee_name' => array(
            'name' => 'employee_name',
            'link' => 'employee_link',
            'vname' => 'LBL_EMPLOYEE_NAME',
            'rname' => 'full_name',
            'fields' => array(
                'first_name',
                'last_name',
            ),
            'db_concat_fields' => array(
                0 => 'first_name',
                1 => 'last_name',
            ),
            'type' => 'relate',
            'reportable' => false,
            'source' => 'non-db',
            'table' => 'users',
            'id_name' => 'employee_id',
            'module' => 'Employees',
         'duplicate_merge' => 'disabled',
         'join_name' => 'employee_related',
        ),
        'employee_link' => array(
            'name' => 'employee_link',
            'type' => 'link',
            'relationship' => strtolower($module) . '_employee',
            'vname' => 'LBL_EMPLOYEE',
            'link_type' => 'one',
            'module' => 'Employees',
            'bean_name' => 'Employee',
            'source' => 'non-db',
            'duplicate_merge' => 'enabled',
            'rname' => 'full_name',
            'id_name' => 'employee_id',
            'table' => 'users',
        ),
    ),
    'relationships' => array(
        strtolower($module) . '_employee' => array(
            'lhs_module' => $module,
            'lhs_table' => $table_name,
            'lhs_key' => 'employee_id',
            'rhs_module' => 'Employees',
            'rhs_table' => 'users',
            'rhs_key' => 'id',
            'relationship_type' => 'one-to-many',
        ),
    ),
);
