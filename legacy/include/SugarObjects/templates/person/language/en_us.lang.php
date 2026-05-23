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
 * Copyright (C) 2018-2024 MintHCM
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

$mod_strings = array(
    'LBL_SALUTATION' => 'Salutation',
    'LBL_NAME' => 'Name',
    'LBL_FIRST_NAME' => 'First Name',
    'LBL_LAST_NAME' => 'Last Name',
    'LBL_TITLE' => 'Title',
    'LBL_DEPARTMENT' => 'Department',
    'LBL_DO_NOT_CALL' => 'Do Not Call',
    'LBL_HOME_PHONE' => 'Home Phone',
    'LBL_MOBILE_PHONE' => 'Mobile Phone',
    'LBL_OFFICE_PHONE' => 'Office Phone',
    'LBL_OTHER_PHONE' => 'Other Phone',
    'LBL_FAX_PHONE' => 'Fax',
    'LBL_EMAIL_ADDRESS' => 'Email Address(es)',
    'LBL_PRIMARY_ADDRESS' => 'Primary Address',
    'LBL_PRIMARY_ADDRESS_STREET' => 'Primary Address',
    'LBL_PRIMARY_ADDRESS_STREET_2' => 'Primary Address Street 2:',
    'LBL_PRIMARY_ADDRESS_STREET_3' => 'Primary Address Street 3:',
    'LBL_PRIMARY_ADDRESS_CITY' => 'Primary City',
    'LBL_PRIMARY_ADDRESS_STATE' => 'Primary State',
    'LBL_PRIMARY_ADDRESS_POSTALCODE' => 'Primary Postal Code',
    'LBL_PRIMARY_ADDRESS_COUNTRY' => 'Primary Address Country:',
    'LBL_ALT_ADDRESS' => 'Alternate Address',
    'LBL_ALT_ADDRESS_STREET' => 'Alternate Address',
    'LBL_ALT_ADDRESS_STREET_2' => 'Alternate Address Street 2:',
    'LBL_ALT_ADDRESS_STREET_3' => 'Alternate Address Street 3:',
    'LBL_ALT_ADDRESS_CITY' => 'Alternate City',
    'LBL_ALT_ADDRESS_STATE' => 'Alternate State',
    'LBL_ALT_ADDRESS_POSTALCODE' => 'Alternate Postal Code',
    'LBL_ALT_ADDRESS_COUNTRY' => 'Alternate Country',
    'LBL_PRIMARY_STREET' => 'Address',
    'LBL_ALT_STREET' => 'Other Address',
    'LBL_STREET' => 'Other Address',
    'LBL_CITY' => 'City',
    'LBL_STATE' => 'State',
    'LBL_POSTAL_CODE' => 'Postal Code',
    'LBL_COUNTRY' => 'Country',
    'LBL_CONTACT_INFORMATION' => 'Contact Information',
    'LBL_ADDRESS_INFORMATION' => 'Address(es)',
    'LBL_ASSIGNED_TO_NAME' => 'User',
    'LBL_OTHER_EMAIL_ADDRESS' => 'Other Email:',
    'LBL_ASSISTANT' => 'Assistant',
    'LBL_ASSISTANT_PHONE' => 'Assistant Phone',
    'LBL_WORK_PHONE' => 'Work Phone',
    'LNK_IMPORT_VCARD' => 'Create From vCard',
    'LBL_ANY_EMAIL' => 'Any Email',
    'LBL_EMAIL_NON_PRIMARY' => 'Non Primary E-mails',
    'LBL_PHOTO' => 'Photo',
    'LBL_EDIT_BUTTON' => 'Edit',
    'LBL_REMOVE' => 'Remove',

    //Lawful Basis labels
    'LBL_LAWFUL_BASIS' => 'Lawful Basis',
    'LBL_DATE_REVIEWED' => 'Lawful Basis Date Reviewed',
    'LBL_LAWFUL_BASIS_SOURCE' => 'Lawful Basis Source',
    'LBL_CONSENT' => 'Consent',
    //End Lawful Basis labels
    'LBL_NAME_COMMENT' => 'Full name',
    'LBL_SALUTATION_COMMENT' => 'Salutation used to address the contact, for example Mister or Miss',
    'LBL_FIRST_NAME_COMMENT' => 'First name of the contact',
    'LBL_LAST_NAME_COMMENT' => 'Last name of the contact',
    'LBL_TITLE_COMMENT' => 'Job title or position of the contact',
    'LBL_PHOTO_COMMENT' => 'Profile photo of the contact',
    'LBL_DEPARTMENT_COMMENT' => 'Department where the contact works',
    'LBL_DO_NOT_CALL_COMMENT' => 'Indicates whether the contact should not be called',
    'LBL_PHONE_HOME_COMMENT' => 'Home phone number of the contact',
    'LBL_PHONE_MOBILE_COMMENT' => 'Mobile phone number of the contact',
    'LBL_PHONE_WORK_COMMENT' => 'Work phone number of the contact',
    'LBL_PHONE_OTHER_COMMENT' => 'Additional phone number of the contact',
    'LBL_PHONE_FAX_COMMENT' => 'Fax number of the contact',
    'LBL_EMAIL_ADDRESS_COMMENT' => 'Email address widget of the contact',
    'LBL_LAWFUL_BASIS_COMMENT' => 'Legal basis for processing the contact\'s personal data',
    'LBL_DATE_REVIEWED_COMMENT' => 'Date when the legal basis was last reviewed',
    'LBL_LAWFUL_BASIS_SOURCE_COMMENT' => 'Source of the legal basis for data processing',
    'LBL_PRIMARY_ADDRESS_STREET_COMMENT' => 'Street address of the primary residence',
    'LBL_PRIMARY_ADDRESS_CITY_COMMENT' => 'City of the primary residence',
    'LBL_PRIMARY_ADDRESS_STATE_COMMENT' => 'State or region of the primary residence',
    'LBL_PRIMARY_ADDRESS_POSTALCODE_COMMENT' => 'Postal code of the primary residence',
    'LBL_PRIMARY_ADDRESS_COUNTRY_COMMENT' => 'Country of the primary residence',
    'LBL_ALT_ADDRESS_STREET_COMMENT' => 'Street address of the alternate residence',
    'LBL_ALT_ADDRESS_CITY_COMMENT' => 'City of the alternate residence',
    'LBL_ALT_ADDRESS_STATE_COMMENT' => 'State or region of the alternate residence',
    'LBL_ALT_ADDRESS_POSTALCODE_COMMENT' => 'Postal code of the alternate residence',
    'LBL_ALT_ADDRESS_COUNTRY_COMMENT' => 'Country of the alternate residence',
    'LBL_ASSISTANT_COMMENT' => 'Name of the contact\'s assistant',
    'LBL_ASSISTANT_PHONE_COMMENT' => 'Phone number of the contact\'s assistant',
);


