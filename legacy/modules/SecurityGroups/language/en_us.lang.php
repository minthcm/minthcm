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
if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

$mod_strings = array(
   'LBL_ALL_MODULES' => 'All', //rost fix
   'LBL_ASSIGNED_TO_ID' => 'Assigned User Id',
   'LBL_ASSIGNED_TO_NAME' => 'Assigned to',
   'LBL_ID' => 'ID',
   'LBL_DATE_ENTERED' => 'Date Created',
   'LBL_DATE_MODIFIED' => 'Date Modified',
   'LBL_MODIFIED' => 'Modified By',
   'LBL_MODIFIED_NAME' => 'Modified By Name',
   'LBL_CREATED' => 'Created By',
   'LBL_DESCRIPTION' => 'Description',
   'LBL_DELETED' => 'Deleted',
   'LBL_NONINHERITABLE' => 'Not Inheritable',
   'LBL_LIST_NONINHERITABLE' => 'Not Inheritable',
   'LBL_NAME' => 'Name',
   'LBL_CREATED_USER' => 'Created by User',
   'LBL_MODIFIED_USER' => 'Modified by User',
   'LBL_LIST_FORM_TITLE' => 'Organizational Units',
   'LBL_MODULE_NAME' => 'Organizational Units Management',
   'LBL_MODULE_TITLE' => 'Organizational Units Management',
   'LNK_NEW_RECORD' => 'Create a Organizational Unit',
   'LNK_LIST' => 'List View',
   'LBL_SEARCH_FORM_TITLE' => 'Search Organizational Units Management',
   'LBL_HISTORY_SUBPANEL_TITLE' => 'History',
   'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Activities',
   'LBL_SECURITYGROUPS_SUBPANEL_TITLE' => 'Organizational Units Management',
   'LBL_USERS' => 'Users',
   'LBL_USERS_SUBPANEL_TITLE' => 'Users',
   'LBL_ROLES_SUBPANEL_TITLE' => 'Roles',
   'LBL_ROLES' => 'Roles',
   'LBL_CONFIGURE_SETTINGS' => 'Configure',
   'LBL_ADDITIVE' => 'Additive Rights',
   'LBL_ADDITIVE_DESC' => "User gets greatest rights of all roles assigned to the user or the user's organizational unit(s)",
   'LBL_STRICT_RIGHTS' => 'Strict Rights',
   'LBL_STRICT_RIGHTS_DESC' => "If a user is a member of several organizational units only the respective rights from the organizational unit assigned to the current record are used.",
   'LBL_USER_ROLE_PRECEDENCE' => 'User Role Precedence',
   'LBL_USER_ROLE_PRECEDENCE_DESC' => 'If any role is assigned directly to a user that role should take precedence over any organizational unit roles.',
   'LBL_INHERIT_TITLE' => 'Organizational Unit Inheritance Rules',
   'LBL_INHERIT_CREATOR' => 'Inherit from Created By User',
   'LBL_INHERIT_CREATOR_DESC' => 'The record will inherit all the organizational units assigned to the user who created it.',
   'LBL_INHERIT_PARENT' => 'Inherit from Parent Record',
   'LBL_INHERIT_PARENT_DESC' => 'e.g. If a case is created for a contact the case will inherit the organizational units associated with the contact.',
   'LBL_USER_POPUP' => 'New User Organizational Unit Popup',
   'LBL_USER_POPUP_DESC' => 'When creating a new user show the Organizational Units popup to assign the user to a organizational unit(s).',
   'LBL_INHERIT_ASSIGNED' => 'Inherit from Assigned To User',
   'LBL_INHERIT_ASSIGNED_DESC' => 'The record will inherit all the organizational units of the user assigned to the record. Other organizational units assigned to the record will NOT be removed.',
   'LBL_POPUP_SELECT' => 'Use Creator Organizational Unit Select',
   'LBL_POPUP_SELECT_DESC' => 'When a record is created by a user in more than one organizational unit show a organizational unit selection panel on the create screen. Otherwise inherit that one organizational unit.',
   'LBL_FILTER_USER_LIST' => 'Filter User List',
   'LBL_FILTER_USER_LIST_DESC' => "Non-admin users can only assign to users in the same organizational unit(s)",
   'LBL_DEFAULT_GROUP_TITLE' => 'Default Organizational units for New Records',
   'LBL_ADD_BUTTON_LABEL' => 'Add',
   'LBL_REMOVE_BUTTON_LABEL' => 'Remove',
   'LBL_GROUP' => 'Organizational Unit:',
   'LBL_MODULE' => 'Module:',
   'LBL_MASS_ASSIGN' => 'Organizational Units: Mass Assign',
   'LBL_ASSIGN' => 'Assign',
   'LBL_REMOVE' => 'Remove',
   'LBL_ASSIGN_CONFIRM' => 'Are you sure that you want to add this organizational unit to the ',
   'LBL_REMOVE_CONFIRM' => 'Are you sure that you want to remove this organizational unit from the ',
   'LBL_CONFIRM_END' => ' selected record(s)?',
   'LBL_SECURITYGROUP_USER_FORM_TITLE' => 'Organizational Unit/User',
   'LBL_USER_NAME' => 'User Name',
   'LBL_SECURITYGROUP_NAME' => 'Organizational Unit Name',
   'LBL_HOMEPAGE_TITLE' => 'Organizational Unit Messages',
   'LBL_TITLE' => 'Title',
   'LBL_ROWS' => 'Rows',
   'LBL_POST' => 'Post',
   'LBL_SELECT_GROUP_ERROR' => 'Please select a Organizational unit and try again.',
   'LBL_GROUP_SELECT' => 'Select which organizational units should have access to this record',
   'LBL_ERROR_DUPLICATE' => 'Due to a possible duplicate detected by MintHCM you will have to manually add Organizational Unit to your newly created record.',
   'LBL_INBOUND_EMAIL' => 'Inbound email account',
   'LBL_INBOUND_EMAIL_DESC' => 'Only allow access to an email account if user belongs to a organizational unit that is assigned to the mail account.',
   'LBL_PRIMARY_GROUP' => 'Primary Organizational Unit',
   'LBL_CHECKMARK' => 'Checkmark',
   'LBL_GROUP_TYPE' => 'Type',
   'LBL_ERROR_EXPORT_WHERE_CHANGED' => 'Update failed because the search filter was modified. Please try again.',
   
   'LBL_CURRENT_MANAGER_ID' => 'Current Manager (ID)',
   'LBL_CURRENT_MANAGER' => 'Current Manager',
   'LBL_CURRENT_MANAGER_NAME' => 'Current Manager',
   'LBL_ONBOARDINGOFFBOARDINGELEMENTS' => 'Onboarding/Offboarding Elements',
   'LBL_MEMBER_OF' => 'Parent Unit',
   'LBL_PARENT_ID' => 'Parent Unit (ID)',
   'LBL_POSITIONS_LEADER' => 'Supervisor',
   'LBL_POSITION_LEADER_NAME' => 'Supervisor',
   'LBL_POSITION_LEADER_ID' => 'Supervisor (ID)',
   'LBL_MEMBERS' => 'Member Units',
   'LBL_POSITIONS_MEMBERSHIP' => 'Member Positions',
   'LBL_EMPLOYEES' => 'Employees',

   'LBL_RELATIONSHIP_ROOMS_NAME' => 'Rooms',
   'LBL_EMPLOYEES_IN_DEPARTEMENTS' => 'Employees in the Department',
   'LBL_PARENT_GROUP_ERROR' => 'Selected Group is a child Unit of this Group',
);
