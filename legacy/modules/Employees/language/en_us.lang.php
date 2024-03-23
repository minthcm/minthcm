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

$mod_strings = array(
    'LBL_MODULE_NAME' => 'Employees',
    'LBL_MODULE_TITLE' => 'Employees: Home',
    'LBL_SEARCH_FORM_TITLE' => 'Employee Search',
    'LBL_LIST_FORM_TITLE' => 'Employees',
    'LBL_NEW_FORM_TITLE' => 'New Employee',
    'LBL_LOGIN' => 'Login',
    'LBL_RESET_PREFERENCES' => 'Reset To Default Preferences',
    'LBL_TIME_FORMAT' => 'Time Format:',
    'LBL_DATE_FORMAT' => 'Date Format:',
    'LBL_TIMEZONE' => 'Current Time:',
    'LBL_CURRENCY' => 'Currency:',
    'LBL_LIST_NAME' => 'Name',
    'LBL_LIST_LAST_NAME' => 'Last Name',
    'LBL_LIST_EMPLOYEE_NAME' => 'Employee Name',
    'LBL_LIST_DEPARTMENT' => 'Department',
    'LBL_LIST_REPORTS_TO_NAME' => 'Reports To',
    'LBL_LIST_EMAIL' => 'Email',
    'LBL_LIST_USER_NAME' => 'User Name',
    'LBL_ERROR' => 'Error:',
    'LBL_PASSWORD' => 'Password:',
    'LBL_USER_NAME' => 'User Name:',
    'LBL_USER_TYPE' => 'User Type',
    'LBL_FIRST_NAME' => 'First Name:',
    'LBL_LAST_NAME' => 'Last Name:',
    'LBL_THEME' => 'Theme:',
    'LBL_LANGUAGE' => 'Language:',
    'LBL_ADMIN' => 'Administrator:',
    'LBL_EMPLOYEE_INFORMATION' => 'Employee Information',
    'LBL_OFFICE_PHONE' => 'Office Phone:',
    'LBL_REPORTS_TO_ID' => 'Reports to Id:',
    'LBL_REPORTS_TO_NAME' => 'Reports to',
    'LBL_OTHER_PHONE' => 'Other Phone:',
    'LBL_NOTES' => 'Notes:',
    'LBL_DEPARTMENT' => 'Department:',
    'LBL_TITLE' => 'Title:',
    'LBL_ANY_ADDRESS' => 'Any Address:',
    'LBL_ANY_PHONE' => 'Any Phone:',
    'LBL_ANY_EMAIL' => 'Any Email:',
    'LBL_ADDRESS' => 'Address:',
    'LBL_CITY' => 'City:',
    'LBL_STATE' => 'State:',
    'LBL_POSTAL_CODE' => 'Postal Code:',
    'LBL_COUNTRY' => 'Country:',
    'LBL_NAME' => 'Name:',
    'LBL_MOBILE_PHONE' => 'Mobile:',
    'LBL_FAX' => 'Fax:',
    'LBL_EMAIL' => 'Email Address:',
    'LBL_EMAIL_LINK_TYPE' => 'Email Client',
    'LBL_EMAIL_LINK_TYPE_HELP' => '<b>MintHCM Mail Client:</b> Send emails using the email client in the MintHCM application.<br><b>External Mail Client:</b> Send email using an email client outside of the MintHCM application, such as Microsoft Outlook.',
    'LBL_HOME_PHONE' => 'Home Phone:',
    'LBL_WORK_PHONE' => 'Work Phone:',
    'LBL_EMPLOYEE_STATUS' => 'Employee Status:',
    'LBL_PRIMARY_ADDRESS' => 'Primary Address:',
    'LBL_SAVED_SEARCH' => 'Layout Options',
    'LBL_MESSENGER_ID' => 'IM Name:',
    'LBL_MESSENGER_TYPE' => 'IM Type:',
    'ERR_LAST_ADMIN_1' => 'The employee name "',
    'ERR_LAST_ADMIN_2' => '" is the last employee with administrator access. At least one employee must be an administrator.',
    'LNK_NEW_EMPLOYEE' => 'Create Employee',
    'LNK_EMPLOYEE_LIST' => 'View Employees',
    'ERR_DELETE_RECORD' => 'You must specify a record number to delete the account.',
    'LBL_LIST_EMPLOYEE_STATUS' => 'Employee Status',
    'LBL_SUITE_LOGIN' => 'Is User',
    'LBL_RECEIVE_NOTIFICATIONS' => 'Notify on Assignment',
    'LBL_IS_ADMIN' => 'Is Administrator',
    'LBL_GROUP' => 'Group User',
    'LBL_PHOTO' => 'Photo',
    'LBL_DELETE_USER_CONFIRM' => 'This Employee is also a User. Deleting the Employee record will also delete the User record, and the User will no longer be able to access the application. Do you want to proceed with deleting this record?',
    'LBL_DELETE_EMPLOYEE_CONFIRM' => 'Are you sure you want to delete this employee?',
    'LBL_ONLY_ACTIVE' => 'Active Employees',
    'LBL_SELECT' => 'Select' /* for 508 compliance fix */,
    'LBL_AUTHENTICATE_ID' => 'Authentication Id',
    'LBL_EXT_AUTHENTICATE' => 'External Authentication',
    'LBL_GROUP_USER' => 'Group User',
    'LBL_LIST_ACCEPT_STATUS' => 'Accept Status',
    'LBL_MODIFIED_BY' => 'Modified By',
    'LBL_MODIFIED_BY_ID' => 'Modified By Id',
    'LBL_CREATED_BY_NAME' => 'Created By', //bug48978
    'LBL_PORTAL_ONLY_USER' => 'Portal API User',
    'LBL_PSW_MODIFIED' => 'Password Last Changed',
    'LBL_SHOW_ON_EMPLOYEES' => 'Display Employee Record',
    'LBL_USER_HASH' => 'Password',
    'LBL_SYSTEM_GENERATED_PASSWORD' => 'System Generated Password',
    'LBL_DESCRIPTION' => 'Description',
    'LBL_FAX_PHONE' => 'Fax',
    'LBL_STATUS' => 'Status',
    'LBL_ADDRESS_CITY' => 'Address City',
    'LBL_ADDRESS_COUNTRY' => 'Address Country',
    'LBL_ADDRESS_INFORMATION' => 'Address Information',
    'LBL_ADDRESS_POSTALCODE' => 'Address Postal Code',
    'LBL_ADDRESS_STATE' => 'Address State',
    'LBL_ADDRESS_STREET' => 'Address Street',
    'LBL_DATE_MODIFIED' => 'Date Modified',
    'LBL_DATE_ENTERED' => 'Date Entered',
    'LBL_DELETED' => 'Deleted',
    'LBL_BUTTON_SELECT' => 'Select',
    'LBL_BUTTON_CLEAR' => 'Clear',
    'LBL_CONTACTS_SYNC' => 'Contact Sync',
    'LBL_OAUTH_TOKENS' => 'OAuth Tokens',
    'LBL_PROJECT_USERS_1_FROM_PROJECT_TITLE' => 'Project Users from Project Title',
    'LBL_PROJECT_CONTACTS_1_FROM_CONTACTS_TITLE' => 'Project Contacts from Contacts Title',
    'LBL_ROLES' => 'Roles',
    'LBL_SECURITYGROUPS' => 'Organizational Unit',
    'LBL_PROSPECT_LIST' => 'Prospect List',
    'LBL_USERS_SPENT_TIME_TITLE' => 'User Actual Effort',
    'LBL_CONTRACTS' => 'Contracts',
    'LBL_RESOURCES' => 'Resources',
    'LBL_RESERVATIONS' => 'Reservations',
    'LBL_PERIODSOFEMPLOYMENT' => 'Periods of employment',
    'LBL_EXITINTERVIEWS_EMPLOYEE_TITLE' => 'Exit Interviews',
    'LBL_POSITION_NAME' => 'Position',
    'LBL_POSITION_ID' => 'Position (ID)',
    'LBL_ROLES' => 'Roles',
    'LBL_BENEFITS' => 'Benefits',
    'LBL_RESPONSIBILITIES' => 'Responsibilities',
    'LBL_ONBOARDINGS' => 'Onboardings',
    'LBL_OFFBOARDINGS' => 'Offboardings',
    'LBL_COMPETENCYRATINGS' => 'Competency Ratings',
    'LBL_GOALS' => 'Goals',
    'LBL_APPRAISALS' => 'Appraisals',
    'LBL_CREATE_APPRAISAL' => 'Create Appraisal',
    'LBL_CANDIDATE_EMPLOYEE_RELATE_FROM_EMPLOYEE' => 'Candidate',
    'LBL_SECURITYGROUPS_EMPLOYEES' => 'Organizational Units',
    'LBL_SECURITYGROUP_NAME' => 'Organizational Unit',
    'LBL_SECURITYGROUP_ID' => 'Organizational Unit (ID)',
    'LBL_RELATIONSHIP_SECURITYGROUPS_NAME' => 'Organizational Units',
    'LBL_CERTIFICATES' => 'Certificates',

    'LBL_APPLICATIONS_SUBPANEL' => 'Applications',
    'LBL_CANDIDATE_EMPLOYEE_LINK_FROM_EMPLOYEE' => 'Candidates',
    'LBL_POSITION_EMPLOYEES' => 'Position Employees',
    'LBL_RELATIONSHIP_IDEAS_NAME' => 'Ideas',
    'LBL_SCHEDULEREPORTS' => 'Schedules',
    'LBL_USERS_FORCED_TABS_DASHBOARDS' => 'Forced dashboards',
    'LBL_USERS_LOCKED_DASHBOARDS' => 'Locked dashboards',
    'LBL_USERS_ONBOARDINGOFFBOARDINGELEMENTS' => 'Onboarding/Offboarding elements',
    'LBL_POSITION_EMPLOYEES' => 'Stanowiska',
    'LBL_CANDIDATE_EMPLOYEE_LINK_FROM_EMPLOYEE' => 'Employee candidate link',
    'LBL_CANDIDATE_EMPLOYEE_LINK_FROM_CANDIDATE' => 'Candidate link for candidate',
    'LBL_CANDIDATE_EMPLOYEE_ID_FROM_EMPLOYEE' => 'Candidate ID for employee',
    'LBL_CANDIDATE_EMPLOYEE_ID_FROM_CANDIDATE' => 'Candidate ID for candidate',
    'LBL_USERS_ONE_TIME_DEFAULT_DASHBOARDS' => 'Users: One Time Default',
    'LBL_RELATIONSHIP_CERTIFICATES_NAME' => 'Certificates',
    'LBL_RELATIONSHIP_TRAININGS_NAME' => 'Trainings',
    'LBL_FACTOR_AUTH' => 'Factor Auth',
    'LBL_FACTOR_AUTH_INTERFACE' => 'Factor Auth Interface',
    'LBL_GENERATE_ONBOARDING_OFFBOARDING' => 'Generate Onboarding/Offboarding',
    'LBL_SUBORDINATES' => 'Subordinates',
    'LBL_SECURITYGROUPS_MANAGERS' => 'Manager in Organizational Units',

    'LBL_LINKED_ALLOCATIONS_TITLE' => 'Allocations',
    'LBL_TRAININGS' => 'Trainings',
    'LBL_CANDIDATURES' => 'Candidatures',
    'LBL_BUSINESS_ROLE' => 'Business Role',
    'LBL_EMPLOYEES_CONFIRMATION_BUTTON_CONFIRM' => 'YES',
    'LBL_EMPLOYEES_CONFIRMATION_BUTTON_CANCEL' => 'NO',
    'LBL_DEPUTY'=>'Deputy',
);
