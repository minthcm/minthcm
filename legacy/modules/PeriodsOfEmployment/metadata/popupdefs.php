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

if ( !defined('sugarEntry') || !sugarEntry ) {
   die('Not A Valid Entry Point');
}

$module_name = 'PeriodsOfEmployment';
$object_name = 'PeriodsOfEmployment';
$_module_name = 'periodsofemployment';
$popupMeta = array(
   'moduleMain' => $module_name,
   'varName' => $object_name,
   'orderBy' => $_module_name . '.name',
   'whereClauses' => array(
      'name' => $_module_name . '.name',
      'date_modified' => $_module_name . '.date_modified',
      'assigned_user_id' => $_module_name . '.assigned_user_id',
      'date_entered' => $_module_name . '.date_entered',
      'period_starting_date_c' => $_module_name . '.period_starting_date_c',
      'period_ending_date_c' => $_module_name . '.period_ending_date_c',
   ),
   'searchInputs' => array(
      1 => 'name',
      4 => 'date_modified',
      5 => 'assigned_user_id',
      6 => 'date_entered',
      7 => 'period_starting_date_c',
      8 => 'period_ending_date_c',
   ),
   'searchdefs' => array(
      'name' =>
      array(
         'name' => 'name',
         'width' => '10%',
      ),
      'date_modified' =>
      array(
         'type' => 'datetime',
         'label' => 'LBL_DATE_MODIFIED',
         'width' => '10%',
         'name' => 'date_modified',
      ),
      'assigned_user_id' =>
      array(
         'name' => 'assigned_user_id',
         'label' => 'LBL_ASSIGNED_TO',
         'type' => 'enum',
         'function' =>
         array(
            'name' => 'get_user_array',
            'params' =>
            array(
               0 => false,
            ),
         ),
         'width' => '10%',
      ),
      'date_entered' =>
      array(
         'type' => 'datetime',
         'label' => 'LBL_DATE_ENTERED',
         'width' => '10%',
         'name' => 'date_entered',
      ),
      'period_starting_date_c' =>
      array(
         'type' => 'date',
         'label' => 'LBL_PERIOD_STARTING_DATE',
         'width' => '10%',
         'name' => 'period_starting_date_c',
      ),
      'period_ending_date_c' =>
      array(
         'type' => 'date',
         'label' => 'LBL_PERIOD_ENDING_DATE',
         'width' => '10%',
         'name' => 'period_ending_date_c',
      ),
   ),
);
