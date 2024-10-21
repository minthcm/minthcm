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

use MintHCM\Api\Controllers\KudosController;
use MintHCM\Api\Middlewares\Params\ParamTypes\BoolType;
use MintHCM\Api\Middlewares\Params\ParamTypes\IntType;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = array(
    "getInitialData" => array(
        "method" => "GET",
        "path" => "/kudos/init",
        "class" => KudosController::class,
        "function" => "getInitialData",
        "desc" => "get kudos list",
        "options" => array(
            "auth" => true,
        ),
        "pathParams" => array(),
        "queryParams" => array(),
        "bodyParams" => array(),
    ),
    "get" => array(
        "method" => "GET",
        "path" => "/kudos",
        "class" => KudosController::class,
        "function" => "get",
        "desc" => "get kudos list",
        "options" => array(
            "auth" => true,
        ),
        "pathParams" => array(
        ),
        "queryParams" => array(
            "page" => array(
                "type" => IntType::class,
                "required" => true,
                "desc" => "page number of kudos list",
                "example" => 1,
            ),
            "listType" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "",
                "example" => 'all',
            ),
        ),
        "bodyParams" => array(),
    ),
    "post" => array(
        "method" => "POST",
        "path" => "/kudos/add",
        "class" => KudosController::class,
        "function" => "post",
        "desc" => "add a new record",
        "options" => array(
            "auth" => true,
        ),
        "pathParams" => array(),
        "queryParams" => array(),
        "bodyParams" => array(
            "id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Kudos ID",
                "example" => 'dac8dede-425f-b97a-aac5-25b7751a2f1x',
            ),
            "gifted_user_id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Kudos gifted user ID",
                "example" => 'dac8dede-425f-b97a-aac5-25b7751a2f1x',
            ),
            "message" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Kudos message",
                "example" => 'Thank you for borrowing the USB-c cable',
            ),
            "private" => array(
                "type" => BoolType::class,
                "required" => true,
                "desc" => "means whether the kudos is private",
                "example" => 'true',
            ),
        ),
    ),
    "delete" => array(
        "method" => "delete",
        "path" => "/kudos/{id}",
        "class" => KudosController::class,
        "function" => "delete",
        "desc" => "delete a record",
        "options" => array(
            "auth" => true,
        ),
        "pathParams" => array(
            "id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Kudos ID",
                "example" => 'dac8dede-425f-b97a-aac5-25b7751a2f1x',
            ),
        ),
        "queryParams" => array(),
        "bodyParams" => array(),
    ),
);
