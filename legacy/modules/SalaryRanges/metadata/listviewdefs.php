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

$module_name = 'SalaryRanges';
$listViewDefs[$module_name] = array(
    'NAME' => array(
        'label' => 'LBL_NAME',
        'width' => '10%',
        'default' => true,
        'type' => 'name',
        'name' => 'name',
    ),
    'POSITION_NAME' => array(
        'label' => 'LBL_POSITION_NAME',
        'width' => '10%',
        'default' => true,
        'name' => 'position_name',
    ),
    'START_DATE' => array(
        'label' => 'LBL_START_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'start_date',
    ),
    'END_DATE' => array(
        'label' => 'LBL_END_DATE',
        'width' => '10%',
        'default' => true,
        'name' => 'end_date',
    ),
    'GROSS_VALUE_FROM' => array(
        'label' => 'LBL_GROSS_VALUE_FROM',
        'width' => '10%',
        'default' => true,
        'name' => 'gross_value_from',
    ),
    'GROSS_VALUE_TO' => array(
        'label' => 'LBL_GROSS_VALUE_TO',
        'width' => '10%',
        'default' => true,
        'name' => 'gross_value_to',
    ),
    'NET_VALUE_FROM' => array(
        'label' => 'LBL_NET_VALUE_FROM',
        'width' => '10%',
        'default' => false,
        'name' => 'net_value_from',
    ),
    'NET_VALUE_TO' => array(
        'label' => 'LBL_NET_VALUE_TO',
        'width' => '10%',
        'default' => false,
        'name' => 'net_value_to',
    ),
    'EMPLOYER_COSTS_FROM' => array(
        'label' => 'LBL_EMPLOYER_COSTS_FROM',
        'width' => '10%',
        'default' => false,
        'name' => 'employer_costs_from',
    ),
    'EMPLOYER_COSTS_TO' => array(
        'label' => 'LBL_EMPLOYER_COSTS_TO',
        'width' => '10%',
        'default' => false,
        'name' => 'employer_costs_to',
    ),
    'DATE_MODIFIED' => array(
        'label' => 'LBL_DATE_MODIFIED',
        'width' => '10%',
        'default' => false,
        'name' => 'DATE_MODIFIED',
    ),
    'MODIFIED_BY_NAME' => array(
        'width' => '5%',
        'label' => 'LBL_MODIFIED',
        'default' => false,
        'name' => 'modified_by_name',
    ),
    'CREATED_BY_NAME' => array(
        'label' => 'LBL_CREATED_USER',
        'width' => '10%',
        'default' => false,
        'name' => 'CREATED_BY_NAME',
    ),
    'DATE_ENTERED' => array(
        'width' => '10%',
        'label' => 'LBL_DATE_ENTERED',
        'default' => false,
    ),
);
