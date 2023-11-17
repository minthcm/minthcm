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

$module_name = 'Candidates';
$searchdefs[$module_name] = array(
    'layout' => array(
        'basic_search' => array(
            0 => array(
                'name' => 'search_name',
                'label' => 'LBL_NAME',
                'type' => 'name',
            ),
            1 => array(
                'name' => 'current_user_only',
                'label' => 'LBL_CURRENT_USER_FILTER',
                'type' => 'bool',
            ),
            2 => array(
                'name' => 'favorites_only',
                'label' => 'LBL_FAVORITES_FILTER',
                'type' => 'bool',
            ),
        ),
        'advanced_search' => array(
            'search_name' => array(
                'label' => 'LBL_NAME',
                'type' => 'name',
                'width' => '10%',
                'default' => true,
                'name' => 'search_name',
            ),
            'first_name' => array(
                'name' => 'first_name',
                'default' => true,
                'width' => '10%',
            ),
            'last_name' => array(
                'name' => 'last_name',
                'default' => true,
                'width' => '10%',
            ),
            'recr_contact_agree' => array(
                'type' => 'bool',
                'label' => 'LBL_RECR_CONTACT_AGREE',
                'sortable' => false,
                'width' => '50%',
                'default' => true,
                'name' => 'recr_contact_agree',
            ),
            'relocation' => array(
                'name' => 'relocation',
                'default' => true,
                'width' => '10%',
            ),
            'collaboration' => array(
                'name' => 'collaboration',
                'default' => true,
                'width' => '10%',
            ),
            'last_time_contact' => array(
                'name' => 'last_time_contact',
                'type' => 'date',
                'default' => true,
                'width' => '10%',
            ),
            'date_planned_contact' => array(
                'name' => 'date_planned_contact',
                'type' => 'date',
                'default' => true,
                'width' => '10%',
            ),
            'potential' => array(
                'name' => 'potential',
                'default' => true,
                'width' => '10%',
            ),
            'phone_mobile' => array(
                'type' => 'phone',
                'label' => 'LBL_MOBILE_PHONE',
                'width' => '10%',
                'default' => true,
                'name' => 'phone_mobile',
            ),
            'primary_address_city' => array(
                'type' => 'varchar',
                'label' => 'LBL_PRIMARY_ADDRESS_CITY',
                'width' => '10%',
                'default' => true,
                'name' => 'primary_address_city',
            ),
            'email' => array(
                'name' => 'email',
                'label' => 'LBL_ANY_EMAIL',
                'type' => 'name',
                'default' => true,
                'width' => '10%',
            ),
            'primary_address_postalcode' => array(
                'type' => 'varchar',
                'label' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
                'width' => '10%',
                'default' => true,
                'name' => 'primary_address_postalcode',
            ),
            'primary_address_state' => array(
                'type' => 'varchar',
                'label' => 'LBL_PRIMARY_ADDRESS_STATE',
                'width' => '10%',
                'default' => true,
                'name' => 'primary_address_state',
            ),
            'primary_address_country' => array(
                'type' => 'varchar',
                'default' => true,
                'label' => 'LBL_PRIMARY_ADDRESS_COUNTRY',
                'width' => '10%',
                'name' => 'primary_address_country',
            ),
            'primary_address_street' => array(
                'type' => 'text',
                'label' => 'LBL_PRIMARY_STREET',
                'sortable' => false,
                'width' => '10%',
                'default' => true,
                'name' => 'primary_address_street',
            ),
            'assigned_user_id' => array(
                'name' => 'assigned_user_id',
                'label' => 'LBL_ASSIGNED_TO',
                'type' => 'enum',
                'function' => array(
                    'name' => 'get_user_array',
                    'params' => array(false),
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
        ),
        'templateMeta' => array(
            'maxColumns' => '3',
            'maxColumnsBasic' => '4',
            'widths' => array(
                'label' => '10',
                'field' => '30',
            ),
        ),
    ),
);
