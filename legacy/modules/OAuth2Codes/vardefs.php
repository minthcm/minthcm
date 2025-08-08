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

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$dictionary['OAuth2Codes'] = [
    'table' => 'oauth2codes',
    'audited' => false,
    'comment' => 'OAuth2 Authorization Codes',
    'fields' => [
        'id' => [
            'name' => 'id',
            'vname' => 'LBL_ID',
            'type' => 'id',
            'required' => true,
            'reportable' => false,
            'inline_edit' => false,
        ],
        'code' => [
            'name' => 'code',
            'vname' => 'LBL_CODE',
            'type' => 'varchar',
            'required' => true,
            'unique' => true,
            'len' => 128,
            'reportable' => false,
            'inline_edit' => false,
        ],
        'client_id' => [
            'name' => 'client_id',
            'vname' => 'LBL_CLIENT_ID',
            'type' => 'varchar',
            'required' => true,
            'len' => 128,
            'reportable' => false,
            'inline_edit' => false,
        ],
        'user_id' => [
            'name' => 'user_id',
            'vname' => 'LBL_USER_ID',
            'type' => 'id',
            'required' => true,
            'reportable' => false,
            'inline_edit' => false,
        ],
        'scope' => [
            'name' => 'scope',
            'vname' => 'LBL_SCOPE',
            'type' => 'varchar',
            'required' => true,
            'len' => 255,
            'reportable' => false,
            'inline_edit' => false,
        ],
        'code_challenge' => [
            'name' => 'code_challenge',
            'vname' => 'LBL_CODE_CHALLENGE',
            'type' => 'varchar',
            'required' => true,
            'len' => 255,
            'reportable' => false,
            'inline_edit' => false,
        ],
        'code_challenge_method' => [
            'name' => 'code_challenge_method',
            'vname' => 'LBL_CODE_CHALLENGE_METHOD',
            'type' => 'varchar',
            'required' => true,
            'len' => 16,
            'reportable' => false,
            'inline_edit' => false,
        ],
        'code_expires' => [
            'name' => 'code_expires',
            'vname' => 'LBL_CODE_EXPIRESs',
            'type' => 'datetime',
            'required' => true,
            'reportable' => false,
            'inline_edit' => false,
        ],
        'used' => [
            'name' => 'used',
            'vname' => 'LBL_USED',
            'type' => 'bool',
            'default' => 0,
            'reportable' => false,
            'inline_edit' => false,
        ],
    ],
    'indices' => [
        ['name' => 'idx_oauth2codes_code', 'type' => 'unique', 'fields' => ['code']],
    ],
    'unified_search' => false,
    'optimistic_locking' => true,
    'comment' => 'OAuth2 Authorization Codes',
];

if (!class_exists('VardefManager')) {
    require_once('include/SugarObjects/VardefManager.php');
}

VardefManager::createVardef(
    'OAuth2Codes',
    'OAuth2Codes',
    [
        'default',
    ]
);
