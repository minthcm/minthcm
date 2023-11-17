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

global $app_strings;

$themedef = array(
    'name' => 'MintHCM',
    'description' => 'MintHCM Responsive Theme',
    'version' => array(
        'regex_matches' => array('.+'),
    ),
    'group_tabs' => true,
    'classic' => true,
    'configurable' => true,
    'config_options' => array(
        'display_sidebar' => array(
            'vname' => 'LBL_DISPLAY_SIDEBAR',
            'type' => 'bool',
            'default' => true,
        ),
        'sub_themes' => array(
            'vname' => 'LBL_SUBTHEME_OPTIONS',
            'type' => 'select',
            'default' => 'Mint',
        ),
    ),
    'fa_module_icons' => array(
        'candidates' => 'fa-address-book',
        'candidatures' => 'fa-address-card',
        'positions' => 'fa-list',
        'recruitments' => 'fa-user-plus',
        'onboardings' => 'fa-sign-in-alt',
        'offboardings' => 'fa-sign-out-alt',
        'offboardingtemplates' => 'fa-sign-out-alt',
        'onboardingtemplates' => 'fa-sign-in-alt',
        'exitinterviews' => 'fa-user-times',
        'delegations' => 'fa-plane',
        'workschedules' => 'fa-business-time',
        'transportations' => 'fa-car',
        'costs' => 'fa-dollar-sign',
        'reservations' => 'fa-calendar-check',
        'resources' => 'fa-people-carry',
        'trainings' => 'fa-user-graduate',
        'employeeroles' => 'fa-user-shield',
        'news' => 'fa-bell',
        'ideas' => 'fa-lightbulb',
        'conclusions' => 'fa-hand-point-left',
        'problems' => 'fa-exclamation-triangle',
        'responsibilities' => 'fa-list',
        'activities' => 'fa-hand-point-right',
        'competencies' => 'fa-list',
        'contracts' => 'fa-file-signature',
        'termsofemployment' => 'fa-list',
        'periodsofemployment' => 'fa-calendar',
        'benefits' => 'fa-umbrella-beach',
        'applications' => 'fa-user-plus',
        'certificates' => 'fa-scroll',
        'employeecertificates' => 'fa-scroll',
        'appraisals' => 'fa-medal',
        'appraisalitems' => 'fa-medal',
        'goals' => 'fa-bullseye',
        'competencyratings' => 'fa-star-half-alt',
        'employees' => 'fa-user-tie',
        'careerpaths' => 'fa-project-diagram',
        'onboardingoffboardingelements' => 'fa-plus-square',
        'spenttime' => 'fa-clock',
        'responsibilityactivities' => 'fa-list',
        'delegationslocale' => 'fa-globe-africa',
        'attitudes' => 'fa-brain',
        'improvements' => 'fa-cogs',
        'knowledge' => 'fa-book',
        'skills' => 'fa-magic',
        'workingmonths' => 'fa-calendar-week',
        'salaryranges' => 'fa-dollar-sign',
        'dictionaries' => 'fa-list',
        'kreports' => 'fa-chart-bar',
        'rooms' => 'fa-door-open',
        'allocations' => 'fa-sign-in-alt',
        'workplaces' => 'fa-pencil-ruler',
        'files' => 'fa-file',
    ),
);


if (!empty($app_strings['LBL_SUBTHEMES'])) {
    // if statement removes the php notice
    $themedef['config_options']['sub_themes']['options'] = array(
        $app_strings['LBL_SUBTHEMES'] => array(
            'Mint' => 'Mint',
        ),
    );
    $themedef['config_options']['sub_themes']['default'] = 'Mint';
}

$custom_files_path = 'custom/themes/SuiteP/themedefs';
if (is_dir($custom_files_path))
{
    $custom_files = scandir($custom_files_path);
    foreach($custom_files as $file_name){
        preg_match('/^themedef[.]\w*\.php/', $file_name, $matches);
        if(isset($matches[0])){
            include "$custom_files_path/$file_name";
        }
    }
}
