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

$popupMeta = array(
    'moduleMain' => 'EmployeeCertificates',
    'varName' => 'EmployeeCertificates',
    'orderBy' => 'employeecertificates.name',
    'whereClauses' => array(
        'name' => 'employeecertificates.name',
        'start_date' => 'employeecertificates.start_date',
        'end_date' => 'employeecertificates.end_date',
        'status' => 'cemployeeertificates.status',
        'candidate_name' => 'candidates.candidate_name',
        'certificate_name' => 'certificates.certificate_name',
        'attempts_number' => 'certificates.attempts_number',
        'points_scored' => 'certificates.points_scored',
    ),
    'searchInputs' => array(
        'name',
        'start_date',
        'end_date',
        'status',
        'candidate_name',
        'certificate_name',
        'attempts_number',
        'points_scored',
    ),
    'searchdefs' => array(
        'name' => array(
            'name' => 'name',
        ),
        'start_date' => array(
            'name' => 'start_date',
        ),
        'end_date' => array(
            'name' => 'end_date',
        ),
        'attempts_number' => array(
            'name' => 'attempts_number',
        ),
        'points_scored' => array(
            'name' => 'points_scored',
        ),
        'status' => array(
            'name' => 'status',
        ),
        'assigned_user_id' => array(
            'name' => 'assigned_user_id',
            'label' => 'LBL_ASSIGNED_TO',
            'type' => 'enum',
            'function' => array(
                'name' => 'get_user_array',
                'params' => array(
                    false,
                ),
            ),
            'width' => '10%',
        ),
        'employee_id' => array(
            'name' => 'employee_id',
            'label' => 'LBL_EMPLOYEE',
            'type' => 'enum',
            'function' => array(
                'name' => 'get_user_array',
                'params' => array(
                    false,
                ),
            ),
            'default' => true,
            'width' => '10%',
        ),
        'candidate_name' => array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_RELATIONSHIP_CANDIDATE_NAME',
            'id' => 'CANDIDATE_ID',
            'width' => '10%',
            'name' => 'candidate_name',
        ),
        'certificate_name' => array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_RELATIONSHIP_CERTIFICATE_NAME',
            'id' => 'CERTIFICATE_ID',
            'width' => '10%',
            'name' => 'certificate_name',
        ),
    ),
    'listviewdefs' => array(
        'NAME' => array(
            'label' => 'LBL_NAME',
            'link' => true,
            'default' => true,
        ),
        'start_date' => array(
            'default' => true,
            'label' => 'LBL_START_DATE',
            'name' => 'start_date',
        ),
        'end_date' => array(
            'default' => true,
            'label' => 'LBL_END_DATE',
            'name' => 'end_date',
        ),
        'status' => array(
            'default' => true,
            'label' => 'LBL_STATUS',
            'name' => 'status',
        ),
        'ASSIGNED_USER_NAME' => array(
            'width' => '9%',
            'label' => 'LBL_ASSIGNED_TO_NAME',
            'module' => 'Employees',
            'id' => 'ASSIGNED_USER_ID',
            'default' => true,
            'name' => 'assigned_user_name',
        ),
        'EMPLOYEE_NAME' => array(
            'width' => '9%',
            'label' => 'LBL_EMPLOYEE',
            'module' => 'Employees',
            'default' => true,
            'name' => 'employee_name',
        ),
        'candidate_name' => array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_RELATIONSHIP_CANDIDATE_NAME',
            'id' => 'CANDIDATE_ID',
            'width' => '10%',
            'default' => true,
            'name' => 'candidate_name',
        ),
        'CERTIFICATE_NAME' => array(
            'type' => 'relate',
            'link' => true,
            'label' => 'LBL_RELATIONSHIP_CERTIFICATE_NAME',
            'id' => 'CERTIFICATE_ID',
            'width' => '10%',
            'default' => true,
            'name' => 'certificate_name',
        ),
    ),
);
