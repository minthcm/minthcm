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

$module_name = 'SalaryRanges';
$searchdefs[$module_name] = array(
    'layout' => array(
        'basic_search' => array(
            0 => array(
                'name' => 'position_name',
                'label' => 'LBL_POSITION_NAME',
                'type' => 'relate',
            ),

        ),
        'advanced_search' => array(
            'name',
            'position_name' => array(
                'label' => 'LBL_POSITION_NAME',
                'type' => 'relate',
                'width' => '10%',
                'default' => true,
                'name' => 'position_name',
            ),
            'start_date' => array(
                'label' => 'LBL_START_DATE',
                'type' => 'date',
                'width' => '10%',
                'default' => true,
                'name' => 'start_date',
            ),
            'end_date' => array(
                'label' => 'LBL_END_DATE',
                'type' => 'date',
                'width' => '10%',
                'default' => true,
                'name' => 'end_date',
            ),
            'date_modified' => array(
                'label' => 'LBL_DATE_MODIFIED',
                'type' => 'date',
                'width' => '10%',
                'default' => true,
                'name' => 'date_modified',
            ),
            'created_by' => array(
                'label' => 'LBL_CREATED_USER',
                'type' => 'assigned_user_name',
                'width' => '10%',
                'default' => true,
                'name' => 'created_by',
            ),
            'date_entered'=>array(
                'label' => 'LBL_DATE_ENTERED',
                'type' => 'date',
                'width' => '10%',
                'default' => true,
                'name' => 'date_entered', 
            ),
            'modified_user_id' => array(
                'type' => 'assigned_user_name',
                'label' => 'LBL_MODIFIED',
                'width' => '10%',
                'default' => true,
                'name' => 'modified_user_id',
            ),

        ),
    ),
    'templateMeta' => array(
        'maxColumns' => '3',
        'maxColumnsBasic' => '4',
        'widths' => array(
            'label' => '10',
            'field' => '30',
        ),
    ),
);
