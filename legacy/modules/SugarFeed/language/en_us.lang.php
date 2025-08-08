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
   'LBL_ASSIGNED_TO_ID' => 'Assigned User Id',
   'LBL_ASSIGNED_TO_NAME' => 'Assigned to',
   'LBL_ID' => 'ID',
   'LBL_DATE_ENTERED' => 'Date Created',
   'LBL_DATE_MODIFIED' => 'Date Modified',
   'LBL_MODIFIED' => 'Modified by',
   'LBL_MODIFIED_NAME' => 'Modified by Name',
   'LBL_CREATED' => 'Created by',
   'LBL_DESCRIPTION' => 'Description',
   'LBL_DELETED' => 'Deleted',
   'LBL_NAME' => 'Name',
   'LBL_SAVING' => 'Saving...',
   'LBL_SAVED' => 'Saved',
   'LBL_CREATED_USER' => 'Created by User',
   'LBL_MODIFIED_USER' => 'Modified by User',
   'LBL_LIST_FORM_TITLE' => 'Feed List',
   'LBL_MODULE_NAME' => 'Activity Streams',
   'LBL_MODULE_TITLE' => 'Activity Streams',
   'LBL_DASHLET_DISABLED' => 'Warning: The Feed system is disabled, no new feed entries will be posted until it is activated',
   'LBL_RECORDS_DELETED' => 'All previous Feed entries have been removed, if the Feed system is enabled, new entries will be generated automatically.',
   'LBL_CONFIRM_DELETE_RECORDS' => 'Are you sure you wish to delete all of the Feed entries?',
   'LBL_FLUSH_RECORDS' => 'Delete Feed Entries',
   'LBL_ENABLE_FEED' => 'Enable My Activity Stream Dashlet',
   'LBL_ENABLE_MODULE_LIST' => 'Activate Feeds For',
   'LBL_HOMEPAGE_TITLE' => 'My Activity Stream',
   'LNK_NEW_RECORD' => 'Create Feed',
   'LNK_LIST' => 'Feed',
   'LBL_SEARCH_FORM_TITLE' => 'Search Feed',
   'LBL_HISTORY_SUBPANEL_TITLE' => 'View History',
   'LBL_ACTIVITIES_SUBPANEL_TITLE' => 'Activities',
   'LBL_NEW_FORM_TITLE' => 'New Feed',
   'LBL_ALL' => 'All',
   'LBL_USER_FEED' => 'User Feed',
   'LBL_ENABLE_USER_FEED' => 'Activate User Feed',
   'LBL_TO' => 'Visible to Team',
   'LBL_IS' => 'is',
   'LBL_DONE' => 'Done',
   'LBL_TITLE' => 'Title',
   'LBL_ROWS' => 'Rows',
   'LBL_CATEGORIES' => 'Modules',
   'LBL_TIME_LAST_WEEK' => 'Last Week',
   'LBL_TIME_WEEKS' => 'weeks',
   'LBL_TIME_DAYS' => 'days',
   'LBL_TIME_YESTERDAY' => 'Yesterday',
   'LBL_TIME_HOURS' => 'Hours',
   'LBL_TIME_HOUR' => 'Hours',
   'LBL_TIME_MINUTES' => 'Minutes',
   'LBL_TIME_MINUTE' => 'Minute',
   'LBL_TIME_SECONDS' => 'Seconds',
   'LBL_TIME_SECOND' => 'Second',
   'LBL_TIME_AND' => 'and',
   'LBL_TIME_AGO' => 'ago',
   // Activity stream
   'CREATED_CONTACT' => 'created a <b>NEW</b> {0}',
   'CREATED_CASE' => 'created a <b>NEW</b> {0}',
   'CREATED_LEAD' => 'created a <b>NEW</b> {0}',
   'FOR' => 'for', // Activity stream for cases
   'FOR_AMOUNT' => 'for amount', // Activity stream for cases
   'CLOSED_CASE' => '<b>CLOSED</b> a {0} ',
   'CONVERTED_LEAD' => '<b>CONVERTED</b> a {0}',
   'WITH' => 'with',
   'LBL_SELECT' => 'Select',
   'LBL_POST' => 'Post',
   'LBL_AUTHENTICATE' => 'Connect to',
   'LBL_AUTHENTICATION_PENDING' => 'Not all of the external accounts you have selected have been authenticated. Click \'Cancel\' to return to the Options window to authenticate the external accounts, or click \'Ok\' to proceed without authenticating.',
   'LBL_ADVANCED_SEARCH' => 'Advanced Filter' /* for 508 compliance fix */,
   'LBL_SHOW_MORE_OPTIONS' => 'Show More Options',
   'LBL_HIDE_OPTIONS' => 'Hide Options',
   'LBL_VIEW' => 'View',
   'LBL_POST_TITLE' => 'Post Status Update for ',
   'LBL_URL_LINK_TITLE' => 'URL Link to use',
   'LBL_CREATED_RESPONSIBILITYACTIVITIES' => 'created a <b>new</b> activity',
   'LBL_CREATED_APPRAISALS' => 'created a <b>new</b> appraisal',
   'LBL_CREATED_BENEFITS' => 'created a <b>new</b> benefit',
   'LBL_CREATED_CANDIDATES' => 'created a <b>new</b> candidate',
   'LBL_CREATED_CANDIDATURES' => 'created a <b>new</b> candidature',
   'LBL_CREATED_COMPETENCIES' => 'created a <b>new</b> competency',
   'LBL_CREATED_CONCLUSIONS' => 'created a <b>new</b> conclusion',
   'LBL_CREATED_COSTS' => 'created a <b>new</b> cost',
   'LBL_CREATED_DELEGATIONS' => 'created a <b>new</b> delegation',
   'LBL_CREATED_EXITINTERVIEWS' => 'created a <b>new</b> exit interview',
   'LBL_CREATED_GOALS' => 'created a <b>new</b> goal',
   'LBL_CREATED_IDEAS' => 'created a <b>new</b> idea',
   'LBL_CREATED_IMPROVEMENTS' => 'created a <b>new</b> improvement',
   'LBL_CREATED_NEWS' => 'created a <b>new</b> news',
   'LBL_CREATED_OFFBOARDINGS' => 'created a <b>new</b> offboarding',
   'LBL_CREATED_ONBOARDINGS' => 'created a <b>new</b> onboarding',
   'LBL_CREATED_ORGANIZATIONALUNITS' => 'created a <b>new</b> organizational unit',
   'LBL_CREATED_POSITIONS' => 'created a <b>new</b> position',
   'LBL_CREATED_PROBLEMS' => 'created a <b>new</b> problem',
   'LBL_CREATED_RECRUITMENTS' => 'created a <b>new</b> recruitment',
   'LBL_CREATED_RESERVATIONS' => 'created a <b>new</b> reservation',
   'LBL_CREATED_RESOURCES' => 'created a <b>new</b> resource',
   'LBL_CREATED_RESPONSIBILITIES' => 'created a <b>new</b> responsibility',
   'LBL_CREATED_EMPLOYEEROLES' => 'created a <b>new</b> role',
   'LBL_CREATED_TRAININGS' => 'created a <b>new</b> training',
   'LBL_CREATED_TRANSPORTATIONS' => 'created a <b>new</b> transport',
);
