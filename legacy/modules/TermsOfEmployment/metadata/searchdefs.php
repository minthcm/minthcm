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
$module_name = 'TermsOfEmployment';
$searchdefs[$module_name] = array(
    'templateMeta' => array(
        'maxColumns' => '3',
        'maxColumnsBasic' => '4',
        'widths' => array('label' => '10', 'field' => '30'),
    ),
    'layout' => array(
        'basic_search' => array(
            'name',
            array('name' => 'current_user_only', 'label' => 'LBL_CURRENT_USER_FILTER', 'type' => 'bool'),
        ),
        'advanced_search' => array(
            'name' => array(
                'name' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            'contract_name' => array(
                'name' => 'contract_name',
                'label' => 'LBL_CONTRACT_NAME',
                'default' => true,
                'width' => '10%',
            ),
            'date_of_signing' => array(
                'type' => 'date',
                'label' => 'LBL_DATE_OF_SIGNING',
                'width' => '10%',
                'default' => true,
                'name' => 'date_of_signing',
            ),
            'term_starting_date' => array(
                'type' => 'date',
                'label' => 'LBL_TERM_STARTING_DATE',
                'width' => '10%',
                'default' => true,
                'name' => 'term_starting_date',
            ),
            'term_ending_date' => array(
                'type' => 'date',
                'label' => 'LBL_TERM_ENDING_DATE',
                'width' => '10%',
                'default' => true,
                'name' => 'term_ending_date',
            ),
            'gross' => array(
                'type' => 'int',
                'label' => 'LBL_GROSS',
                'width' => '10%',
                'default' => true,
                'name' => 'gross',
            ),
            'net' => array(
                'type' => 'int',
                'label' => 'LBL_NET',
                'width' => '10%',
                'default' => true,
                'name' => 'net',
            ),
            'employer_cost' => array(
                'type' => 'int',
                'label' => 'LBL_EMPLOYER_COST',
                'width' => '10%',
                'default' => true,
                'name' => 'employer_cost',
            ),
            'contracted_employee' => array(
                'name' => 'contracted_employee',
                'label' => 'LBL_CONTRACTED_EMPLOYEE',
                'type' => 'enum',
                'function' => array(
                    'name' => 'get_user_array',
                    'params' => array(
                        false,
                        '',
                    ),
                ),
                'default' => true,
                'width' => '10%',
            ),
            'employee_id' => array(
                'name' => 'employee_id',
                'label' => 'LBL_EMPLOYEE_NAME',
                'type' => 'enum',
                'function' => array(
                    'name' => 'get_user_array',
                    'params' => array(
                        false,
                        '',
                    ),
                ),
                'default' => true,
                'width' => '10%',
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
                'default' => true,
                'width' => '10%',
            ),
        ),
    ),
);
