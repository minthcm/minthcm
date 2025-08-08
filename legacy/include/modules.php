<?php
/*
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

global $moduleList;
global $beanList;
global $beanFiles;
global $customBeanList;
global $customObjectList;
global $customBeanFiles;
global $modInvisList;
global $adminOnlyList;
global $report_include_modules;
global $modInvisListApiExceptions;

$customBeanList = [];
$customObjectList = [];
$customBeanFiles = [];

$moduleList = [];
// this list defines the modules shown in the top tab list of the app
//the order of this list is the default order displayed - do not change the order unless it is on purpose
$moduleList[] = 'Home';
$moduleList[] = 'Calendar';
$moduleList[] = 'Calls';
$moduleList[] = 'Meetings';
$moduleList[] = 'Tasks';
$moduleList[] = 'Notes';
// $moduleList[] = 'Leads';
// $moduleList[] = 'Contacts';
// $moduleList[] = 'Accounts';

$moduleList[] = 'Emails';
$moduleList[] = 'EmailTemplates';
$moduleList[] = 'Campaigns';
// $moduleList[] = 'Prospects';
$moduleList[] = 'ProspectLists';

$moduleList[] = 'Documents';
// $moduleList[] = 'Cases';
$moduleList[] = 'Project';
// $moduleList[] = 'Bugs';
$moduleList[] = 'ResourceCalendar';
$moduleList[] = 'AOBH_BusinessHours';
$moduleList[] = 'Employees';

// $moduleList[] = 'Spots';

// this list defines all of the module names and bean names in the app
// to create a new module's bean class, add the bean definition here
$beanList = [];
//ACL Objects
$beanList['ACLRoles'] = 'ACLRole';
$beanList['ACLActions'] = 'ACLAction';
//END ACL OBJECTS
// $beanList['Leads'] = 'Lead';
// $beanList['Cases'] = 'aCase';
// $beanList['Bugs'] = 'Bug';
$beanList['ProspectLists'] = 'ProspectList';
// $beanList['Prospects'] = 'Prospect';
$beanList['Project'] = 'Project';
$beanList['ProjectTask'] = 'ProjectTask';
$beanList['Campaigns'] = 'Campaign';
$beanList['EmailMarketing'] = 'EmailMarketing';
$beanList['CampaignLog'] = 'CampaignLog';
$beanList['CampaignTrackers'] = 'CampaignTracker';
// $beanList['Releases'] = 'Release';
$beanList['Groups'] = 'Group';
$beanList['EmailMan'] = 'EmailMan';
$beanList['Schedulers'] = 'Scheduler';
$beanList['SchedulersJobs'] = 'SchedulersJob';
// $beanList['Contacts'] = 'Contact';
// $beanList['Accounts'] = 'Account';
$beanList['DynamicFields'] = 'DynamicField';
$beanList['EditCustomFields'] = 'FieldsMetaData';
$beanList['EmailTemplates'] = 'EmailTemplate';
$beanList['Notes'] = 'Note';
$beanList['Calls'] = 'Call';
$beanList['Emails'] = 'Email';
$beanList['Meetings'] = 'Meeting';
$beanList['Tasks'] = 'Task';
$beanList['Users'] = 'User';
$beanList['Currencies'] = 'Currency';
$beanList['Trackers'] = 'Tracker';
$beanList['Connectors'] = 'Connectors';
$beanList['Import_1'] = 'ImportMap';
$beanList['Import_2'] = 'UsersLastImport';
$beanList['Versions'] = 'Version';
$beanList['Administration'] = 'Administration';
$beanList['vCals'] = 'vCal';
$beanList['CustomFields'] = 'CustomFields';
$beanList['Alerts'] = 'Alert';

$beanList['Documents'] = 'Document';
$beanList['DocumentRevisions'] = 'DocumentRevision';
$beanList['Roles'] = 'Role';

$beanList['Audit'] = 'Audit';

// deferred
//$beanList['Queues'] = 'Queue';

$beanList['InboundEmail'] = 'InboundEmail';

$beanList['SavedSearch'] = 'SavedSearch';
$beanList['UserPreferences'] = 'UserPreference';
$beanList['MergeRecords'] = 'MergeRecord';
$beanList['EmailAddresses'] = 'EmailAddress';
$beanList['EmailText'] = 'EmailText';
$beanList['Relationships'] = 'Relationship';
$beanList['Employees'] = 'Employee';

// $beanList['Spots'] = 'Spots';
$beanList['AOBH_BusinessHours'] = 'AOBH_BusinessHours';

// this list defines all of the files that contain the SugarBean class definitions from $beanList
// to create a new module's bean class, add the file definition here
$beanFiles = [];

$beanFiles['ACLAction'] = 'modules/ACLActions/ACLAction.php';
$beanFiles['ACLRole'] = 'modules/ACLRoles/ACLRole.php';
$beanFiles['Relationship'] = 'modules/Relationships/Relationship.php';

// $beanFiles['Lead'] = 'modules/Leads/Lead.php';
// $beanFiles['aCase'] = 'modules/Cases/Case.php';
// $beanFiles['Bug'] = 'modules/Bugs/Bug.php';
$beanFiles['Group'] = 'modules/Groups/Group.php';
$beanFiles['CampaignLog'] = 'modules/CampaignLog/CampaignLog.php';
$beanFiles['Project'] = 'modules/Project/Project.php';
$beanFiles['ProjectTask'] = 'modules/ProjectTask/ProjectTask.php';
$beanFiles['Campaign'] = 'modules/Campaigns/Campaign.php';
$beanFiles['ProspectList'] = 'modules/ProspectLists/ProspectList.php';
// $beanFiles['Prospect'] = 'modules/Prospects/Prospect.php';

$beanFiles['EmailMarketing'] = 'modules/EmailMarketing/EmailMarketing.php';
$beanFiles['CampaignTracker'] = 'modules/CampaignTrackers/CampaignTracker.php';
// $beanFiles['Release'] = 'modules/Releases/Release.php';
$beanFiles['EmailMan'] = 'modules/EmailMan/EmailMan.php';

$beanFiles['Scheduler'] = 'modules/Schedulers/Scheduler.php';
$beanFiles['SchedulersJob'] = 'modules/SchedulersJobs/SchedulersJob.php';
// $beanFiles['Contact'] = 'modules/Contacts/Contact.php';
// $beanFiles['Account'] = 'modules/Accounts/Account.php';
$beanFiles['EmailTemplate'] = 'modules/EmailTemplates/EmailTemplate.php';
$beanFiles['Note'] = 'modules/Notes/Note.php';
$beanFiles['Call'] = 'modules/Calls/Call.php';
$beanFiles['Email'] = 'modules/Emails/Email.php';
$beanFiles['Meeting'] = 'modules/Meetings/Meeting.php';
$beanFiles['Task'] = 'modules/Tasks/Task.php';
$beanFiles['User'] = 'modules/Users/User.php';
$beanFiles['Employee'] = 'modules/Employees/Employee.php';
$beanFiles['Currency'] = 'modules/Currencies/Currency.php';
$beanFiles['Tracker'] = 'modules/Trackers/Tracker.php';
$beanFiles['ImportMap'] = 'modules/Import/maps/ImportMap.php';
$beanFiles['UsersLastImport'] = 'modules/Import/UsersLastImport.php';
$beanFiles['Administration'] = 'modules/Administration/Administration.php';
$beanFiles['UpgradeHistory'] = 'modules/Administration/UpgradeHistory.php';
$beanFiles['vCal'] = 'modules/vCals/vCal.php';
$beanFiles['Alert'] = 'modules/Alerts/Alert.php';

$beanFiles['Role'] = 'modules/Roles/Role.php';

$beanFiles['Document'] = 'modules/Documents/Document.php';
$beanFiles['DocumentRevision'] = 'modules/DocumentRevisions/DocumentRevision.php';
$beanFiles['FieldsMetaData'] = 'modules/DynamicFields/FieldsMetaData.php';
$beanFiles['Audit'] = 'modules/Audit/Audit.php';

// deferred
//$beanFiles['Queue'] = 'modules/Queues/Queue.php';

$beanFiles['InboundEmail'] = 'modules/InboundEmail/InboundEmail.php';

$beanFiles['SavedSearch'] = 'modules/SavedSearch/SavedSearch.php';
$beanFiles['UserPreference'] = 'modules/UserPreferences/UserPreference.php';
$beanFiles['MergeRecord'] = 'modules/MergeRecords/MergeRecord.php';
$beanFiles['EmailAddress'] = 'modules/EmailAddresses/EmailAddress.php';
$beanFiles['EmailText'] = 'modules/EmailText/EmailText.php';

$beanFiles['Configurator'] = 'modules/Configurator/Configurator.php';

// $beanFiles['Spots'] = 'modules/Spots/Spots.php';
$beanFiles['AOBH_BusinessHours'] = 'modules/AOBH_BusinessHours/AOBH_BusinessHours.php';


// added these lists for security settings for tabs
$modInvisList = [
    'Administration',
    'Currencies',
    'CustomFields',
    'Connectors',
    'Dropdown',
    'Dynamic',
    'DynamicFields',
    'DynamicLayout',
    'EditCustomFields',
    'Help',
    'Import',
    'MySettings',
    'EditCustomFields',
    'FieldsMetaData',
    'UpgradeWizard',
    'Trackers',
    'Connectors',
    'Employees',
    'Calendar',
    // 'Releases',
    'Sync',
    'Users',
    'Versions',
    'LabelEditor',
    'Roles',
    'EmailMarketing',
    'OptimisticLock',
    'TeamMemberships',
    'TeamSets',
    'TeamSetModule',
    'Audit',
    'MailMerge',
    'MergeRecords',
    'EmailAddresses',
    'EmailText',
    'Schedulers',
    'Schedulers_jobs',
    'CampaignTrackers',
    'CampaignLog',
    'EmailMan',
    // 'Prospects',
    'ProspectLists',
    'Groups',
    'InboundEmail',
    'ACLActions',
    'ACLRoles',
    'DocumentRevisions',
    'ProjectTask',
    'ModuleBuilder',
    'Alert',
    'ResourceCalendar',
    'AOBH_BusinessHours',
];

$adminOnlyList = [
    //module => list of actions  (all says all actions are admin only)
    //'Administration' => ['all'=>1, 'SupportPortal'=>'allow'],
    'Dropdown' => ['all' => 1],
    'Dynamic' => ['all' => 1],
    'DynamicFields' => ['all' => 1],
    'Currencies' => ['all' => 1],
    'EditCustomFields' => ['all' => 1],
    'FieldsMetaData' => ['all' => 1],
    'LabelEditor' => ['all' => 1],
    'ACL' => ['all' => 1],
    'ACLActions' => ['all' => 1],
    'ACLRoles' => ['all' => 1],
    'UpgradeWizard' => ['all' => 1],
    'Studio' => ['all' => 1],
    'Schedulers' => ['all' => 1],
];

$modInvisList[] = 'ACL';
$modInvisList[] = 'ACLRoles';
$modInvisList[] = 'Configurator';
$modInvisList[] = 'UserPreferences';
$modInvisList[] = 'SavedSearch';
// deferred
//$modInvisList[] = 'Queues';
$modInvisList[] = 'Studio';
$modInvisList[] = 'Connectors';

$report_include_modules = [];
$report_include_modules['Currencies'] = 'Currency';
//add prospects
// $report_include_modules['Prospects'] = 'Prospect';
$report_include_modules['DocumentRevisions'] = 'DocumentRevision';
// $report_include_modules['ProductCategories'] = 'ProductCategory';
// $report_include_modules['ProductTypes'] = 'ProductType';
//add Tracker modules

$report_include_modules['Trackers'] = 'Tracker';

$beanList['SugarFeed'] = 'SugarFeed';
$beanFiles['SugarFeed'] = 'modules/SugarFeed/SugarFeed.php';
$modInvisList[] = 'SugarFeed';

// This is the mapping for modules that appear under a different module's tab
// Be sure to also add the modules to $modInvisList, otherwise their tab will still appear
$GLOBALS['moduleTabMap'] = [
    'UpgradeWizard' => 'Administration',
    'EmailMan' => 'Administration',
    'ModuleBuilder' => 'Administration',
    'Configurator' => 'Administration',
    'Studio' => 'Administration',
    'Currencies' => 'Administration',
    'SugarFeed' => 'Administration',
    'DocumentRevisions' => 'Documents',
    'EmailMarketing' => 'Campaigns',
];
$beanList['EAPM'] = 'EAPM';
$beanFiles['EAPM'] = 'modules/EAPM/EAPM.php';
$modules_exempt_from_availability_check['EAPM'] = 'EAPM';
$modInvisList[] = 'EAPM';
$beanList['OAuthKeys'] = 'OAuthKey';
$beanFiles['OAuthKey'] = 'modules/OAuthKeys/OAuthKey.php';
$modules_exempt_from_availability_check['OAuthKeys'] = 'OAuthKeys';
$modInvisList[] = 'OAuthKeys';
$beanList['OAuthTokens'] = 'OAuthToken';
$beanFiles['OAuthToken'] = 'modules/OAuthTokens/OAuthToken.php';
$modules_exempt_from_availability_check['OAuthTokens'] = 'OAuthTokens';
$modInvisList[] = 'OAuthTokens';

$beanList['AM_ProjectTemplates'] = 'AM_ProjectTemplates';
$beanFiles['AM_ProjectTemplates'] = 'modules/AM_ProjectTemplates/AM_ProjectTemplates.php';
$moduleList[] = 'AM_ProjectTemplates';
$beanList['AM_TaskTemplates'] = 'AM_TaskTemplates';
$beanFiles['AM_TaskTemplates'] = 'modules/AM_TaskTemplates/AM_TaskTemplates.php';
$modules_exempt_from_availability_check['AM_TaskTemplates'] = 'AM_TaskTemplates';
$report_include_modules['AM_TaskTemplates'] = 'AM_TaskTemplates';
$modInvisList[] = 'AM_TaskTemplates';
$beanList['Favorites'] = 'Favorites';
$beanFiles['Favorites'] = 'modules/Favorites/Favorites.php';

//Object list is only here to correct for modules that break
//the bean class name == dictionary entry/object name convention
//No future module should need an entry here.
$objectList = [];
// $objectList['Cases'] = 'Case';
$objectList['Groups'] = 'User';
$objectList['Users'] = 'User';

// knowledge base
$beanList['AOK_Knowledge_Base_Categories'] = 'AOK_Knowledge_Base_Categories';
$beanFiles['AOK_Knowledge_Base_Categories'] = 'modules/AOK_Knowledge_Base_Categories/AOK_Knowledge_Base_Categories.php';
$moduleList[] = 'AOK_Knowledge_Base_Categories';
$beanList['AOK_KnowledgeBase'] = 'AOK_KnowledgeBase';
$beanFiles['AOK_KnowledgeBase'] = 'modules/AOK_KnowledgeBase/AOK_KnowledgeBase.php';
$moduleList[] = 'AOK_KnowledgeBase';

$beanList['Reminders'] = 'Reminder';
$beanFiles['Reminder'] = 'modules/Reminders/Reminder.php';
$modInvisList[] = 'Reminders';
$modules_exempt_from_availability_check['Reminders'] = 'Reminders';

$beanList['Reminders_Invitees'] = 'Reminder_Invitee';
$beanFiles['Reminder_Invitee'] = 'modules/Reminders_Invitees/Reminder_Invitee.php';
$modInvisList[] = 'Reminders_Invitees';
$modules_exempt_from_availability_check['Reminders_Invitees'] = 'Reminder_Invitee';

$beanList['FP_events'] = 'FP_events';
$beanFiles['FP_events'] = 'modules/FP_events/FP_events.php';
$moduleList[] = 'FP_events';
$beanList['FP_Event_Locations'] = 'FP_Event_Locations';
$beanFiles['FP_Event_Locations'] = 'modules/FP_Event_Locations/FP_Event_Locations.php';
$moduleList[] = 'FP_Event_Locations';

$beanList['AOD_IndexEvent'] = 'AOD_IndexEvent';
$beanFiles['AOD_IndexEvent'] = 'modules/AOD_IndexEvent/AOD_IndexEvent.php';
$modules_exempt_from_availability_check['AOD_IndexEvent'] = 'AOD_IndexEvent';
$report_include_modules['AOD_IndexEvent'] = 'AOD_IndexEvent';
$modInvisList[] = 'AOD_IndexEvent';
$beanList['AOD_Index'] = 'AOD_Index';
$beanFiles['AOD_Index'] = 'modules/AOD_Index/AOD_Index.php';
$modules_exempt_from_availability_check['AOD_Index'] = 'AOD_Index';
$report_include_modules['AOD_Index'] = 'AOD_Index';
$modInvisList[] = 'AOD_Index';

// $beanList['AOP_Case_Events'] = 'AOP_Case_Events';
// $beanFiles['AOP_Case_Events'] = 'modules/AOP_Case_Events/AOP_Case_Events.php';
// $modules_exempt_from_availability_check['AOP_Case_Events'] = 'AOP_Case_Events';
// $report_include_modules['AOP_Case_Events'] = 'AOP_Case_Events';
// $modInvisList[] = 'AOP_Case_Events';
// $beanList['AOP_Case_Updates'] = 'AOP_Case_Updates';
// $beanFiles['AOP_Case_Updates'] = 'modules/AOP_Case_Updates/AOP_Case_Updates.php';
// $modules_exempt_from_availability_check['AOP_Case_Updates'] = 'AOP_Case_Updates';
// $report_include_modules['AOP_Case_Updates'] = 'AOP_Case_Updates';
// $modInvisList[] = 'AOP_Case_Updates';

$beanList['AOR_Reports'] = 'AOR_Report';
$beanFiles['AOR_Report'] = 'modules/AOR_Reports/AOR_Report.php';
$moduleList[] = 'AOR_Reports';
$beanList['AOR_Fields'] = 'AOR_Field';
$beanFiles['AOR_Field'] = 'modules/AOR_Fields/AOR_Field.php';
$modules_exempt_from_availability_check['AOR_Fields'] = 'AOR_Fields';
$report_include_modules['AOR_Fields'] = 'AOR_Fields';
$modInvisList[] = 'AOR_Fields';
$beanList['AOR_Charts'] = 'AOR_Chart';
$beanFiles['AOR_Chart'] = 'modules/AOR_Charts/AOR_Chart.php';
$modules_exempt_from_availability_check['AOR_Charts'] = 'AOR_Charts';
$report_include_modules['AOR_Charts'] = 'AOR_Charts';
$modInvisList[] = 'AOR_Charts';
$beanList['AOR_Conditions'] = 'AOR_Condition';
$beanFiles['AOR_Condition'] = 'modules/AOR_Conditions/AOR_Condition.php';
$modules_exempt_from_availability_check['AOR_Conditions'] = 'AOR_Conditions';
$report_include_modules['AOR_Conditions'] = 'AOR_Conditions';
$modInvisList[] = 'AOR_Conditions';
$beanList['AOR_Scheduled_Reports'] = 'AOR_Scheduled_Reports';
$beanFiles['AOR_Scheduled_Reports'] = 'modules/AOR_Scheduled_Reports/AOR_Scheduled_Reports.php';
$moduleList[] = 'AOR_Scheduled_Reports';

// $beanList['AOS_Contracts'] = 'AOS_Contracts';
// $beanFiles['AOS_Contracts'] = 'modules/AOS_Contracts/AOS_Contracts.php';
// $moduleList[] = 'AOS_Contracts';
// $beanList['AOS_Invoices'] = 'AOS_Invoices';
// $beanFiles['AOS_Invoices'] = 'modules/AOS_Invoices/AOS_Invoices.php';
// $moduleList[] = 'AOS_Invoices';
$beanList['AOS_PDF_Templates'] = 'AOS_PDF_Templates';
$beanFiles['AOS_PDF_Templates'] = 'modules/AOS_PDF_Templates/AOS_PDF_Templates.php';
$moduleList[] = 'AOS_PDF_Templates';
// $beanList['AOS_Product_Categories'] = 'AOS_Product_Categories';
// $beanFiles['AOS_Product_Categories'] = 'modules/AOS_Product_Categories/AOS_Product_Categories.php';
// $moduleList[] = 'AOS_Product_Categories';
// $beanList['AOS_Products'] = 'AOS_Products';
// $beanFiles['AOS_Products'] = 'modules/AOS_Products/AOS_Products.php';
// $moduleList[] = 'AOS_Products';
// $beanList['AOS_Products_Quotes'] = 'AOS_Products_Quotes';
// $beanFiles['AOS_Products_Quotes'] = 'modules/AOS_Products_Quotes/AOS_Products_Quotes.php';
// $modules_exempt_from_availability_check['AOS_Products_Quotes'] = 'AOS_Products_Quotes';
// $report_include_modules['AOS_Products_Quotes'] = 'AOS_Products_Quotes';
// $modInvisList[] = 'AOS_Products_Quotes';
// $beanList['AOS_Line_Item_Groups'] = 'AOS_Line_Item_Groups';
// $beanFiles['AOS_Line_Item_Groups'] = 'modules/AOS_Line_Item_Groups/AOS_Line_Item_Groups.php';
// $modules_exempt_from_availability_check['AOS_Line_Item_Groups'] = 'AOS_Line_Item_Groups';
// $report_include_modules['AOS_Line_Item_Groups'] = 'AOS_Line_Item_Groups';
// $modInvisList[] = 'AOS_Line_Item_Groups';
// $beanList['AOS_Quotes'] = 'AOS_Quotes';
// $beanFiles['AOS_Quotes'] = 'modules/AOS_Quotes/AOS_Quotes.php';
// $moduleList[] = 'AOS_Quotes';

$beanList['AOW_Actions'] = 'AOW_Action';
$beanFiles['AOW_Action'] = 'modules/AOW_Actions/AOW_Action.php';
$modules_exempt_from_availability_check['AOW_Actions'] = 'AOW_Actions';
$report_include_modules['AOW_Actions'] = 'AOW_Actions';
$modInvisList[] = 'AOW_Actions';
$beanList['AOW_WorkFlow'] = 'AOW_WorkFlow';
$beanFiles['AOW_WorkFlow'] = 'modules/AOW_WorkFlow/AOW_WorkFlow.php';
$moduleList[] = 'AOW_WorkFlow';
$beanList['AOW_Processed'] = 'AOW_Processed';
$beanFiles['AOW_Processed'] = 'modules/AOW_Processed/AOW_Processed.php';
$modules_exempt_from_availability_check['AOW_Processed'] = 'AOW_Processed';
$report_include_modules['AOW_Processed'] = 'AOW_Processed';
$modInvisList[] = 'AOW_Processed';
$beanList['AOW_Conditions'] = 'AOW_Condition';
$beanFiles['AOW_Condition'] = 'modules/AOW_Conditions/AOW_Condition.php';
$modules_exempt_from_availability_check['AOW_Conditions'] = 'AOW_Conditions';
$report_include_modules['AOW_Conditions'] = 'AOW_Conditions';
$modInvisList[] = 'AOW_Conditions';

// $beanList['jjwg_Maps'] = 'jjwg_Maps';
// $beanFiles['jjwg_Maps'] = 'modules/jjwg_Maps/jjwg_Maps.php';
// $moduleList[] = 'jjwg_Maps';
// $beanList['jjwg_Markers'] = 'jjwg_Markers';
// $beanFiles['jjwg_Markers'] = 'modules/jjwg_Markers/jjwg_Markers.php';
// $moduleList[] = 'jjwg_Markers';
// $beanList['jjwg_Areas'] = 'jjwg_Areas';
// $beanFiles['jjwg_Areas'] = 'modules/jjwg_Areas/jjwg_Areas.php';
// $moduleList[] = 'jjwg_Areas';
// $beanList['jjwg_Address_Cache'] = 'jjwg_Address_Cache';
// $beanFiles['jjwg_Address_Cache'] = 'modules/jjwg_Address_Cache/jjwg_Address_Cache.php';
// $moduleList[] = 'jjwg_Address_Cache';

$beanList['Calls_Reschedule'] = 'Calls_Reschedule';
$beanFiles['Calls_Reschedule'] = 'modules/Calls_Reschedule/Calls_Reschedule.php';
$modules_exempt_from_availability_check['Calls_Reschedule'] = 'Calls_Reschedule';
$report_include_modules['Calls_Reschedule'] = 'Calls_Reschedule';
$modInvisList[] = 'Calls_Reschedule';

$beanList['SecurityGroups'] = 'SecurityGroup';
$beanFiles['SecurityGroup'] = 'modules/SecurityGroups/SecurityGroup.php';
$moduleList[] = 'SecurityGroups';

$beanList['Kudos'] = 'Kudos';
$beanFiles['Kudos'] = 'modules/Kudos/Kudos.php';
$moduleList[] = 'Kudos';

$beanList['OutboundEmailAccounts'] = 'OutboundEmailAccounts';
$beanFiles['OutboundEmailAccounts'] = 'modules/OutboundEmailAccounts/OutboundEmailAccounts.php';
$modules_exempt_from_availability_check['OutboundEmailAccounts'] = 'OutboundEmailAccounts';
$report_include_modules['OutboundEmailAccounts'] = 'OutboundEmailAccounts';
$modInvisList[] = 'OutboundEmailAccounts';

$beanList['ExternalOAuthConnection'] = 'ExternalOAuthConnection';
$beanFiles['ExternalOAuthConnection'] = 'modules/ExternalOAuthConnection/ExternalOAuthConnection.php';
$modInvisList[] = 'ExternalOAuthConnection';

$beanList['ExternalOAuthProvider'] = 'ExternalOAuthProvider';
$beanFiles['ExternalOAuthProvider'] = 'modules/ExternalOAuthProvider/ExternalOAuthProvider.php';
$modInvisList[] = 'ExternalOAuthProvider';

$beanList['TemplateSectionLine'] = 'TemplateSectionLine';
$beanFiles['TemplateSectionLine'] = 'modules/TemplateSectionLine/TemplateSectionLine.php';
$modules_exempt_from_availability_check['TemplateSectionLine'] = 'TemplateSectionLine';
$report_include_modules['TemplateSectionLine'] = 'TemplateSectionLine';
$modInvisList[] = 'TemplateSectionLine';

$beanList['OAuth2Tokens'] = 'OAuth2Tokens';
$beanFiles['OAuth2Tokens'] = 'modules/OAuth2Tokens/OAuth2Tokens.php';
$modInvisList[] = 'OAuth2Tokens';

$beanList['OAuth2Clients'] = 'OAuth2Clients';
$beanFiles['OAuth2Clients'] = 'modules/OAuth2Clients/OAuth2Clients.php';
$modInvisList[] = 'OAuth2Clients';

$beanList['OAuth2Codes'] = 'OAuth2Codes';
$beanFiles['OAuth2Codes'] = 'modules/OAuth2Codes/OAuth2Codes.php';
$modInvisList[] = 'OAuth2Codes';

$beanList['SurveyResponses'] = 'SurveyResponses';
$beanFiles['SurveyResponses'] = 'modules/SurveyResponses/SurveyResponses.php';
$modules_exempt_from_availability_check['SurveyResponses'] = 'SurveyResponses';
$report_include_modules['SurveyResponses'] = 'SurveyResponses';
$modInvisList[] = 'SurveyResponses';
$beanList['Surveys'] = 'Surveys';
$beanFiles['Surveys'] = 'modules/Surveys/Surveys.php';
$moduleList[] = 'Surveys';
$beanList['SurveyQuestionResponses'] = 'SurveyQuestionResponses';
$beanFiles['SurveyQuestionResponses'] = 'modules/SurveyQuestionResponses/SurveyQuestionResponses.php';
$modules_exempt_from_availability_check['SurveyQuestionResponses'] = 'SurveyQuestionResponses';
$report_include_modules['SurveyQuestionResponses'] = 'SurveyQuestionResponses';
$modInvisList[] = 'SurveyQuestionResponses';
$beanList['SurveyQuestions'] = 'SurveyQuestions';
$beanFiles['SurveyQuestions'] = 'modules/SurveyQuestions/SurveyQuestions.php';
$modules_exempt_from_availability_check['SurveyQuestions'] = 'SurveyQuestions';
$report_include_modules['SurveyQuestions'] = 'SurveyQuestions';
$modInvisList[] = 'SurveyQuestions';
$beanList['SurveyQuestionOptions'] = 'SurveyQuestionOptions';
$beanFiles['SurveyQuestionOptions'] = 'modules/SurveyQuestionOptions/SurveyQuestionOptions.php';
$modules_exempt_from_availability_check['SurveyQuestionOptions'] = 'SurveyQuestionOptions';
$report_include_modules['SurveyQuestionOptions'] = 'SurveyQuestionOptions';
$modInvisList[] = 'SurveyQuestionOptions';

$beanFiles['ReservationsCalendar'] = 'modules/ReservationsCalendar/ReservationsCalendar.php';
$beanFiles['ResponsibilityActivities'] = 'modules/ResponsibilityActivities/ResponsibilityActivities.php';
$beanFiles['Improvements'] = 'modules/Improvements/Improvements.php';
$beanFiles['Conclusions'] = 'modules/Conclusions/Conclusions.php';
$beanFiles['Problems'] = 'modules/Problems/Problems.php';
$beanFiles['Candidates'] = 'modules/Candidates/Candidates.php';
$beanFiles['Candidatures'] = 'modules/Candidatures/Candidatures.php';
$beanFiles['Costs'] = 'modules/Costs/Costs.php';
$beanFiles['Delegations'] = 'modules/Delegations/Delegations.php';
$beanFiles['DelegationsLocale'] = 'modules/DelegationsLocale/DelegationsLocale.php';
$beanFiles['Ideas'] = 'modules/Ideas/Ideas.php';

$beanFiles['Rooms'] = 'modules/Rooms/Rooms.php';
$beanFiles['Allocations'] = 'modules/Allocations/Allocations.php';
$beanFiles['Workplaces'] = 'modules/Workplaces/Workplaces.php';


$beanFiles['Positions'] = 'modules/Positions/Positions.php';
$beanFiles['Recruitments'] = 'modules/Recruitments/Recruitments.php';
$beanFiles['Transportations'] = 'modules/Transportations/Transportations.php';
$beanFiles['EmployeeRoles'] = 'modules/EmployeeRoles/EmployeeRoles.php';
$beanFiles['Benefits'] = 'modules/Benefits/Benefits.php';
$beanFiles['Responsibilities'] = 'modules/Responsibilities/Responsibilities.php';
$beanFiles['SpentTime'] = 'modules/SpentTime/SpentTime.php';
$beanFiles['WorkSchedules'] = 'modules/WorkSchedules/WorkSchedules.php';
$beanFiles['WorkingMonths'] = 'modules/WorkingMonths/WorkingMonths.php';
$beanFiles['NonWorkingDays'] = 'modules/NonWorkingDays/NonWorkingDays.php';
$beanFiles['Resources'] = 'modules/Resources/Resources.php';
$beanFiles['PeriodsOfEmployment'] = 'modules/PeriodsOfEmployment/PeriodsOfEmployment.php';
$beanFiles['Reservations'] = 'modules/Reservations/Reservations.php';
$beanFiles['Contracts'] = 'modules/Contracts/Contracts.php';
$beanFiles['TermsOfEmployment'] = 'modules/TermsOfEmployment/TermsOfEmployment.php';
$beanFiles['Trainings'] = 'modules/Trainings/Trainings.php';
$beanFiles['OnboardingOffboardingElements'] = 'modules/OnboardingOffboardingElements/OnboardingOffboardingElements.php';
$beanFiles['OnboardingTemplates'] = 'modules/OnboardingTemplates/OnboardingTemplates.php';
$beanFiles['OffboardingTemplates'] = 'modules/OffboardingTemplates/OffboardingTemplates.php';
$beanFiles['ExitInterviews'] = 'modules/ExitInterviews/ExitInterviews.php';
$beanFiles['Goals'] = 'modules/Goals/Goals.php';
$beanFiles['Appraisals'] = 'modules/Appraisals/Appraisals.php';
$beanFiles['AppraisalItems'] = 'modules/AppraisalItems/AppraisalItems.php';
$beanFiles['Onboardings'] = 'modules/Onboardings/Onboardings.php';
$beanFiles['Offboardings'] = 'modules/Offboardings/Offboardings.php';
$beanFiles['CompetencyRatings'] = 'modules/CompetencyRatings/CompetencyRatings.php';
$beanFiles['Competencies'] = 'modules/Competencies/Competencies.php';
$beanFiles['CareerPaths'] = 'modules/CareerPaths/CareerPaths.php';
$beanFiles['News'] = 'modules/News/News.php';
$beanFiles['UsersNews'] = 'modules/UsersNews/UsersNews.php';
$beanFiles['Certificates'] = 'modules/Certificates/Certificates.php';
$beanFiles['Applications'] = 'modules/Applications/Applications.php';
$beanFiles['DashboardBackups'] = 'modules/DashboardBackups/DashboardBackups.php';
$beanFiles['DashboardManager'] = 'modules/DashboardManager/DashboardManager.php';
$beanFiles['DashboardHistory'] = 'modules/DashboardHistory/DashboardHistory.php';
$beanFiles['KReport'] = 'modules/KReports/KReport.php';
$beanFiles['ScheduleReports'] = 'modules/ScheduleReports/ScheduleReports.php';
$beanFiles['ScheduleReportsLogs'] = 'modules/ScheduleReportsLogs/ScheduleReportsLogs.php';
$beanFiles['KTemplates'] = 'modules/KTemplates/KTemplates.php';
$beanFiles['PDFGenerator'] = 'modules/PDFGenerator/PDFGenerator.php';
$beanFiles['PDFTemplates'] = 'modules/PDFTemplates/PDFTemplates.php';
//
$beanFiles['Skills'] = 'modules/Skills/Skills.php';
$beanFiles['Attitudes'] = 'modules/Attitudes/Attitudes.php';
$beanFiles['Knowledge'] = 'modules/Knowledge/Knowledge.php';
$beanFiles['SalaryRanges'] = 'modules/SalaryRanges/SalaryRanges.php';
$beanFiles['EmployeeCertificates'] = 'modules/EmployeeCertificates/EmployeeCertificates.php';
$beanFiles['Dictionaries'] = 'modules/Dictionaries/Dictionaries.php';
//
$beanFiles['Comments'] = 'modules/Comments/Comments.php';
$beanFiles['Reactions'] = 'modules/Reactions/Reactions.php';
//

$beanList['Skills'] = 'Skills';
$beanList['Attitudes'] = 'Attitudes';
$beanList['Knowledge'] = 'Knowledge';

$beanList['ReservationsCalendar'] = 'ReservationsCalendar';
$beanList['ResponsibilityActivities'] = 'ResponsibilityActivities';
$beanList['Improvements'] = 'Improvements';
$beanList['Conclusions'] = 'Conclusions';
$beanList['Problems'] = 'Problems';
$beanList['Candidates'] = 'Candidates';
$beanList['Candidatures'] = 'Candidatures';
$beanList['Costs'] = 'Costs';
$beanList['Delegations'] = 'Delegations';
$beanList['DelegationsLocale'] = 'DelegationsLocale';
$beanList['Ideas'] = 'Ideas';
$beanList['Rooms'] = 'Rooms';
$beanList['Allocations'] = 'Allocations';
$beanList['Workplaces'] = 'Workplaces';
$beanList['Positions'] = 'Positions';
$beanList['Recruitments'] = 'Recruitments';
$beanList['Transportations'] = 'Transportations';
$beanList['EmployeeRoles'] = 'EmployeeRoles';
$beanList['Benefits'] = 'Benefits';
$beanList['Responsibilities'] = 'Responsibilities';
$beanList['SpentTime'] = 'SpentTime';
$beanList['WorkSchedules'] = 'WorkSchedules';
$beanList['WorkingMonths'] = 'WorkingMonths';
$beanList['NonWorkingDays'] = 'NonWorkingDays';
$beanList['Resources'] = 'Resources';
$beanList['PeriodsOfEmployment'] = 'PeriodsOfEmployment';
$beanList['Reservations'] = 'Reservations';
$beanList['Contracts'] = 'Contracts';
$beanList['TermsOfEmployment'] = 'TermsOfEmployment';
$beanList['Trainings'] = 'Trainings';
$beanList['OnboardingOffboardingElements'] = 'OnboardingOffboardingElements';
$beanList['OnboardingTemplates'] = 'OnboardingTemplates';
$beanList['OffboardingTemplates'] = 'OffboardingTemplates';
$beanList['ExitInterviews'] = 'ExitInterviews';
$beanList['Goals'] = 'Goals';
$beanList['Appraisals'] = 'Appraisals';
$beanList['AppraisalItems'] = 'AppraisalItems';
$beanList['Onboardings'] = 'Onboardings';
$beanList['Offboardings'] = 'Offboardings';
$beanList['CompetencyRatings'] = 'CompetencyRatings';
$beanList['Competencies'] = 'Competencies';
$beanList['CareerPaths'] = 'CareerPaths';
$beanList['News'] = 'News';
$beanList['UsersNews'] = 'UsersNews';
$beanList['Certificates'] = 'Certificates';
$beanList['Applications'] = 'Applications';
$beanList['DashboardBackups'] = 'DashboardBackups';
$beanList['DashboardManager'] = 'DashboardManager';
$beanList['DashboardHistory'] = 'DashboardHistory';
$beanList['KReports'] = 'KReport';
$beanList['ScheduleReports'] = 'ScheduleReports';
$beanList['ScheduleReportsLogs'] = 'ScheduleReportsLogs';
$beanList['KTemplates'] = 'KTemplates';
$beanList['PDFGenerator'] = 'PDFGenerator';
$beanList['PDFTemplates'] = 'PDFTemplates';
//
$beanList['Skills'] = 'Skills';
$beanList['Attitudes'] = 'Attitudes';
$beanList['Knowledge'] = 'Knowledge';
$beanList['SalaryRanges'] = 'SalaryRanges';
$beanList['EmployeeCertificates'] = 'EmployeeCertificates';
$beanList['Dictionaries'] = 'Dictionaries';
//
$beanList['Comments'] = 'Comments';
$beanList['Reactions'] = 'Reactions';
//
$moduleList[] = 'ReservationsCalendar';
$moduleList[] = 'ResponsibilityActivities';
$moduleList[] = 'Improvements';
$moduleList[] = 'Conclusions';
$moduleList[] = 'Problems';
$moduleList[] = 'Candidates';
$moduleList[] = 'Candidatures';
$moduleList[] = 'Delegations';
$moduleList[] = 'Ideas';
$moduleList[] = 'Rooms';
$moduleList[] = 'Allocations';
$moduleList[] = 'Workplaces';
$moduleList[] = 'Positions';
$moduleList[] = 'Recruitments';
$moduleList[] = 'EmployeeRoles';
$moduleList[] = 'Benefits';
$moduleList[] = 'Responsibilities';
$moduleList[] = 'WorkSchedules';
$moduleList[] = 'WorkingMonths';
$moduleList[] = 'NonWorkingDays';
$moduleList[] = 'Resources';
$moduleList[] = 'PeriodsOfEmployment';
$moduleList[] = 'Reservations';
$moduleList[] = 'Contracts';
$moduleList[] = 'TermsOfEmployment';
$moduleList[] = 'Trainings';
$moduleList[] = 'OnboardingOffboardingElements';
$moduleList[] = 'OnboardingTemplates';
$moduleList[] = 'OffboardingTemplates';
$moduleList[] = 'ExitInterviews';
$moduleList[] = 'Goals';
$moduleList[] = 'Appraisals';
$moduleList[] = 'Onboardings';
$moduleList[] = 'Offboardings';
$moduleList[] = 'Competencies';
$moduleList[] = 'News';
$moduleList[] = 'Certificates';
$moduleList[] = 'Applications';
$moduleList[] = 'KReports';
$moduleList[] = 'KTemplates';
$moduleList[] = 'PDFTemplates';
$moduleList[] = 'ScheduleReports';
$moduleList[] = 'ScheduleReportsLogs';
//
$moduleList[] = 'Skills';
$moduleList[] = 'Attitudes';
$moduleList[] = 'Knowledge';
$moduleList[] = 'SalaryRanges';
$moduleList[] = 'EmployeeCertificates';
$moduleList[] = 'Employees';  // MintHCM #116668
//
$beanList['Files'] = 'Files';
$beanFiles['Files'] = 'modules/Files/Files.php';
$moduleList[] = 'Files';

$beanList['Requests'] = 'Requests';
$beanFiles['Requests'] = 'modules/Requests/Requests.php';
$moduleList[] = 'Requests';

$modInvisListApiExceptions = [
    'Employees',
];

if (file_exists('include/modules_override.php')) {
    include 'include/modules_override.php';
}
if (file_exists('custom/application/Ext/Include/modules.ext.php')) {
    include 'custom/application/Ext/Include/modules.ext.php';
}
