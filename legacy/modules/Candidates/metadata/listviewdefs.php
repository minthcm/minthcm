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
$listViewDefs[$module_name] = array(
    'NAME' => array(
        'label' => 'LBL_NAME',
        'link' => true,
        'orderBy' => 'last_name',
        'default' => true,
        'related_fields' => array(
            0 => 'first_name',
            1 => 'last_name',
            2 => 'salutation',
        ),
        'width' => '10%',
    ),
    'EMAIL1' => array(
        'width' => '15%',
        'label' => 'LBL_EMAIL_ADDRESS',
        'sortable' => false,
        'link' => true,
        'customCode' => '{$EMAIL1_LINK}',
        'default' => true,
    ),
    'PHONE_MOBILE' => array(
        'label' => 'LBL_MOBILE_PHONE',
        'width' => '10%',
        'default' => false,
    ),
    'RECR_CONTACT_AGREE' => array(
        'width' => '5%',
        'label' => 'LBL_RECR_CONTACT_AGREE_SHORT',
        'link' => false,
        'default' => false,

    ),
    'POTENTIAL' => array(
        'type' => 'enum',
        'default' => true,
        'studio' => 'visible',
        'label' => 'LBL_POTENTIAL',
        'width' => '10%',
    ),
    'BIRTHDATE' => array(
        'label' => 'LBL_BIRTHDATE',
        'type' => 'date',
        'width' => '10%',
        'default' => false,
    ),
    'RELOCATION' => array(
        'label' => 'LBL_RELOCATION',
        'type' => 'bool',
        'width' => '10%',
        'default' => false,
    ),
    'LBL_LAST_TIME_CONTACT' => array(
        'label' => 'LBL_LAST_TIME_CONTACT',
        'width' => '10%',
        'default' => false,
    ),
    'LBL_DATE_PLANNED_CONTACT' => array(
        'label' => 'LBL_DATE_PLANNED_CONTACT',
        'width' => '10%',
        'default' => false,
    ),
    'DATE_ENTERED' => array(
        'label' => 'LBL_DATE_ENTERED',
        'width' => '10%',
        'default' => false,
    ),
    'ASSIGNED_USER_NAME' => array(
        'link' => true,
        'type' => 'relate',
        'label' => 'LBL_ASSIGNED_TO_NAME',
        'id' => 'ASSIGNED_USER_ID',
        'width' => '10%',
        'default' => true,
    ),
    'EMPLOYEE_NAME' => array(
        'width' => '9%',
        'label' => 'LBL_EMPLOYEE_NAME',
        'module' => 'Employees',
        'id' => 'EMPLOYEE_ID',
        'default' => true,
    ),
    'SKYPE' => array(
        'label' => 'LBL_SKYPE',
        'type' => 'varchar',
        'width' => '10%',
        'default' => false,
    ),
    'GOLDENLINE' => array(
        'label' => 'LBL_GOLDENLINE',
        'type' => 'url',
        'width' => '10%',
        'default' => false,
    ),
    'LAST_NAME' => array(
        'type' => 'varchar',
        'label' => 'LBL_LAST_NAME',
        'width' => '10%',
        'default' => false,
    ),
    'LINKEDIN' => array(
        'label' => 'LBL_LINKEDIN_ACCOUNT',
        'type' => 'url',
        'width' => '10%',
        'default' => false,
    ),
    'FIRST_NAME' => array(
        'type' => 'varchar',
        'label' => 'LBL_FIRST_NAME',
        'width' => '10%',
        'default' => false,
    ),
    'CREATED_BY_NAME' => array(
        'label' => 'LBL_CREATED',
        'width' => '10%',
        'default' => false,
    ),
    'PRIMARY_ADDRESS_STREET' => array(
        'type' => 'text',
        'label' => 'LBL_PRIMARY_STREET',
        'sortable' => false,
        'width' => '10%',
        'default' => false,
    ),
    'FACEBOOK' => array(
        'name' => 'FACEBOOK',
        'label' => 'LBL_FACEBOOK',
        'default' => false,
        'type' => 'url',
        'width' => '10%',
    ),
    'PRIMARY_ADDRESS_CITY' => array(
        'type' => 'varchar',
        'label' => 'LBL_PRIMARY_ADDRESS_CITY',
        'width' => '10%',
        'default' => true,
    ),
    'PRIMARY_ADDRESS_STATE' => array(
        'type' => 'varchar',
        'label' => 'LBL_PRIMARY_ADDRESS_STATE',
        'width' => '10%',
        'default' => false,
    ),
    'PRIMARY_ADDRESS_COUNTRY' => array(
        'type' => 'varchar',
        'default' => false,
        'label' => 'LBL_PRIMARY_ADDRESS_COUNTRY',
        'width' => '10%',
    ),
    'PRIMARY_ADDRESS_POSTALCODE' => array(
        'type' => 'varchar',
        'label' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
        'width' => '10%',
        'default' => false,
    ),
);
