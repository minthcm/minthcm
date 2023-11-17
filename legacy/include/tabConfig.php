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
$GLOBALS['tabStructure'] = array(
    'LBL_NAV_COMPANY_LIFE' => array(
        'label' => 'LBL_NAV_COMPANY_LIFE',
        'modules' => array(
            0 => 'Home',
            1 => 'Calendar',
            2 => 'News',
            3 => 'Applications',
            4 => 'FP_events',
            5 => 'Project',
            6 => 'AM_ProjectTemplates',
            7 => 'Ideas',
            8 => 'Improvements',
            9 => 'Conclusions',
            10 => 'Problems',
            11 => 'Notes',
            12 => 'Documents',
            13 => 'ResourceCalendar',
            14 => 'ReservationsCalendar',
            15 => 'SecurityGroups',
            16 => 'FP_Event_Locations',
            17 => 'AOK_KnowledgeBase',
            18 => 'AOK_Knowledge_Base_Categories',
            19 => 'AOR_Reports',
            20 => 'KReports',
        ),
    ),
    'LBL_NAV_EMPLOYEE' => array(
        'label' => 'LBL_NAV_EMPLOYEE',
        'modules' => array(
            0 => 'Goals',
            1 => 'Appraisals',
            2 => 'Trainings',
            3 => 'Delegations',
            4 => 'Benefits',
            5 => 'Contracts',
            6 => 'PeriodsOfEmployment',
            7 => 'TermsOfEmployment',
            8 => 'Certificates',
            9 => 'Documents',
        ),
    ),
    'LBL_NAV_TIME_TRACKING' => array(
        'label' => 'LBL_NAV_TIME_TRACKING',
        'modules' => array(
            0 => 'Calendar',
            1 => 'WorkSchedules',
            2 => 'Calls',
            3 => 'Meetings',
            4 => 'Tasks',
            5 => 'Notes',
            6 => 'Emails',
            7 => 'Trainings',
            8 => 'Reservations',
            9 => 'FP_events',
            10 => 'ReservationsCalendar',
            11 => 'Delegations',
            12 => 'AOR_Reports',
            13 => 'KReports',
        ),
    ),
    'LBL_NAV_RECRUITMENT_EB' => array(
        'label' => 'LBL_NAV_RECRUITMENT_EB',
        'modules' => array(
            0 => 'Recruitments',
            1 => 'Candidates',
            2 => 'Candidatures',
            3 => 'Onboardings',
            4 => 'Offboardings',
            5 => 'SecurityGroups',
            6 => 'Positions',
            7 => 'Campaigns',
            8 => 'Prospects',
            9 => 'ProspectLists',
            10 => 'Documents',
            11 => 'Contracts',
            12 => 'PeriodsOfEmployment',
            13 => 'TermsOfEmployment',
            14 => 'Delegations',
            15 => 'Benefits',
            16 => 'OnboardingTemplates',
            17 => 'OffboardingTemplates',
            18 => 'OnboardingOffboardingElements',
            19 => 'ExitInterviews',
            20 => 'Appraisals',
            21 => 'Goals',
            22 => 'AOR_Reports',
            23 => 'Certificates',
            24 => 'KReports',
        ),
    ),
    'LBL_NAV_DEFINITIONS' => array(
        'label' => 'LBL_NAV_DEFINITIONS',
        'modules' => array(
            0 => 'SecurityGroups',
            1 => 'Positions',
            2 => 'Responsibilities',
            3 => 'EmployeeRoles',
            4 => 'Competencies',
            5 => 'ResponsibilityActivities',
            6 => 'Certificates',
            7 => 'AOK_Knowledge_Base_Categories',
            8 => 'FP_Event_Locations',
        ),
    ),
);

if (file_exists('custom/include/tabConfig.php')) {
    require 'custom/include/tabConfig.php';
}
