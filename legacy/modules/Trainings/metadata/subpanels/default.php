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

$module_name = 'Trainings';
$subpanel_layout = array(
    'top_buttons' => array(
        array(
            'widget_class' => 'SubPanelTopCreateButton',
        ),
        array(
            'widget_class' => 'SubPanelTopSelectButton',
            'popup_module' => 'Trainings',
        ),
    ),
    'where' => '',
    'list_fields' => array(
        'name' => array(
            'vname' => 'LBL_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'width' => '25%',
            'default' => true,
        ),
        'status' => array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'vname' => 'LBL_STATUS',
            'width' => '10%',
        ),
        'date_start' => array(
            'type' => 'datetimecombo',
            'vname' => 'LBL_DATE_START',
            'width' => '10%',
            'default' => true,
        ),
        'date_end' => array(
            'type' => 'datetimecombo',
            'vname' => 'LBL_DATE_END',
            'width' => '10%',
            'default' => true,
        ),
        'training_type' => array(
            'type' => 'enum',
            'default' => true,
            'studio' => 'visible',
            'vname' => 'LBL_TRAINING_TYPE',
            'width' => '10%',
        ),
        'parent_name' => array(
            'vname' => 'LBL_PARENT_NAME',
            'width' => '10%',
            'id' => 'parent_id',
            'widget_class' => 'SubPanelDetailViewLink',
            'target_record_key' => 'parent_id',
            'target_module_key' => 'parent_type',
            'related_fields' => array(
                'parent_id',
                'parent_type',
            ),
            'default' => true,
        ),
        'assigned_user_name' => array(
            'name' => 'assigned_user_name',
            'vname' => 'LBL_ASSIGNED_TO_NAME',
            'widget_class' => 'SubPanelDetailViewLink',
            'target_record_key' => 'assigned_user_id',
            'target_module' => 'Users',
            'related_fields' => array(
                'assigned_user_id',
            ),
            'width' => '9%',
            'default' => true,
        ),
        'assigned_user_id' => array(
            'usage' => 'query_only',
        ),
        'parent_id' => array(
            'usage' => 'query_only',
        ),
        'parent_type' => array(
            'usage' => 'query_only',
        ),
        'edit_button' => array(
            'vname' => 'LBL_EDIT_BUTTON',
            'widget_class' => 'SubPanelEditButton',
            'module' => 'Trainings',
            'width' => '4%',
            'default' => true,
        ),
        'remove_button' => array(
            'vname' => 'LBL_REMOVE',
            'widget_class' => 'SubPanelRemoveButton',
            'module' => 'Trainings',
            'width' => '5%',
            'default' => true,
        ),
        'close_button'=>array(
			'widget_class' => 'SubPanelCloseButton',
			'vname' => 'LBL_LIST_CLOSE',
			'width' => '6%',
			'sortable'=>false,
		),
    ),
);
