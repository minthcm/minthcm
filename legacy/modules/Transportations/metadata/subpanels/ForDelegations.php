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

// created: 2011-05-26 13:20:36
$subpanel_layout['list_fields'] = array(
   'name' => array(
      'vname' => 'LBL_NAME',
      'widget_class' => 'SubPanelDetailViewLink',
      'width' => '45%',
      'default' => true,
   ),
   'from_city' => array(
      'type' => 'varchar',
      'vname' => 'LBL_FROM_CITY',
      'width' => '10%',
      'default' => true,
   ),
   'to_city' => array(
      'type' => 'varchar',
      'vname' => 'LBL_TO_CITY',
      'width' => '10%',
      'default' => true,
   ),
   'type' => array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'vname' => 'LBL_TYPE',
      'width' => '10%',
   ),
   'other_transportation' => array(
      'type' => 'varchar',
      'vname' => 'LBL_OTHER_TRANSPORTATION',
      'width' => '10%',
      'default' => true,
   ),
   'trans_date' => array(
      'type' => 'date',
      'vname' => 'LBL_TRANS_DATE',
      'width' => '10%',
      'default' => true,
   ),
   'assigned_user_name' => array(
      'vname' => 'LBL_ASSIGNED_TO_NAME',
      'width' => '30%'
   ),
   'edit_button' => array(
      'widget_class' => 'SubPanelEditButton',
      'module' => 'Transportations',
      'width' => '4%',
      'default' => true,
   ),
   'remove_button' => array(
      'widget_class' => 'SubPanelRemoveButton',
      'module' => 'Transportations',
      'width' => '5%',
      'default' => true,
   ),
);
