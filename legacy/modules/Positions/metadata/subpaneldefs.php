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

$layout_defs["Positions"]["subpanel_setup"] = array(
    'employees' => array(
        'order' => 50,
        'module' => 'Employees',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_EMPLOYEES',
        'get_subpanel_data' => 'employees',
        'top_buttons' => array(
            array(
                'widget_class' => 'SubPanelTopSelectButton',
                'mode' => 'MultiSelect',
            ),
        ),
    ),
    'recruitments' => array(
        'order' => 100,
        'module' => 'Recruitments',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_RECRUITMENTS_POSITIONS_FROM_RECRUITMENTS_TITLE',
        'get_subpanel_data' => 'recruitments',
        'top_buttons' => array(
            array(
                'widget_class' => 'SubPanelTopSelectButton',
                'mode' => 'MultiSelect',
            ),
            array(
                'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
        ),
    ),
    'securitygroups_membership' => array(
        'order' => 100,
        'module' => 'SecurityGroups',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_SECURITYGROUPS_POSITIONS_MEMBERSHIP',
        'get_subpanel_data' => 'securitygroups_membership',
        'top_buttons' => array(
            array(
                'widget_class' => 'SubPanelTopSelectButton',
                'mode' => 'MultiSelect',
            ),
            array(
                'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
        ),
    ),
    'positions_supervision_left' => array(
        'order' => 200,
        'module' => 'Positions',
        'subpanel_name' => 'for_Positions',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_POSITIONS_POSITIONS_SUPERVISION_LEFT',
        'get_subpanel_data' => 'positions_supervision_left',
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
    'documents' => array(
        'order' => 300,
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
    'benefits' => array(
        'order' => 400,
        'module' => 'Benefits',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_BENEFITS_SUBPANEL_FOR_POSITIONS',
        'get_subpanel_data' => 'benefits',
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
    'responsibilities' => array(
        'order' => 400,
        'module' => 'Responsibilities',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_RESPONSIBILITIES_SUBPANEL_FOR_POSITIONS',
        'get_subpanel_data' => 'responsibilities',
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
    'competencyratings' => array(
        'order' => 100,
        'module' => 'CompetencyRatings',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_COMPETENCYRATINGS_POSITIONS_TITLE',
        'get_subpanel_data' => 'competencyratings',
        'top_buttons' => array(
            array(
                'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
        ),
    ),
    'appraisals' => array(
        'order' => 100,
        'module' => 'Appraisals',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_POSITIONS_APPRAISALS',
        'get_subpanel_data' => 'appraisals',
        'top_buttons' => array(
            array(
                'widget_class' => 'SubPanelTopSelectButton',
                'mode' => 'MultiSelect',
            ),
        ),
    ),
    'careerpaths_from' => array(
        'order' => 100,
        'module' => 'CareerPaths',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_POSITIONS_CAREERPATHS_FROM_TITLE',
        'get_subpanel_data' => 'careerpaths_from',
        'top_buttons' => array(
            array(
                'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
        ),
    ),
    'termsofemployment' => array(
        'order' => 400,
        'module' => 'TermsOfEmployment',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_TERMSOFEMPLOYMENT_SUBPANEL_TITLE',
        'get_subpanel_data' => 'termsofemployment',
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
    'salaryranges' => array(
        'order' => 50,
        'module' => 'SalaryRanges',
        'subpanel_name' => 'default',
        'sort_order' => 'asc',
        'sort_by' => 'id',
        'title_key' => 'LBL_SALARYRANGE_SUBPANEL_TITLE',
        'get_subpanel_data' => 'salaryranges',
        'top_buttons' => array(
            array(
                'widget_class' => 'SubPanelTopButtonQuickCreate',
            ),
        ),
    ),
    'securitygroups' => array(
        'top_buttons' => array(array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'SecurityGroups', 'mode' => 'MultiSelect')),
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
    'files' => array(
        'order' => 100,
        'module' => 'Files',
        'subpanel_name' => 'default',
        'title_key' => 'LBL_FILES',
        'get_subpanel_data' => 'files',
        'dropzone' => true
    ),
);
