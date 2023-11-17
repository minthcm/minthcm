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

$dashletData['CandidatesDashlet']['searchFields'] = array(
    'name' => array(
        'default' => '',
    ),
    'recr_contact_agree' => array(
        'default' => '',
    ),
    'last_time_contact' => array(
        'default' => '',
    ),
    'date_planned_contact' => array(
        'default' => '',
    ),
    'date_entered' => array(
        'default' => '',
    ),
    'date_modified' => array(
        'default' => '',
    ),
    'assigned_user_name' => array(
        'default' => '',
    ),
    'last_name' => array(
        'default' => '',
    ),
    'first_name' => array(
        'default' => '',
    ),
    'phone_mobile' => array(
        'default' => '',
    ),
    'primary_address_street' => array(
        'default' => '',
    ),
    'relocation' => array(
        'default' => '',
    ),
    'potential' => array(
        'default' => '',
    ),
    'primary_address_country' => array(
        'default' => '',
    ),
    'primary_address_postalcode' => array(
        'default' => '',
    ),
    'primary_address_state' => array(
        'default' => '',
    ),
    'primary_address_city' => array(
        'default' => '',
    ),
    'email1' => array(
        'default' => '',
    ),
);
$dashletData['CandidatesDashlet']['columns'] = array(
    'name' => array(
        'width' => '40%',
        'label' => 'LBL_LIST_NAME',
        'link' => true,
        'default' => true,
        'name' => 'name',
    ),
    'recr_contact_agree' => array(
        'width' => '5%',
        'label' => 'LBL_RECR_CONTACT_AGREE_SHORT',
        'default' => false,
        'name' => 'recr_contact_agree',
    ),
    'last_time_contact' => array(
        'width' => '15%',
        'label' => 'LBL_LAST_TIME_CONTACT',
        'default' => false,
        'name' => 'last_time_contact',
    ),
    'date_planned_contact' => array(
        'width' => '15%',
        'label' => 'LBL_DATE_PLANNED_CONTACT',
        'default' => false,
        'name' => 'date_planned_contact',
    ),
    'date_entered' => array(
        'width' => '15%',
        'label' => 'LBL_DATE_ENTERED',
        'default' => true,
        'name' => 'date_entered',
    ),
    'primary_address_city' => array(
        'type' => 'varchar',
        'label' => 'LBL_PRIMARY_ADDRESS_CITY',
        'width' => '10%',
        'default' => true,
        'name' => 'primary_address_city',
    ),
    'date_modified' => array(
        'width' => '15%',
        'label' => 'LBL_DATE_MODIFIED',
        'name' => 'date_modified',
        'default' => false,
    ),
    'created_by' => array(
        'width' => '8%',
        'label' => 'LBL_CREATED',
        'name' => 'created_by',
        'default' => false,
    ),
    'assigned_user_name' => array(
        'width' => '8%',
        'label' => 'LBL_LIST_ASSIGNED_USER',
        'name' => 'assigned_user_name',
        'default' => false,
    ),
    'employee_name' => array(
        'label' => 'LBL_EMPLOYEE_NAME',
        'name' => 'employee_name',
        'width' => '8%',
    ),
    'phone_mobile' => array(
        'type' => 'phone',
        'label' => 'LBL_MOBILE_PHONE',
        'width' => '10%',
        'default' => false,
        'name' => 'phone_mobile',
    ),
    'last_name' => array(
        'type' => 'varchar',
        'label' => 'LBL_LAST_NAME',
        'width' => '10%',
        'default' => false,
        'name' => 'last_name',
    ),
    'email1' => array(
        'type' => 'varchar',
        'studio' => array(
            'editview' => true,
            'editField' => true,
            'searchview' => false,
            'popupsearch' => false,
        ),
        'label' => 'LBL_EMAIL_ADDRESS',
        'width' => '10%',
        'default' => false,
        'name' => 'email1',
    ),
    'primary_address_country' => array(
        'type' => 'varchar',
        'default' => false,
        'label' => 'LBL_PRIMARY_ADDRESS_COUNTRY',
        'width' => '10%',
        'name' => 'primary_address_country',
    ),
    'primary_address_postalcode' => array(
        'type' => 'varchar',
        'label' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
        'width' => '10%',
        'default' => false,
        'name' => 'primary_address_postalcode',
    ),
    'primary_address_state' => array(
        'type' => 'varchar',
        'label' => 'LBL_PRIMARY_ADDRESS_STATE',
        'width' => '10%',
        'default' => false,
        'name' => 'primary_address_state',
    ),
    'primary_address_street' => array(
        'type' => 'text',
        'label' => 'LBL_PRIMARY_ADDRESS_STREET',
        'sortable' => false,
        'width' => '10%',
        'default' => false,
        'name' => 'primary_address_street',
    ),
    'first_name' => array(
        'type' => 'varchar',
        'label' => 'LBL_FIRST_NAME',
        'width' => '10%',
        'default' => false,
        'name' => 'first_name',
    ),
    'potential' => array(
        'type' => 'enum',
        'default' => false,
        'studio' => 'visible',
        'label' => 'LBL_POTENTIAL',
        'width' => '10%',
        'name' => 'potential',
    ),
    'relocation' => array(
        'label' => 'LBL_RELOCATION',
        'type' => 'bool',
        'width' => '10%',
        'default' => false,
    ),
    'birthdate' => array(
        'label' => 'LBL_BIRTHDATE',
        'type' => 'date',
        'width' => '10%',
        'default' => false,
        'name' => 'birthdate',
    ),
);
