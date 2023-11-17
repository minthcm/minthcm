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
   'moduleMain' => 'Transportations',
   'varName' => 'Transportations',
   'orderBy' => 'transportations.name',
   'whereClauses' => array(
      'from_city' => 'transportations.from_city',
      'to_city' => 'transportations.to_city',
      'type' => 'transportations.type',
      'other_transportation' => 'transportations.other_transportation',
      'name' => 'transportations.name',
      'trans_date' => 'transportations.trans_date',
   ),
   'searchInputs' => array(
      'from_city',
      'to_city',
      'type',
      'other_transportation',
      'name',
      'trans_date',
   ),
   'searchdefs' => array(
      'name' => array(
         'type' => 'name',
         'label' => 'LBL_NAME',
         'width' => '10%',
         'name' => 'name',
      ),
      'from_city' => array(
         'type' => 'varchar',
         'label' => 'LBL_FROM_CITY',
         'width' => '10%',
         'name' => 'from_city',
      ),
      'to_city' => array(
         'type' => 'varchar',
         'label' => 'LBL_TO_CITY',
         'width' => '10%',
         'name' => 'to_city',
      ),
      'trans_date' => array(
         'type' => 'date',
         'label' => 'LBL_TRANS_DATE',
         'width' => '10%',
         'name' => 'trans_date',
      ),
      'type' => array(
         'type' => 'enum',
         'studio' => 'visible',
         'label' => 'LBL_TYPE',
         'sortable' => false,
         'width' => '10%',
         'name' => 'type',
      ),
      'other_transportation' => array(
         'type' => 'varchar',
         'label' => 'LBL_OTHER_TRANSPORTATION',
         'width' => '10%',
         'name' => 'other_transportation',
      ),
      'assigned_user_name' => array(
         'link' => 'assigned_user_link',
         'type' => 'relate',
         'label' => 'LBL_ASSIGNED_TO_NAME',
         'width' => '10%',
         'name' => 'assigned_user_name',
      ),
   ),
   'listviewdefs' => array(
      'NAME' => array(
         'type' => 'name',
         'label' => 'LBL_NAME',
         'width' => '10%',
         'default' => true,
         'name' => 'name',
         'link' => true,
      ),
      'FROM_CITY' => array(
         'type' => 'varchar',
         'label' => 'LBL_FROM_CITY',
         'width' => '10%',
         'default' => true,
         'name' => 'from_city',
      ),
      'TO_CITY' => array(
         'type' => 'varchar',
         'label' => 'LBL_TO_CITY',
         'width' => '10%',
         'default' => true,
         'name' => 'to_city',
      ),
      'TRANS_DATE' => array(
         'type' => 'date',
         'label' => 'LBL_TRANS_DATE',
         'width' => '10%',
         'default' => true,
      ),
      'TYPE' => array(
         'type' => 'enum',
         'default' => true,
         'studio' => 'visible',
         'label' => 'LBL_TYPE',
         'sortable' => true,
         'width' => '10%',
         'name' => 'type',
      ),
      'OTHER_TRANSPORTATION' => array(
         'type' => 'varchar',
         'label' => 'LBL_OTHER_TRANSPORTATION',
         'width' => '10%',
         'default' => true,
         'name' => 'other_transportation',
      ),
      'OTHER_INFO' => array(
         'type' => 'varchar',
         'label' => 'LBL_OTHER_INFO',
         'width' => '10%',
         'default' => true,
         'name' => 'other_info',
      ),
      'ASSIGNED_USER_NAME' => array(
         'link' => 'assigned_user_link',
         'type' => 'relate',
         'label' => 'LBL_ASSIGNED_TO_NAME',
         'width' => '10%',
         'default' => true,
         'name' => 'assigned_user_name',
      ),
   ),
);
