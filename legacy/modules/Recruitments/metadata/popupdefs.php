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

$popupMeta = array(
   'moduleMain' => 'Recruitments',
   'varName' => 'Recruitments',
   'orderBy' => 'recruitments.name',
   'whereClauses' => array(
      'name' => 'recruitments.name',
      'position_name' => 'recruitments.position_name',
      'project_status' => 'recruitments.project_status',
      'start_date' => 'recruitments.start_date',
      'start_work_date' => 'recruitments.start_work_date',
      'end_date' => 'recruitments.end_date',
      'assigned_user_id' => 'recruitments.assigned_user_id',
      'favorites_only' => 'recruitments.favorites_only',
      'vacancy' => 'recruitments.vacancy',
   ),
   'searchInputs' => array(
      'name',
      'position_name',
      'project_status',
      'start_date',
      'start_work_date',
      'end_date',
      'assigned_user_id',
      'favorites_only',
      'vacancy',
   ),
   'searchdefs' => array(
      'name' =>
      array(
         'name' => 'name',
      ),
      'position_name' => array(
         'type' => 'relate',
         'link' => true,
         'label' => 'LBL_RECRUITMENTS_POSITIONS_FROM_POSITIONS_TITLE',
         'id' => 'position_id',
         'sortable' => false,
         'name' => 'position_name',
      ),
      'project_status' => array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_PROJECT_STATUS',
         'name' => 'project_status',
      ),
      'start_date' => array(
         'type' => 'date',
         'label' => 'LBL_START_DATE',
         'name' => 'start_date',
      ),
      'start_work_date' => array(
         'type' => 'date',
         'label' => 'LBL_START_WORK_DATE',
         'name' => 'start_work_date',
      ),
      'end_date' => array(
         'type' => 'date',
         'label' => 'LBL_END_DATE',
         'name' => 'end_date',
      ),
      'assigned_user_id' => array(
         'name' => 'assigned_user_id',
         'label' => 'LBL_ASSIGNED_TO',
         'type' => 'enum',
         'function' =>
         array(
            'name' => 'get_user_array',
            'params' =>
            array(
               false,
            ),
         ),
      ),
      'favorites_only' => array(
         'name' => 'favorites_only',
         'label' => 'LBL_FAVORITES_FILTER',
         'type' => 'bool',
      ),
      'vacancy' => array(
         'type' => 'int',
         'label' => 'LBL_VACANCY',
         'name' => 'vacancy',
      ),
   ),
   'listviewdefs' => array(
      'NAME' =>
      array(
         'label' => 'LBL_NAME',
         'default' => true,
         'link' => true,
         'name' => 'name',
      ),
      'POSITION_NAME' => array(
         'type' => 'relate',
         'link' => true,
         'label' => 'LBL_RECRUITMENTS_POSITIONS_FROM_POSITIONS_TITLE',
         'id' => 'position_id',
         'sortable' => false,
         'default' => true,
         'name' => 'position_name',
      ),
      'PROJECT_STATUS' => array(
         'type' => 'enum',
         'default' => true,
         'studio' => 'visible',
         'label' => 'LBL_PROJECT_STATUS',
         'name' => 'project_status',
      ),
      'START_DATE' => array(
         'type' => 'date',
         'default' => true,
         'label' => 'LBL_START_DATE',
         'name' => 'start_date',
      ),
      'END_DATE' => array(
         'type' => 'date',
         'default' => true,
         'label' => 'LBL_END_DATE',
         'name' => 'end_date',
      ),
      'ASSIGNED_USER_ID' => array(
         'type' => 'relate',
         'label' => 'LBL_ASSIGNED_TO_ID',
         'id' => 'ASSIGNED_USER_ID',
         'link' => true,
         'sortable' => false,
         'default' => true,
      ),
      'VACANCY' => array(
         'type' => 'int',
         'default' => true,
         'label' => 'LBL_VACANCY',
      ),
   ),
);
