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
$layout_defs["Candidates"]["subpanel_setup"] = array(
    'candidatures' => array(
        'order' => 100,
        'module' => 'Candidatures',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_CANDIDATURES_TITLE',
        'get_subpanel_data' => 'candidatures',
        'top_buttons' => array(
            array(
                'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
        ),
    ),
    'meetings' => array(
        'order' => 100,
        'module' => 'Meetings',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_MEETINGS_TITLE',
        'get_subpanel_data' => 'meetings',
        'top_buttons' => array(
            array(
                'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
        ),
    ),
    'tasks' => array(
        'order' => 100,
        'module' => 'Tasks',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_TASKS_TITLE',
        'get_subpanel_data' => 'tasks',
        'top_buttons' => array(
            array(
                'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
        ),
    ),
    'calls' => array(
        'order' => 100,
        'module' => 'Calls',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_CALLS',
        'get_subpanel_data' => 'calls',
        'top_buttons' => array(
            array(
                'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
        ),
    ),
    'notes' => array(
        'order' => 100,
        'module' => 'Notes',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_NOTES_TITLE',
        'get_subpanel_data' => 'notes',
        'top_buttons' => array(
            array(
                'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
        ),
    ),
    'emails' => array(
        'order' => 100,
        'module' => 'Emails',
        'subpanel_name' => 'ForHistory',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_EMAILS_TITLE',
        'get_subpanel_data' => 'emails',
        'top_buttons' => array(
            array(
                'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
        ),
    ),
    'documents' => array(
        'order' => 25,
        'module' => 'Documents',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_DOCUMENTS',
        'get_subpanel_data' => 'documents',
        'top_buttons' => array(
            array(
                'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
            array(
                'widget_class' => 'SubPanelTopSelectButton',
                'mode' => 'MultiSelect',
            ),
        ),
    ),
    'employeecertificates' => array(
        'order' => 100,
        'module' => 'EmployeeCertificates',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_EMPLOYEECERTIFICATES',
        'get_subpanel_data' => 'employeecertificates',
        'top_buttons' => array(
            array(
                'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
        ),
    ),
    'securitygroups' => array(
        'top_buttons' => array(array('widget_class' => 'SubPanelTopSelectButton',
            'popup_module' => 'SecurityGroups', 'mode' => 'MultiSelect')),
        'order' => 900,
        'sort_by' => 'name',
        'sort_order' => 'asc',
        'module' => 'SecurityGroups',
        'refresh_page' => 1,
        'subpanel_name' => 'default',
        'get_subpanel_data' => 'SecurityGroups',
        'add_subpanel_data' => 'securitygroup_id',
        'title_key' => 'LBL_SECURITYGROUPS_SUBPANEL_TITLE',
    ),
);
