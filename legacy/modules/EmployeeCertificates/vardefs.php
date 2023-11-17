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
$dictionary['EmployeeCertificates'] = array(
    'table' => 'employeecertificates',
    'audited' => true,
    'activity_enabled' => false,
    'duplicate_merge' => true,
    'fields' => array(
        'start_date' => array(
            'name' => 'start_date',
            'label' => 'LBL_START_DATE',
            'vname' => 'LBL_START_DATE',
            'type' => 'date',
            'audited' => true,
            'mass_update' => false,
            'duplicate_merge' => '1',
            'reportable' => true,
            'importable' => true,
            'options' => 'date_range_search_dom',
            'enable_range_search' => '1',
            'validation' => array('type' => 'isbefore', 'compareto' => 'end_date'),
        ),
        'end_date' => array(
            'name' => 'end_date',
            'label' => 'LBL_END_DATE',
            'vname' => 'LBL_END_DATE',
            'type' => 'date',
            'audited' => true,
            'mass_update' => false,
            'duplicate_merge' => '1',
            'reportable' => true,
            'importable' => true,
            'options' => 'date_range_search_dom',
            'enable_range_search' => '1',
        ),
        'status' => array(
            'required' => false,
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'audited' => true,
            'default' => 'Active',
            'no_default' => false,
            'massupdate' => true,
            'importable' => false,
            'duplicate_merge' => 'disabled',
            'duplicate_merge_dom_value' => '0',
            'reportable' => true,
            'unified_search' => false,
            'merge_filter' => 'disabled',
            'calculated' => false,
            'len' => 100,
            'size' => '20',
            'options' => 'certificates_status_list',
            'studio' => 'visible',
            'dependency' => false,
        ),
        "candidate" => array(
            'name' => 'candidate',
            'type' => 'link',
            'relationship' => 'candidates_employeecertificates',
            'source' => 'non-db',
            'module' => 'Candidates',
            'bean_name' => 'Candidates',
            'vname' => 'LBL_RELATIONSHIP_CANDIDATE_NAME',
            'id_name' => 'candidate_id',
        ),
        "candidate_name" => array(
            'name' => 'candidate_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_RELATIONSHIP_CANDIDATE_NAME',
            'id_name' => 'candidate_id',
            'link' => 'candidate',
            'module' => 'Candidates',
            'table' => 'candidates',
            'rname' => 'name',
            'audited' => true,
            'importable' => true,
            'required' => false,
            'reportable' => true,
            'massupdate' => false,
            'duplicate_merge' => 'enabled',
            'vt_validation' => 'AEM(ifElse(and(empty($candidate_id),empty($employee_id)),false,true), \'LBL_CANDIDATE_OR_EMPLOYEE_HAVE_TO_BE_SET\')',
        ),
        "candidate_id" => array(
            'name' => 'candidate_id',
            'relationship' => 'candidates_employeecertificates',
            'type' => 'id',
            'vname' => 'LBL_RELATIONSHIP_CANDIDATE_ID',
            'audited' => false,
            'reportable' => true,
        ),
        "certificates" => array(
            'name' => 'certificates',
            'type' => 'link',
            'relationship' => 'certificates_employeecertificates',
            'source' => 'non-db',
            'vname' => 'LBL_CERTIFICATES',
            'id_name' => 'certificate_id',
        ),
        "certificate_name" => array(
            'name' => 'certificate_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_CERTIFICATE_NAME',
            'id_name' => 'certificate_id',
            'link' => 'certificates',
            'module' => 'Certificates',
            'table' => 'certificates',
            'rname' => 'name',
            'importable' => true,
            'reportable' => true,
            'audited' => true,
            'required' => true,
        ),
        "certificate_id" => array(
            'name' => 'certificate_id',
            'relationship' => 'certificates_employeecertificates',
            'type' => 'id',
            'vname' => 'LBL_CERTIFICATE_ID',
            'label' => 'LBL_CERTIFICATE_ID',
            'audited' => true,
            'importable' => true,
            'reportable' => true,
            'rname' => 'id',
            'isnull' => 'true',
            'dbType' => 'id',
        ),
        "employeecertificates_employees" => array(
            'name' => 'employeecertificates_employees',
            'type' => 'link',
            'relationship' => 'employeecertificates_employees',
            'source' => 'non-db',
            'module' => 'Employees',
            'bean_name' => 'Employee',
            'vname' => 'LBL_EMPLOYEES',
            'id_name' => 'employee_id',
        ),
        "employee_name" => array(
            'name' => 'employee_name',
            'type' => 'relate',
            'source' => 'non-db',
            'vname' => 'LBL_EMPLOYEES',
            'id_name' => 'employee_id',
            'link' => 'employeecertificates_employees',
            'module' => 'Employees',
            'table' => 'users',
            'rname' => 'name',
        ),
        "employee_id" => array(
            'name' => 'employee_id',
            'relationship' => 'employeecertificates_employees',
            'type' => 'id',
            'vname' => 'LBL_EMPLOYEES_ID',
        ),
        'attempts_number' => array(
            'name' => 'attempts_number',
            'vname' => 'LBL_ATTEMPTS_NUMBER',
            'label' => 'LBL_ATTEMPTS_NUMBER',
            'type' => 'int',
            'importable' => 'true',
            'reportable' => true,
            'audited' => true,
            'validation' => array (
                'type' => 'range',
                'min' => 0,
                'max' => false,
            ),
            'min' => 0,
            'max' => false,
            'duplicate_merge' => 'enabled',    
            'size' => 30,    
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'disable_num_format' => true,
        ),
        'points_scored' => array(
            'name' => 'points_scored',
            'vname' => 'LBL_POINTS_SCORED',
            'label' => 'LBL_POINTS_SCORED',
            'type' => 'int',
            'importable' => 'true',
            'reportable' => true,
            'audited' => true,
            'validation' => array (
                'type' => 'range',
                'min' => 0,
                'max' => false,
            ),
            'min' => 0,
            'max' => false,
            'duplicate_merge' => 'enabled',    
            'size' => 30,    
            'enable_range_search' => true,
            'options' => 'numeric_range_search_dom',
            'disable_num_format' => true,
        ),
    ),
    'relationships' => array(
        "candidates_employeecertificates" => array(
            'lhs_module' => 'Candidates',
            'lhs_table' => 'candidates',
            'lhs_key' => 'id',
            'rhs_module' => 'EmployeeCertificates',
            'rhs_table' => 'employeecertificates',
            'rhs_key' => 'candidate_id',
            'relationship_type' => 'one-to-many',
        ),
        "certificates_employeecertificates" => array(
            'lhs_module' => 'Certificates',
            'lhs_table' => 'certificates',
            'lhs_key' => 'id',
            'rhs_module' => 'EmployeeCertificates',
            'rhs_table' => 'employeecertificates',
            'rhs_key' => 'certificate_id',
            'relationship_type' => 'one-to-many',
        ),
        "employeecertificates_employees" => array(
            'lhs_module' => 'Employees',
            'lhs_table' => 'users',
            'lhs_key' => 'id',
            'rhs_module' => 'EmployeeCertificates',
            'rhs_table' => 'employeecertificates',
            'rhs_key' => 'employee_id',
            'relationship_type' => 'one-to-many',
        ),
    ),
    'optimistic_locking' => true,
    'unified_search' => true,
);

