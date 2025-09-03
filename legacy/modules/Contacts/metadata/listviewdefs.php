<?php
if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}
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




$listViewDefs['Contacts'] = array(
    'NAME' => array(
        'width' => '20%',
        'label' => 'LBL_LIST_NAME',
        'link' => true,
        'contextMenu' => array('objectType' => 'sugarPerson',
                               'metaData' => array('contact_id' => '{$ID}',
                                                   'module' => 'Contacts',
                                                   'return_action' => 'ListView',
                                                   'contact_name' => '{$FULL_NAME}',
                                                   'return_module' => 'Contacts',
                                                   'return_action' => 'ListView',)
                              ),
        'orderBy' => 'name',
        'default' => true,
        'related_fields' => array('first_name', 'last_name', 'salutation',),
        ),
    'TITLE' => array(
        'width' => '15%',
        'label' => 'LBL_LIST_TITLE',
        'default' => true),
    'EMAIL1' => array(
        'width' => '15%',
        'label' => 'LBL_LIST_EMAIL_ADDRESS',
        'sortable' => false,
        'link' => true,
        'customCode' => '{$EMAIL1_LINK}',
        'default' => true
        ),
    'PHONE_WORK' => array(
        'width' => '15%',
        'label' => 'LBL_OFFICE_PHONE',
        'default' => true),
    'DEPARTMENT' => array(
        'width' => '10',
        'label' => 'LBL_DEPARTMENT'),
    'DO_NOT_CALL' => array(
        'width' => '10',
        'label' => 'LBL_DO_NOT_CALL'),
    'PHONE_HOME' => array(
        'width' => '10',
        'label' => 'LBL_HOME_PHONE'),
    'PHONE_MOBILE' => array(
        'width' => '10',
        'label' => 'LBL_MOBILE_PHONE'),
    'PHONE_OTHER' => array(
        'width' => '10',
        'label' => 'LBL_OTHER_PHONE'),
    'PHONE_FAX' => array(
        'width' => '10',
        'label' => 'LBL_FAX_PHONE'),
    'EMAIL2' => array(
        'width' => '15',
        'label' => 'LBL_LIST_EMAIL_ADDRESS',
        'sortable' => false,
        'customCode' => '{$EMAIL2_LINK}{$EMAIL2}</a>'),
    'EMAIL_OPT_OUT' => array(
        'width' => '10',
        
        'label' => 'LBL_EMAIL_OPT_OUT'),
    'PRIMARY_ADDRESS_STREET' => array(
        'width' => '10',
        'label' => 'LBL_PRIMARY_ADDRESS_STREET'),
    'PRIMARY_ADDRESS_CITY' => array(
        'width' => '10',
        'label' => 'LBL_PRIMARY_ADDRESS_CITY'),
    'PRIMARY_ADDRESS_STATE' => array(
        'width' => '10',
        'label' => 'LBL_PRIMARY_ADDRESS_STATE'),
    'PRIMARY_ADDRESS_POSTALCODE' => array(
        'width' => '10',
        'label' => 'LBL_PRIMARY_ADDRESS_POSTALCODE'),
    'ALT_ADDRESS_COUNTRY' => array(
        'width' => '10',
        'label' => 'LBL_ALT_ADDRESS_COUNTRY'),
    'ALT_ADDRESS_STREET' => array(
        'width' => '10',
        'label' => 'LBL_ALT_ADDRESS_STREET'),
    'ALT_ADDRESS_CITY' => array(
        'width' => '10',
        'label' => 'LBL_ALT_ADDRESS_CITY'),
    'ALT_ADDRESS_STATE' => array(
        'width' => '10',
        'label' => 'LBL_ALT_ADDRESS_STATE'),
    'ALT_ADDRESS_POSTALCODE' => array(
        'width' => '10',
        'label' => 'LBL_ALT_ADDRESS_POSTALCODE'),
    'ALT_ADDRESS_COUNTRY' => array(
        'width' => '10',
        'label' => 'LBL_ALT_ADDRESS_COUNTRY'),
    'CREATED_BY_NAME' => array(
        'width' => '10',
        'label' => 'LBL_CREATED'),
    'ASSIGNED_USER_NAME' => array(
        'width' => '10',
        'label' => 'LBL_LIST_ASSIGNED_USER',
        'module' => 'Employees',
        'id' => 'ASSIGNED_USER_ID',
        'default' => true),
    'MODIFIED_BY_NAME' => array(
        'width' => '10',
        'label' => 'LBL_MODIFIED'),
    'SYNC_CONTACT' => array(
        'type' => 'bool',
        'label' => 'LBL_SYNC_CONTACT',
        'width' => '10%',
        'default' => false,
        'sortable' => false,
        ),
    'DATE_ENTERED' => array(
        'width' => '10',
        'label' => 'LBL_DATE_ENTERED',
        'default' => true)
);
