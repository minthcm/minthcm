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
 * Free Software Foundation wih the addition of the following permission added
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

$module_name = 'SpentTime';
$viewdefs[$module_name] = array(
    'EditView' => array(
        'templateMeta' => array(
            'form' => array(
                'hidden' => array(
                    '<input type="hidden" id="projecttask_issue_tracker" name="projecttask_issue_tracker" value="{$fields.projecttask_issue_tracker.value}" />',
                    '<input type="hidden" name="current_user_is_admin" id="current_user_is_admin" value="{$CURRENT_USER_IS_ADMIN}">',
                    '<input type="hidden" name="work_date" id="work_date" value="{$fields.work_date.value}">',
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
            'includes' => array(
                array(
                    'file' => 'include/javascript/moment.min.js',
                ),
                array(
                    'file' => 'modules/SpentTime/js/view.edit.js',
                ),
            ),
        ),
        'panels' => array(
            'default' => array(
                array(
                    'employee_name',
                    'assigned_user_name',
                ),
                array(
                    array(
                        'name' => 'workschedule_name',
                        'displayParams' => array(
                            'field_to_name_array' => array(
                                'id' => 'workschedule_id',
                                'name' => 'workschedule_name',
                                'date_start' => 'work_date',
                            ),
                            'call_back_function' => 'set_return_overload',
                        ),
                    ),
                    '',
                ),
                array(
                    'spent_time',
                    'category',
                ),
                array(
                    array(
                        'name' => 'date_start',
                        'displayParams' => array(
                            'minutesStep' => 5,
                        ),
                    ),
                    array(
                        'name' => 'date_end',
                        'displayParams' => array(
                            'minutesStep' => 5,
                        ),
                    ),
                ),
                array(
                    'description',
                ),
            ),
        ),
    ),
);
