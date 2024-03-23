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

$module_name = 'Costs';
$listViewDefs [$module_name] = array(
   'NAME' => array(
      'width' => '20%',
      'label' => 'LBL_NAME',
      'default' => true,
      'link' => true,
   ),
   'ASSIGNED_USER_NAME' => array(
      'width' => '9%',
      'label' => 'LBL_ASSIGNED_TO_NAME',
      'module' => 'Employees',
      'id' => 'ASSIGNED_USER_ID',
      'default' => true,
   ),
   'DELEGATION_NAME' => array(
      'type' => 'relate',
      'link' => 'costs_delegations',
      'related_fields' => array( 'delegation_id', ),
      'label' => 'LBL_DELEGATION_NAME',
      'width' => '10%',
      'default' => true,
   ),
   'TRANSPORTATION_NAME' => array(
      'type' => 'relate',
      'related_fields' => array( 'transportation_id', ),
      'link' => 'transportations',
      'label' => 'LBL_TRANSPORTATION_NAME',
      'width' => '10%',
      'default' => true,
   ),
   'TYPE' => array(
      'type' => 'enum',
      'default' => true,
      'studio' => 'visible',
      'label' => 'LBL_TYPE',
      'sortable' => false,
      'width' => '10%',
   ),
   'COST_AMOUNT' => array(
      'type' => 'currency',
      'label' => 'LBL_COST_AMOUNT',
      'currency_format' => true,
      'width' => '10%',
      'default' => false,
   ),
   'COST_AMOUNT_USDOLLARS' => array(
      'type' => 'currency',
      //'label' => 'LBL_COST_AMOUNT_BASE',
      'label' => translate('LBL_COST_AMOUNT', $module_name) . ' (' . $this->ss->_tpl_vars['CURRENCY_SYMBOL'] . ')',
      'currency_format' => true,
      'width' => '10%',
      'default' => true,
   ),
   'COST_CITY' => array(
      'type' => 'varchar',
      'label' => 'LBL_COST_CITY',
      'width' => '10%',
      'default' => true,
   ),
   'COST_DATE' => array(
      'type' => 'date',
      'label' => 'LBL_COST_DATE',
      'width' => '10%',
      'default' => true,
   ),
);
