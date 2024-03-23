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

use MintHCM\Api\Controllers\Init\Module;
use MintHCM\Api\Controllers\ModuleController;
use MintHCM\Api\Controllers\Module\ListController;
use MintHCM\Api\Controllers\Module\ListInitController;
use MintHCM\Api\Middlewares\Params\ParamTypes\IntType;
use MintHCM\Api\Middlewares\Params\ParamTypes\ArrayType;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = array(
    "detail" => array(
        "method" => "GET",
        "path" => "/Detail/{id}",
        "class" => ModuleController::class,
        "function" => 'detail',
        "desc" => "Get module detail",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(
            "id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Module id",
                "example" => '223dee27-b9e7-432a-8da9-c84cc0770035',
            ),
        ),
    ),
    "delete" => array(
        "method" => "DELETE",
        "path" => "/{id}",
        "class" => ModuleController::class,
        "function" => 'delete',
        "desc" => "Delete record",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(
            "id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Module id",
                "example" => '223dee27-b9e7-432a-8da9-c84cc0770035',
            ),
        ),
        "queryParams" => array(),
        "bodyParams" => array(),
    ),
    "list_data" => array(
        "method" => "POST",
        "path" => "",
        "class" => ListController::class,
        "desc" => "Get list of module beans",
        "options" => array(
            'auth' => true,
        ),
        "bodyParams" => array(
            "offset" => array(
                "type" => IntType::class,
                "required" => true,
                "desc" => "Offset to start searching - in response get info about it, first page default has -1.
                     This number can be greater than items x page becouse user can not access to some rekords",
                "example" => '22',
            ),
            "items" => array(
                "type" => IntType::class,
                "required" => false,
                "desc" => "Items number per page",
                "example" => '25',
            ),
            "sortBy" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "Name of field to sort list by them",
                "example" => 'phone_mobile',
            ),
            "sortOrder" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "Sort order",
                "example" => 'desc or asc',
            ),
            "filters" => array(
                "type" => ArrayType::class,
                "required" => false,
                "desc" => "Array of filters",
                "example" => '
                    "filters": [
                        {
                            "field": "city",
                            "operator": "equals",
                            "value": "Paris",
                            "not": false/true => default false
                        },
                        {
                            "field": "country",
                            "operator": "match",
                            "value": "USA"
                        },
                    ]
                ',
            ),
        ),
    ),
    "list" => array(
        "method" => "GET",
        "path" => "",
        "class" => ListInitController::class,
        "desc" => "Get init data for list",
        "options" => array(
            'auth' => true,
        ),
    ),
    "init" => array(
        "method" => "GET",
        "path" => "/init",
        "class" => Module::class,
        "desc" => "Get init data for list",
        "options" => array(
            'auth' => true,
        ),
    ),
);
