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

$vardefs = array(
    'fields' => array(
        'salutation' => array(
            'name' => 'salutation',
            'vname' => 'LBL_SALUTATION',
            'type' => 'enum',
            'options' => 'salutation_dom',
            'massupdate' => false,
            'len' => '255',
            'comment' => 'Salutation used to address the contact, for example Mister or Miss'
        ),
        'first_name' => array(
            'name' => 'first_name',
            'vname' => 'LBL_FIRST_NAME',
            'type' => 'varchar',
            'len' => '100',
            'unified_search' => true,
            'full_text_search' => array('boost' => 3),
            'comment' => 'First name of the contact',
            'merge_filter' => 'selected',

        ),
        'last_name' => array(
            'name' => 'last_name',
            'vname' => 'LBL_LAST_NAME',
            'type' => 'varchar',
            'len' => '100',
            'unified_search' => true,
            'full_text_search' => array('boost' => 3),
            'comment' => 'Last name of the contact',
            'merge_filter' => 'selected',
            'required' => true,
            'importable' => 'required',
        ),
        'name' => array(
            'name' => 'name',
            'rname' => 'name',
            'vname' => 'LBL_NAME',
            'type' => 'name',
            'link' => true,
            'fields' => array('first_name', 'last_name'),
            'sort_on' => 'last_name',
            'source' => 'non-db',
            'group' => 'last_name',
            'len' => '255',
            'db_concat_fields' => array(0 => 'first_name', 1 => 'last_name'),
            'importable' => 'false',
            'comment' => 'Full name'
        ),
        'full_name' => array(
            'name' => 'full_name',
            'rname' => 'full_name',
            'vname' => 'LBL_NAME',
            'type' => 'fullname',
            'fields' => array('first_name', 'last_name'),
            'sort_on' => 'last_name',
            'source' => 'non-db',
            'group' => 'last_name',
            'len' => '510',
            'db_concat_fields' => array(0 => 'first_name', 1 => 'last_name'),
            'studio' => array('listview' => false),
            'comment' => 'Full name'
        ),
        'title' => array(
            'name' => 'title',
            'vname' => 'LBL_TITLE',
            'type' => 'varchar',
            'len' => '100',
            'comment' => 'Job title or position of the contact'
        ),
        'photo' => array(
            'name' => 'photo',
            'vname' => 'LBL_PHOTO',
            'type' => 'image',
            'massupdate' => false,
            'help' => '',
            'importable' => false,
            'reportable' => true,
            'len' => 255,
            'dbType' => 'varchar',
            'width' => '160',
            'height' => '160',
			'studio' => array('listview' => true),
            'comment' => 'Profile photo of the contact'
        ),
        'department' => array(
            'name' => 'department',
            'vname' => 'LBL_DEPARTMENT',
            'type' => 'varchar',
            'len' => '255',
            'comment' => 'Department where the contact works',
            'merge_filter' => 'enabled',
        ),
        'do_not_call' => array(
            'name' => 'do_not_call',
            'vname' => 'LBL_DO_NOT_CALL',
            'type' => 'bool',
            'default' => '0',
            'audited' => true,
            'comment' => 'Indicates whether the contact should not be called'
        ),
        'phone_home' => array(
            'name' => 'phone_home',
            'vname' => 'LBL_HOME_PHONE',
            'type' => 'phone',
            'dbType' => 'varchar',
            'len' => 100,
            'unified_search' => true,
            'full_text_search' => array('boost' => 1),
            'comment' => 'Home phone number of the contact',
            'merge_filter' => 'enabled',
        ),
        //bug 42902
        'email' => array(
            'name' => 'email',
            'type' => 'email',
            'query_type' => 'default',
            'source' => 'non-db',
            'operator' => 'subquery',
            'subquery' => 'SELECT eabr.bean_id FROM email_addr_bean_rel eabr JOIN email_addresses ea ON (ea.id = eabr.email_address_id) WHERE eabr.deleted=0 AND ea.email_address LIKE',
            'db_field' => array(
                'id',
            ),
            'vname' => 'LBL_ANY_EMAIL',
            'studio' => array('visible' => false, 'searchview' => true),
            'importable' => false,
            'comment' => 'The primary email address of the person',
        ),
        'phone_mobile' => array(
            'name' => 'phone_mobile',
            'vname' => 'LBL_MOBILE_PHONE',
            'type' => 'phone',
            'dbType' => 'varchar',
            'len' => 100,
            'unified_search' => true,
            'full_text_search' => array('boost' => 1),
            'comment' => 'Mobile phone number of the contact',
            'merge_filter' => 'enabled',
        ),
        'phone_work' => array(
            'name' => 'phone_work',
            'vname' => 'LBL_OFFICE_PHONE',
            'type' => 'phone',
            'dbType' => 'varchar',
            'len' => 100,
            'audited' => true,
            'unified_search' => true,
            'full_text_search' => array('boost' => 1),
            'comment' => 'Work phone number of the contact',
            'merge_filter' => 'enabled',
        ),
        'phone_other' => array(
            'name' => 'phone_other',
            'vname' => 'LBL_OTHER_PHONE',
            'type' => 'phone',
            'dbType' => 'varchar',
            'len' => 100,
            'unified_search' => true,
            'full_text_search' => array('boost' => 1),
            'comment' => 'Additional phone number of the contact',
            'merge_filter' => 'enabled',
        ),
        'phone_fax' => array(
            'name' => 'phone_fax',
            'vname' => 'LBL_FAX_PHONE',
            'type' => 'phone',
            'dbType' => 'varchar',
            'len' => 100,
            'unified_search' => true,
            'full_text_search' => array('boost' => 1),
            'comment' => 'Fax number of the contact',
            'merge_filter' => 'enabled',
        ),
        'email1' => array(
            'name' => 'email1',
            'vname' => 'LBL_EMAIL_ADDRESS',
            'type' => 'varchar',
            'function' => array(
                'name' => 'getEmailAddressWidget',
                'returns' => 'html'
            ),
            'source' => 'non-db',
            'group' => 'email1',
            'merge_filter' => 'enabled',
            'studio' => array('editview' => true, 'editField' => true, 'searchview' => false, 'popupsearch' => false),
            // bug 46859
            'full_text_search' => array('boost' => 3, 'analyzer' => 'whitespace'),
            //bug 54567
            'comment' => 'Email address widget of the contact'
        ),
        'email2' => array(
            'name' => 'email2',
            'vname' => 'LBL_OTHER_EMAIL_ADDRESS',
            'type' => 'varchar',
            'function' => array(
                'name' => 'getEmailAddressWidget',
                'returns' => 'html'
            ),
            'source' => 'non-db',
            'group' => 'email2',
            'merge_filter' => 'enabled',
            'studio' => 'false',
            'comment' => 'The secondary email address of the person',
        ),
        'invalid_email' => array(
            'name' => 'invalid_email',
            'vname' => 'LBL_INVALID_EMAIL',
            'source' => 'non-db',
            'type' => 'bool',
            'massupdate' => false,
            'studio' => 'false',
            'comment' => 'A flag indicating whether the primary email address is invalid or undeliverable',
        ),
        'email_opt_out' => array(
            'name' => 'email_opt_out',
            'vname' => 'LBL_EMAIL_OPT_OUT',
            'source' => 'non-db',
            'type' => 'bool',
            'massupdate' => false,
            'studio' => 'false',
            'comment' => 'A flag indicating whether the person has opted out of email communications',
        ),
        'lawful_basis' => array(
            'name' => 'lawful_basis',
            'vname' => 'LBL_LAWFUL_BASIS',
            'type' => 'multienum',
            'massupdate' => true,
            'no_default' => false,
            'inline_edit' => true,
            'reportable' => true,
            'merge_filter' => 'enabled',
            'len' => 100,
            'size' => '20',
            'options' => 'lawful_basis_dom',
            'audited' => true,
            'importable' => true,
            'comment' => 'Legal basis for processing the contact\'s personal data'
        ),
        'date_reviewed' => array(
            'name' => 'date_reviewed',
            'vname' => 'LBL_DATE_REVIEWED',
            'type' => 'date',
            'massupdate' => true,
            'audited' => true,
            'importable' => true,
            'comment' => 'Date when the legal basis was last reviewed'
        ),
        'lawful_basis_source' => array(
            'name' => 'lawful_basis_source',
            'vname' => 'LBL_LAWFUL_BASIS_SOURCE',
            'type' => 'enum',
            'massupdate' => true,
            'no_default' => false,
            'inline_edit' => true,
            'reportable' => true,
            'merge_filter' => 'enabled',
            'len' => 100,
            'size' => '20',
            'options' => 'lawful_basis_source_dom',
            'audited' => true,
            'importable' => true,
            'comment' => 'Source of the legal basis for data processing'
        ),
        'primary_address_street' => array(
            'name' => 'primary_address_street',
            'vname' => 'LBL_PRIMARY_ADDRESS_STREET',
            'type' => 'varchar',
            'len' => '150',
            'group' => 'primary_address',
            'merge_filter' => 'enabled',
            'comment' => 'Street address of the primary residence',
        ),
        'primary_address_street_2' => array(
            'name' => 'primary_address_street_2',
            'vname' => 'LBL_PRIMARY_ADDRESS_STREET_2',
            'type' => 'varchar',
            'len' => '150',
            'source' => 'non-db',
            'comment' => 'The second line of the primary address street, used for suite or apartment numbers',
        ),
        'primary_address_street_3' => array(
            'name' => 'primary_address_street_3',
            'vname' => 'LBL_PRIMARY_ADDRESS_STREET_3',
            'type' => 'varchar',
            'len' => '150',
            'source' => 'non-db',
            'comment' => 'The third line of the primary address street for additional address details',
        ),
        'primary_address_city' => array(
            'name' => 'primary_address_city',
            'vname' => 'LBL_PRIMARY_ADDRESS_CITY',
            'type' => 'varchar',
            'len' => '100',
            'group' => 'primary_address',
            'merge_filter' => 'enabled',
            'comment' => 'City of the primary residence',
        ),
        'primary_address_state' => array(
            'name' => 'primary_address_state',
            'vname' => 'LBL_PRIMARY_ADDRESS_STATE',
            'type' => 'varchar',
            'len' => '100',
            'group' => 'primary_address',
            'merge_filter' => 'enabled',
            'comment' => 'State or region of the primary residence',
        ),
        'primary_address_postalcode' => array(
            'name' => 'primary_address_postalcode',
            'vname' => 'LBL_PRIMARY_ADDRESS_POSTALCODE',
            'type' => 'varchar',
            'len' => '20',
            'group' => 'primary_address',
            'merge_filter' => 'enabled',
            'comment' => 'Postal code of the primary residence',

        ),
        'primary_address_country' => array(
            'name' => 'primary_address_country',
            'vname' => 'LBL_PRIMARY_ADDRESS_COUNTRY',
            'type' => 'varchar',
            'group' => 'primary_address',
            'merge_filter' => 'enabled',
            'comment' => 'Country of the primary residence',
        ),
        'alt_address_street' => array(
            'name' => 'alt_address_street',
            'vname' => 'LBL_ALT_ADDRESS_STREET',
            'type' => 'varchar',
            'len' => '150',
            'group' => 'alt_address',
            'merge_filter' => 'enabled',
            'comment' => 'Street address of the alternate residence',
        ),
        'alt_address_street_2' => array(
            'name' => 'alt_address_street_2',
            'vname' => 'LBL_ALT_ADDRESS_STREET_2',
            'type' => 'varchar',
            'len' => '150',
            'source' => 'non-db',
            'comment' => 'The second line of the alternate address street, used for suite or apartment numbers',
        ),
        'alt_address_street_3' => array(
            'name' => 'alt_address_street_3',
            'vname' => 'LBL_ALT_ADDRESS_STREET_3',
            'type' => 'varchar',
            'len' => '150',
            'source' => 'non-db',
            'comment' => 'The third line of the alternate address street for additional address details',
        ),
        'alt_address_city' => array(
            'name' => 'alt_address_city',
            'vname' => 'LBL_ALT_ADDRESS_CITY',
            'type' => 'varchar',
            'len' => '100',
            'group' => 'alt_address',
            'merge_filter' => 'enabled',
            'comment' => 'City of the alternate residence',
        ),
        'alt_address_state' => array(
            'name' => 'alt_address_state',
            'vname' => 'LBL_ALT_ADDRESS_STATE',
            'type' => 'varchar',
            'len' => '100',
            'group' => 'alt_address',
            'merge_filter' => 'enabled',
            'comment' => 'State or region of the alternate residence',
        ),
        'alt_address_postalcode' => array(
            'name' => 'alt_address_postalcode',
            'vname' => 'LBL_ALT_ADDRESS_POSTALCODE',
            'type' => 'varchar',
            'len' => '20',
            'group' => 'alt_address',
            'merge_filter' => 'enabled',
            'comment' => 'Postal code of the alternate residence',
        ),
        'alt_address_country' => array(
            'name' => 'alt_address_country',
            'vname' => 'LBL_ALT_ADDRESS_COUNTRY',
            'type' => 'varchar',
            'group' => 'alt_address',
            'merge_filter' => 'enabled',
            'comment' => 'Country of the alternate residence',
        ),
        'assistant' => array(
            'name' => 'assistant',
            'vname' => 'LBL_ASSISTANT',
            'type' => 'varchar',
            'len' => '75',
            'unified_search' => true,
            'full_text_search' => array('boost' => 2),
            'merge_filter' => 'enabled',
            'comment' => 'Name of the contact\'s assistant',
        ),
        'assistant_phone' => array(
            'name' => 'assistant_phone',
            'vname' => 'LBL_ASSISTANT_PHONE',
            'type' => 'phone',
            'dbType' => 'varchar',
            'len' => 100,
            'group' => 'assistant',
            'unified_search' => true,
            'full_text_search' => array('boost' => 1),
            'comment' => 'Phone number of the contact\'s assistant',
            'merge_filter' => 'enabled',
        ),

        'email_addresses_primary' => array(
            'name' => 'email_addresses_primary',
            'type' => 'link',
            'relationship' => strtolower($object_name) . '_email_addresses_primary',
            'source' => 'non-db',
            'vname' => 'LBL_EMAIL_ADDRESS_PRIMARY',
            'duplicate_merge' => 'disabled',
        ),
        'email_addresses' => array(
            'name' => 'email_addresses',
            'type' => 'link',
            'relationship' => strtolower($object_name) . '_email_addresses',
            'source' => 'non-db',
            'vname' => 'LBL_EMAIL_ADDRESSES',
            'reportable' => false,
            'unified_search' => true,
            'rel_fields' => array('primary_address' => array('type' => 'bool')),
        ),
        // Used for non-primary mail import
        'email_addresses_non_primary' => array(
            'name' => 'email_addresses_non_primary',
            'type' => 'email',
            'source' => 'non-db',
            'vname' => 'LBL_EMAIL_NON_PRIMARY',
            'studio' => false,
            'reportable' => false,
            'massupdate' => false,
            'comment' => 'Additional non-primary email addresses associated with the person',
        ),
    ),
    'relationships' => array(
        strtolower($module) . '_email_addresses' => array(
            'lhs_module' => $module,
            'lhs_table' => $table_name,
            'lhs_key' => 'id',
            'rhs_module' => 'EmailAddresses',
            'rhs_table' => 'email_addresses',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'email_addr_bean_rel',
            'join_key_lhs' => 'bean_id',
            'join_key_rhs' => 'email_address_id',
            'relationship_role_column' => 'bean_module',
            'relationship_role_column_value' => $module
        ),
        strtolower($module) . '_email_addresses_primary' => array(
            'lhs_module' => $module,
            'lhs_table' => $table_name,
            'lhs_key' => 'id',
            'rhs_module' => 'EmailAddresses',
            'rhs_table' => 'email_addresses',
            'rhs_key' => 'id',
            'relationship_type' => 'many-to-many',
            'join_table' => 'email_addr_bean_rel',
            'join_key_lhs' => 'bean_id',
            'join_key_rhs' => 'email_address_id',
            'relationship_role_column' => 'primary_address',
            'relationship_role_column_value' => '1'
        ),
    )
);