if (!class_exists('VardefManager')) {
    require_once 'include/SugarObjects/VardefManager.php';
}
VardefManager::createVardef('EmployeeCertificates', 'EmployeeCertificates',
    array('basic', 'assignable', 'security_groups', 'employee_related'));

$dictionary['EmployeeCertificates']['fields']['name']['vt_readonly'] = "true";
$dictionary['EmployeeCertificates']['fields']['name']['vt_calculated'] = 'concat($certificate_name,\' - \',ifElse(empty($employee_id),$candidate_name,$employee_name))';
$dictionary['EmployeeCertificates']['fields']['name']['audited'] = false;
$dictionary['EmployeeCertificates']['fields']['name']['related_fields'] = array(
    'certificate_name',
    'employee_name',
);
$dictionary['EmployeeCertificates']['fields']['employee_name']['required'] = false;
$dictionary['EmployeeCertificates']['fields']['employee_name']['audited'] = true;
$dictionary['EmployeeCertificates']['fields']['employee_name']['vt_validation'] = 'AEM(ifElse(and(empty($candidate_id),empty($employee_id)),false,true), \'LBL_CANDIDATE_OR_EMPLOYEE_HAVE_TO_BE_SET\')';
$dictionary['EmployeeCertificates']['fields']['employee_id']['audited'] = false;
