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

$module_name = 'WorkSchedules';
$viewdefs[$module_name] = array(
    'DetailView' => array(
        'templateMeta' => array(
            'form' => array(
                'buttons' => array(
                    'EDIT',
                    'DUPLICATE',
                    'DELETE',
                    'FIND_DUPLICATES',
                    array(
                        'customCode' => '{include file="modules/WorkSchedules/tpls/CloseButton.tpl"}'
                        . '{include file="modules/WorkSchedules/tpls/AcceptWorkPlanButton.tpl"}'
                        . '{include file="modules/WorkSchedules/tpls/UndoAcceptanceButton.tpl"}',
                    ),
                ),
                'hidden' => array(
                    '<input type="hidden" name="current_user_is_admin" id="current_user_is_admin" value="{$CURRENT_USER_IS_ADMIN}">',
                ),
            ),
            'maxColumns' => '2',
            'widths' => array(
                array(
                    'label' => '10',
                    'field' => '30',
                ),
                array(
                    'label' => '10',
                    'field' => '30',
                ),
            ),
            'useTabs' => true,
            'tabDefs' => array(
                'DEFAULT' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
                'LBL_PANEL_ASSIGNMENT' => array(
                    'newTab' => true,
                    'panelDefault' => 'expanded',
                ),
            ),
            'includes' => array(
                array(
                    'file' => 'include/javascript/moment.min.js',
                ),
                array(
                    'file' => 'modules/WorkSchedules/js/detail.js',
                ),
                array(
                    'file' => 'modules/WorkSchedules/tpls/TimeTrackingPane.js',
                ),
            ),
        ),
        'panels' => array(
            'default' => array(
                array(
                    'name',
                    'assigned_user_name',
                ),
                array(
                    array(
                        'name' => 'type',
                        'studio' => 'visible',
                        'label' => 'LBL_TYPE',
                    ),
                    array(
                        'name' => 'status',
                        'studio' => 'visible',
                        'label' => 'LBL_STATUS',
                    ),
                ),
                array(
                    array(
                        'name' => 'comments',
                        'label' => 'LBL_COMMENTS',
                    ),
                    array(
                        'name' => 'occasional_leave_type',
                        'label' => 'LBL_OCCASIONAL_LEAVE_TYPE',
                    ),
                ),
                array(
                    array(
                        'name' => 'date_start',
                        'label' => 'LBL_DATE_START',
                    ),
                    array(
                        'name' => 'date_end',
                        'label' => 'LBL_DATE_END',
                    ),
                ),
                array(
                    '',
                    array(
                        'label' => 'LBL_DURATION',
                        'customCode' => '{$fields.duration_hours.value} <span class="dateFormat">{$MOD.LBL_HOURS_HOURS}</span>&nbsp;{$fields.duration_minutes.value} <span class="dateFormat">{$MOD.LBL_HOURS_MINUTES}</span>',
                    ),
                ),
                array(
                    'delegation_name',
                    '',
                ),
                array(
                    array(
                        'name' => 'supervisor_acceptance',
                        'studio' => 'visible',
                        'label' => 'LBL_SUPERVISOR_ACCEPTANCE',
                    ),
                    '',
                ),
                array(
                    'delegation_duration',
                ),
                array(
                    'description',
                ),
            ),
            '' => array(
                array(
                    array(
                        'name' => 'time_tracking_pane',
                        'customCode' => '{include file="modules/WorkSchedules/tpls/TimeTrackingPane.tpl"}',
                    ),
                ),
            ),
            'LBL_PANEL_ASSIGNMENT' => array(
                array(
                    array(
                        'name' => 'date_entered',
                        'customCode' => '{$fields.date_entered.value} {$APP.LBL_BY} {$fields.created_by_name.value}',
                    ),
                    array(
                        'name' => 'date_modified',
                        'label' => 'LBL_DATE_MODIFIED',
                        'customCode' => '{$fields.date_modified.value} {$APP.LBL_BY} {$fields.modified_by_name.value}',
                    ),
                ),
            ),
        ),
    ),
);
