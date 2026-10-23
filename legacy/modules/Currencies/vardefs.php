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
$dictionary['Currency'] = array('table' => 'currencies',
    'comment' => 'Currencies allow Sugar to store and display monetary values in various denominations'
    ,
    'doctrineEntity' => array(
        'repository' => 'CurrenciesRepository',
    ),
     'fields' => array(
        'id' => array(
            'name' => 'id',
            'vname' => 'LBL_NAME',
            'type' => 'id',
            'required' => true,
            'reportable' => false,
            'comment' => 'Record unique identifier',
        ),
        'name' => array(
            'name' => 'name',
            'vname' => 'LBL_LIST_NAME',
            'type' => 'varchar',
            'len' => '36',
            'required' => true,
            'comment' => 'The name or title of the record',
            'importable' => 'required',
        ),
        'symbol' => array(
            'name' => 'symbol',
            'vname' => 'LBL_LIST_SYMBOL',
            'type' => 'varchar',
            'len' => '36',
            'required' => true,
            'comment' => 'The symbol used to represent the currency',
            'importable' => 'required',
        ),
        'iso4217' => array(
            'name' => 'iso4217',
            'vname' => 'LBL_LIST_ISO4217',
            'type' => 'varchar',
            'len' => '3',
            'comment' => 'The ISO 4217 code for the currency',
        ),
        'conversion_rate' => array(
            'name' => 'conversion_rate',
            'vname' => 'LBL_LIST_RATE',
            'type' => 'float',
            'dbType' => 'double',
            'default' => '0',
            'required' => true,
            'comment' => 'The conversion rate of the currency to the base currency',
            'importable' => 'required',
        ),
        'status' => array(
            'name' => 'status',
            'vname' => 'LBL_STATUS',
            'type' => 'enum',
            'dbType' => 'varchar',
            'options' => 'currency_status_dom',
            'len' => 100,
            'comment' => 'The current status of the record',
            'importable' => 'required',
        ),
        'deleted' => array(
            'name' => 'deleted',
            'vname' => 'LBL_DELETED',
            'type' => 'bool',
            'required' => false,
            'reportable' => false,
            'comment' => 'A flag indicating whether the record has been deleted',
        ),
        'date_entered' => array(
            'name' => 'date_entered',
            'vname' => 'LBL_DATE_ENTERED',
            'type' => 'datetime',
            'required' => true,
            'comment' => 'The date when the record was created',
        ),
        'date_modified' => array(
            'name' => 'date_modified',
            'vname' => 'LBL_DATE_MODIFIED',
            'type' => 'datetime',
            'required' => true,
            'comment' => 'The date when the record was last modified',
        ),
        'created_by' => array(
            'name' => 'created_by',
            'reportable' => false,
            'vname' => 'LBL_CREATED_BY',
            'type' => 'id',
            'len' => '36',
            'required' => true,
            'comment' => 'The unique identifier of the user who created the record',
        ),
        'currency_on_right' => array(
            'name' => 'currency_on_right',
            'vname' => 'LBL_CURRENCY_ON_RIGHT_LIST',
            'label' => 'LBL_CURRENCY_ON_RIGHT_LIST',
            'type' => 'bool',
            'default' => '0',
            'comment' => 'Flag indicating whether the currency symbol is displayed on the right side of the amount',
        ),
        'hidden' => array(
            'name' => 'hidden',
            'vname' => 'LBL_HIDDEN',
            'label' => 'LBL_HIDDEN',
            'type' => 'bool',
            'default' => '0',
            'comment' => 'Flag indicating whether the currency is hidden',
        ),
    )
    , 'indices' => array(
        array('name' => 'currenciespk', 'type' => 'primary', 'fields' => array('id')),
        array('name' => 'idx_currency_name', 'type' => 'index', 'fields' => array('name', 'deleted')),
    ),
);


