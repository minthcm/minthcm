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
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/06_Customer_Center/10_Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

$module_name = 'Recruitments';
$listViewDefs[$module_name] = array(
    'NAME' => array(
        'name' => 'name',
        'label' => 'LBL_NAME',
        'default' => true,
        'enabled' => true,
        'link' => true,
        'width' => '10%',
    ),
    'POSITION_NAME' => array(
        'name' => 'position_name',
        'label' => 'LBL_RECRUITMENTS_POSITIONS_FROM_POSITIONS_TITLE',
        'enabled' => true,
        'id' => 'position_id',
        'link' => true,
        'sortable' => false,
        'default' => true,
        'width' => '10%',
    ),
    'PROJECT_STATUS' => array(
        'name' => 'project_status',
        'label' => 'LBL_PROJECT_STATUS',
        'enabled' => true,
        'default' => true,
        'width' => '10%',
    ),
    'START_DATE' => array(
        'name' => 'start_date',
        'label' => 'LBL_START_DATE',
        'enabled' => true,
        'default' => true,
        'width' => '10%',
    ),
    'END_DATE' => array(
        'name' => 'end_date',
        'label' => 'LBL_END_DATE',
        'enabled' => true,
        'default' => true,
        'width' => '10%',
    ),
    'ASSIGNED_USER_NAME' => array(
        'name' => 'assigned_user_name',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'default' => true,
        'enabled' => true,
        'link' => true,
        'width' => '10%',
    ),
    'START_WORK_DATE' => array(
        'name' => 'start_work_date',
        'label' => 'LBL_START_WORK_DATE',
        'enabled' => true,
        'default' => false,
        'width' => '10%',
    ),
    'DATE_ENTERED' => array(
        'type' => 'datetime',
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => false,
    ),
    'SALARY_FROM' => array(
        'name' => 'salary_from',
        'label' => 'LBL_SALARY_FROM',
        'related_fields' => array(
            0 => 'currency_id',
        ),
        'currency_field' => 'currency_id',
        'enabled' => true,
        'default' => false,
        'width' => '10%',
    ),
    'SALARY_TO' => array(
        'name' => 'salary_to',
        'label' => 'LBL_SALARY_TO',
        'related_fields' => array(
            0 => 'currency_id',
        ),
        'currency_field' => 'currency_id',
        'enabled' => true,
        'default' => false,
        'width' => '10%',
    ),
    'RECRUITMENT_CHANNELS' => array(
        'type' => 'multienum',
        'default' => false,
        'studio' => 'visible',
        'label' => 'LBL_RECRUITMENT_CHANNELS',
        'width' => '10%',
    ),
    'CREATED_BY_NAME' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_CREATED',
        'id' => 'CREATED_BY',
        'width' => '10%',
        'default' => false,
    ),
    'VACANCY' => array(
        'name' => 'vacancy',
        'label' => 'LBL_VACANCY',
        'enabled' => true,
        'default' => false,
        'width' => '10%',
    ),
    'EMPLOYEES_NUMBER' => array(
        'name' => 'employees_number',
        'label' => 'LBL_EMPLOYEES_NUMBER',
        'enabled' => true,
        'default' => false,
        'width' => '10%',
    ),
    'RECRUITMENT_TYPE' => array(
        'type' => 'enum',
        'default' => false,
        'studio' => 'visible',
        'label' => 'LBL_RECRUITMENT_TYPE',
        'width' => '10%',
    ),
    'DATE_MODIFIED' => array(
        'label' => 'LBL_DATE_MODIFIED',
        'enabled' => true,
        'default' => false,
        'name' => 'date_modified',
        'readonly' => true,
        'width' => '10%',
    ),
    'MODIFIED_BY_NAME' => array(
        'type' => 'relate',
        'link' => true,
        'label' => 'LBL_MODIFIED_NAME',
        'id' => 'MODIFIED_USER_ID',
        'width' => '10%',
        'default' => false,
    ),
);
