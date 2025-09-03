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


$layout_defs['ProspectLists'] = array(
    // list of what Subpanels to show in the DetailView
    'subpanel_setup' => array(
        'prospects' => array(
            'order' => 10,
            'sort_by' => 'last_name',
            'sort_order' => 'asc',
            'module' => 'Prospects',
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'prospects',
            'title_key' => 'LBL_PROSPECTS_SUBPANEL_TITLE',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopButtonQuickCreate'),
                array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect'),
            ),
        ),
        'contacts' => array(
            'order' => 20,
            'module' => 'Contacts',
            'sort_by' => 'last_name, first_name',
            'sort_order' => 'asc',
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'contacts',
            'title_key' => 'LBL_CONTACTS_SUBPANEL_TITLE',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopButtonQuickCreate'),
                array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect'),
            ),
        ),
        'employees' => array(
            'order' => 40,
            'module' => 'Employees',
            'sort_by' => 'name',
            'sort_order' => 'asc',
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'employees',
            'title_key' => 'LBL_EMPLOYEES',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect'),
            ),
        ),
        'candidates' => array(
            'order' => 40,
            'module' => 'Candidates',
            'sort_order' => 'asc',
            'sort_by' => 'name',
            'subpanel_name' => 'default',
            'get_subpanel_data' => 'candidates',
            'title_key' => 'LBL_CANDIDATES',
            'top_buttons' => array(
                array('widget_class' => 'SubPanelTopSelectButton', 'mode' => 'MultiSelect'),
            ),
        ),
        'securitygroups' => array(
            'top_buttons' => array(array('widget_class' => 'SubPanelTopSelectButton', 'popup_module' => 'SecurityGroups', 'mode' => 'MultiSelect'),),
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
        'news' => array(
            'order' => 100,
            'module' => 'News',
            'subpanel_name' => 'default',
            'sort_order' => 'asc',
            'sort_by' => 'publication_date',
            'title_key' => 'LBL_NEWS',
            'get_subpanel_data' => 'news',
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
    ),
);
