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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'Candidatures';
$listViewDefs[$module_name] = array(
    'NAME' => array(
        'name' => 'name',
        'label' => 'LBL_NAME',
        'default' => true,
        'enabled' => true,
        'link' => true,
    ),
    'PARENT_NAME' => array(
        'name' => 'parent_name',
        'label' => 'LBL_CANDIDATES_TITLE',
        'enabled' => true,
        'module' => 'Candidates',
        'id' => 'PARENT_ID',
        'link' => true,
        'sortable' => false,
        'default' => true,
    ),
    'status' => array(
        'name' => 'status',
        'label' => 'LBL_STATUS',
        'enabled' => true,
        'default' => true,
    ),
    'to_decision' => array(
        'name' => 'to_decision',
        'label' => 'LBL_TO_DECISION',
        'enabled' => true,
        'default' => true,
    ),
    'net_amount' => array(
        'name' => 'net_amount',
        'label' => 'LBL_NET_AMOUNT',
        'enabled' => true,
        'related_fields' => array(
            'currency_id',
        ),
        'currency_format' => true,
        'default' => true,
    ),
    'gross_amount' => array(
        'name' => 'gross_amount',
        'label' => 'LBL_GROSS_AMOUNT',
        'enabled' => true,
        'related_fields' => array(
            'currency_id',
        ),
        'currency_format' => true,
        'default' => true,
    ),
    'dg_amount' => array(
        'name' => 'dg_amount',
        'label' => 'LBL_DG_AMOUNT',
        'enabled' => true,
        'related_fields' => array(
            'currency_id',
        ),
        'currency_format' => true,
        'default' => true,
    ),
    'scoring' => array(
        'name' => 'scoring',
        'label' => 'SCORING',
        'enabled' => true,
        'default' => true,
    ),
    'assigned_user_name' => array(
        'name' => 'assigned_user_name',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'default' => true,
        'enabled' => true,
        'link' => true,
    ),
    'EMPLOYEE_NAME' => array(
        'width' => '9%',
        'label' => 'LBL_EMPLOYEE_NAME',
        'module' => 'Employees',
        'id' => 'EMPLOYEE_ID',
        'default' => true,
    ),
    'DATE_MODIFIED' => array(
        'label' => 'LBL_DATE_MODIFIED',
        'enabled' => true,
        'default' => true,
        'name' => 'date_modified',
        'readonly' => true,
    ),
    'SOURCE' => array(
        'name' => 'source',
        'label' => 'LBL_SOURCE',
        'enabled' => true,
        'default' => false,
    ),
    'START_DATE' => array(
        'name' => 'start_date',
        'label' => 'LBL_START_DATE',
        'enabled' => true,
        'default' => false,
    ),
    'NOTICE' => array(
        'name' => 'notice',
        'label' => 'LBL_NOTICE',
        'enabled' => true,
        'sortable' => false,
        'default' => false,
    ),
    'NOTICE_FINAL_EXPECTATIONS' => array(
        'name' => 'notice_final_expectations',
        'label' => 'LBL_NOTICE_FINAL_EXPECTATIONS',
        'enabled' => true,
        'sortable' => false,
        'default' => false,
    ),
    'FINAL_EMPLOYMENT_FORM' => array(
        'name' => 'final_employment_form',
        'label' => 'LBL_FINAL_EMPLOYMENT_FORM',
        'enabled' => true,
        'default' => false,
    ),
    'EMPLOYMENT_FORM' => array(
        'name' => 'employment_form',
        'label' => 'LBL_EMPLOYMENT_FORM',
        'enabled' => true,
        'default' => false,
    ),
    'RECRUITMENT_NAME' => array(
        'name' => 'recruitment_name',
        'label' => 'LBL_CANDIDATURES_RECRUITMENTS_FROM_RECRUITMENTS_TITLE',
        'enabled' => true,
        'id' => 'RECRUITMENT_ID',
        'link' => true,
        'sortable' => false,
        'default' => false,
        'module' => 'Recruitments',
    ),
    'RECRUITMENT_END_NAME' => array(
        'name' => 'recruitment_end_name',
        'label' => 'LBL_CANDIDATURES_RECRUITMENTS_END_FROM_RECRUITMENTS_TITLE',
        'enabled' => true,
        'id' => 'RECRUITMENT_END_ID',
        'link' => true,
        'sortable' => false,
        'default' => false,
        'module' => 'Recruitments',
    ),
    'SALARY_NET' => array(
        'name' => 'salary_net',
        'label' => 'LBL_SALARY_NET',
        'enabled' => true,
        'related_fields' => array(
            'currency_id',
        ),
        'currency_format' => true,
        'default' => false,
    ),
    'TASK_GRADE' => array(
        'name' => 'task_grade',
        'label' => 'LBL_TASK_GRADE',
        'enabled' => true,
        'default' => false,
    ),
    'REASON_FOR_REJECTION' => array(
        'name' => 'reason_for_rejection',
        'label' => 'LBL_REASON_FOR_REJECTION',
        'enabled' => true,
        'default' => false,
    ),
);
