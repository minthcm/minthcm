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

use MintHCM\Api\Controllers\Init\Module;
use MintHCM\Api\Controllers\ModuleController;
use MintHCM\Api\Controllers\Module\ListController;
use MintHCM\Api\Controllers\Module\ListInitController;
use MintHCM\Api\Controllers\Module\ListMassActionsController;
use MintHCM\Api\Middlewares\Params\ParamTypes\ArrayType;
use MintHCM\Api\Middlewares\Params\ParamTypes\IntType;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;
use MintHCM\Api\Middlewares\Params\ParamTypes\BoolType;

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
    "create" => array(
        "method" => "POST",
        "path" => "/Create",
        "class" => ModuleController::class,
        "function" => 'create',
        "desc" => "Create records",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(),
        "queryParams" => array(),
        "bodyParams" => array(
            "record_data" => array(
                "type" => ArrayType::class,
                "required" => true,
                "desc" => "Record data",
                "example" => '
                    "record_data": {
                        "first_name": "Example",
                        "last_name": "record",
                        "birthdate": "2023-07-23",
                    },
                ',
            ),
            "links" => array(
                "type" => ArrayType::class,
                "required" => false,
                "desc" => "Related records to link/unlink",
                "example" => '
                    "accounts": {
                        "beansToAdd": {
                            "223dee27-b9e7-432a-8da9-c84cc0770035": {
                                "id": "223dee27-b9e7-432a-8da9-c84cc0770035",
                                "additionalValues": {}
                            }
                        },
                        "beansToRemove": ["223dee27-b9e7-432a-8da9-c84cc0770035"],
                    },
                ',
            ),
        ),
    ),
    "update" => array(
        "method" => "PATCH",
        "path" => "/Update[/{id}]",
        "class" => ModuleController::class,
        "function" => 'update',
        "desc" => "Update records",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(
            "id" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "Module id",
                "example" => '223dee27-b9e7-432a-8da9-c84cc0770035',
            ),
        ),
        "queryParams" => array(),
        "bodyParams" => array(
            "record_data" => array(
                "type" => ArrayType::class,
                "required" => true,
                "desc" => "Record id and fields to overwrite",
                "example" => '
                    "record_data": {
                        "name": "Updated example record",
                        "description": "Example desc for this record"
                    },
                ',
            ),
            "files" => array(
                "type" => ArrayType::class,
                "required" => false,
                "desc" => "Record files to upload",
                "example" => '
                    "files": {
                        "photo": Base64 encoded file content,
                        "document": Base64 encoded file content,
                    },
                ',
            ),
            "links" => array(
                "type" => ArrayType::class,
                "required" => false,
                "desc" => "Related records to link/unlink",
                "example" => '
                    "accounts": {
                        "beansToAdd": {
                            "223dee27-b9e7-432a-8da9-c84cc0770035": {
                                "id": "223dee27-b9e7-432a-8da9-c84cc0770035",
                                "additionalValues": {}
                            }
                        },
                        "beansToRemove": ["223dee27-b9e7-432a-8da9-c84cc0770035"],
                    },
                ',
            ),
        ),
    ),
    "get_record" => array(
        "method" => "POST",
        "path" => "/Get[/{id}]",
        "class" => ModuleController::class,
        "function" => 'getRecord',
        "desc" => "Get record fields",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(
            "id" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "Module id",
                "example" => '223dee27-b9e7-432a-8da9-c84cc0770035',
            ),
        ),
        "queryParams" => array(),
        "bodyParams" => array(
            "links" => array(
                "type" => ArrayType::class,
                "required" => false,
                "desc" => "List of relations to load",
                "example" => ['meetings'],
            ),
        ),
    ),
    "get_record_logic" => array(
        "method" => "POST",
        "path" => "/Logic[/{id}]",
        "class" => ModuleController::class,
        "function" => 'getRecordLogic',
        "desc" => "Get record logic",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(
            "id" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "Module id",
                "example" => '223dee27-b9e7-432a-8da9-c84cc0770035',
            ),
        ),
        "queryParams" => array(),
        "bodyParams" => array(
            "attributes" => array(
                "type" => ArrayType::class,
                "required" => true,
                "desc" => "Attributes",
                "example" => '
                    "attributes": {
                        "first_name": "Example",
                        "last_name": "record",
                        "birthdate": "2023-07-23",
                    },
                ',
            ),
            "triggerFields" => array(
                "type" => ArrayType::class,
                "required" => true,
                "desc" => "Trigger Fields",
                "example" => '
                    [
                        "first_name",
                        "last_name",
                        "birthdate",
                    ],
                ',
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
    "subpanel_records" => array(
        "method" => "POST",
        "path" => "/subpanel/{relation_name}/{id}",
        "class" => ModuleController::class,
        "function" => 'subpanelRecords',
        "desc" => "Returns related records for Subpanel.",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(
            "relation_name" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Relatiuon name or name of subpanel",
                "example" => 'candidatures',
            ),
            "id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Module id",
                "example" => '223dee27-b9e7-432a-8da9-c84cc0770035',
            ),
        ),
        "queryParams" => array(
            "paginate_by" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Number of records per page",
                "example" => '10',
            ),
            "page" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Page number, starts from 0",
                "example" => '6',
            ),
        ),
        "bodyParams" => array(
            "sortBy" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "Field name to sort by",
                "example" => 'name',
            ),
            "sortOrder" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "Sort order",
                "example" => 'DESC',
            ),
        ),
    ),
    "list_data" => array(
        "method" => "POST",
        "path" => "",
        "class" => ListController::class,
        "function" => 'getListData',
        "desc" => "Get list of module beans",
        "options" => array(
            'auth' => true,
        ),
        "bodyParams" => array(
            "activeFilter" => array(
                "type" => ArrayType::class,
                "required" => false,
            ),
            "page" => array(
                "type" => IntType::class,
                "required" => false,
                "desc" => "Page number to retrieve",
                "example" => '1',
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
            "myObjects" => array(
                "type" => BoolType::class,
                "required" => false,
                "desc" => "if enable, shows only records that are created or assigned to current user",
                "example" => '1',
            ),
            "searchPhrase" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "Search phrase",
                "example" => 'John Doe',
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
            "onlyFavorites" => array(
                "type" => BoolType::class,
                "required" => false,
                "desc" => "if enable, shows only favorite records",
                "example" => '1',
            ),
        ),
    ),
    "list_save_preferences" => array(
        "method" => "POST",
        "path" => "/list/preferences",
        "class" => ListController::class,
        "function" => 'savePreferences',
        "desc" => "Save user list preferences",
        "options" => array(
            'auth' => true,
        ),
        "bodyParams" => array(
            "preferences" => array(
                "type" => ArrayType::class,
                "required" => true,
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
    "mass_actions" => array(
        "method" => "POST",
        "path" => "/MassActions/{action}",
        "class" => ListMassActionsController::class,
        "desc" => "Mass actions on list of records",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(
            "action" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Action name",
                "example" => 'Delete',
            ),
        ),
        "bodyParams" => array(
            "ids" => array(
                "type" => ArrayType::class,
                "required" => true,
                "desc" => "Array of ids",
                "example" => '["223dee27-b9e7-432a-8da9-c84cc0770035", "223dee27-b9e7-432a-8da9-c84cc0770035"]',
            ),
            "update_fields" => array(
                "type" => ArrayType::class,
                "required" => false,
                "desc" => "Array of fields to update",
                "example" => '
                    "update_fields": {
                        "status": "Active",
                        "assigned_user_id": "1",
                    },
                ',
            ),
        ),
    ),
    "link" => array(
        "method" => "POST",
        "path" => "/Link/{id}",
        "class" => ModuleController::class,
        "function" => 'link',
        "desc" => "Link records",
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
        "bodyParams" => array(
            "ids" => array(
                "type" => ArrayType::class,
                "required" => true,
                "desc" => "Record ids to link",
                "example" => '["223dee27-b9e7-432a-8da9-c84cc0770035", "223dee27-b9e7-432a-8da9-c84cc0770035]',
            ),
            "link_name" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Link name",
                "example" => 'contacts',
            ),
        ),
    ),
    "checklist" => array(
        "method" => "GET",
        "path" => "/checklist/{id}",
        "class" => ModuleController::class,
        "function" => 'getChecklistItems',
        "desc" => "Returns checklist items for a record if supported.",
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
    "unlink" => array(
        "method" => "POST",
        "path" => "/Unlink/{id}",
        "class" => ModuleController::class,
        "function" => 'unlink',
        "desc" => "Unlink records",
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
        "bodyParams" => array(
            "ids" => array(
                "type" => ArrayType::class,
                "required" => true,
                "desc" => "Record ids to unlink",
                "example" => '["223dee27-b9e7-432a-8da9-c84cc0770035", "223dee27-b9e7-432a-8da9-c84cc0770035]',
            ),
            "link_name" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Link name",
                "example" => 'contacts',
            ),
        ),
    ),
);
