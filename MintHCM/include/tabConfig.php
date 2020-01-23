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
 * Copyright (C) 2018-2019 MintHCM
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
    'LBL_NAV_RECRUITMENT_EB' => array(
        'label' => 'LBL_NAV_RECRUITMENT_EB',
        'modules' => array(
            'Candidates',
            'Candidatures',
            'Positions',
            'Recruitments',
            'Onboardings',
            'Offboardings',
            'OnboardingOffboardingElements',
            'OffboardingTemplates',
            'OnboardingTemplates',
            'ExitInterviews',
            'Documents',
        ),
    ),
    'LBL_NAV_TIME_TRACKING' => array(
        'label' => 'LBL_NAV_TIME_TRACKING',
        'modules' => array(
            'Calendar',
            'ReservationsCalendar',
            'WorkSchedules',
            'Notes',
            'Emails',
            'Trainings',
            'Reservations',
            'Resources',
            'Delegations',
            'Meetings',
            'Calls',
            'Tasks',
        ),
    ),
    'LBL_NAV_COMPANY_LIFE' => array(
        'label' => 'LBL_NAV_COMPANY_LIFE',
        'modules' => array(
            'EmployeeRoles',
            'OrganizationalUnits',
            'News',
            'Ideas',
            'KReports',
            'AM_ProjectTemplates',
            'FP_events',
            'Campaigns',
            'Project',
            'FP_Event_Locations',
        ),
    ),
    'LBL_NAV_EMPLOYEE' => array(
        'label' => 'LBL_NAV_EMPLOYEE',
        'modules' => array(
            'Conclusions',
            'Problems',
            'Improvements',
            'Responsibilities',
            'ResponsibilityActivities',
            'Competencies',
            'Contracts',
            'TermsOfEmployment',
            'PeriodsOfEmployment',
            'Benefits',
            'Applications',
            'Certificates',
            'Appraisals',
            'Goals',
        ),
    ),
);

if (file_exists('custom/include/tabConfig.php')) {
    require 'custom/include/tabConfig.php';
}
