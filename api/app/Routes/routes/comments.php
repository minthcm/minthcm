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

use MintHCM\Api\Controllers\CommentsController;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;
use MintHCM\Api\Middlewares\Params\ParamTypes\ArrayType;

$routes = array(
    "get_initial_data" => array(
        "method" => "GET",
        "path" => "/comments/{parent_type}/{parent_id}/init",
        "class" => CommentsController::class,
        "function" => "getInitialData",
        "desc" => "get initial data for comments",
        "options" => array(
            "auth" => true,
        ),
        "pathParams" => array(
            "parent_type" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Parent module",
                "example" => "Tasks",
            ),
            "parent_id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Parent ID",
                "example" => "223dee27-b9e7-432a-8da9-c84cc0770035",
            ),
        ),
        "queryParams" => array(),
        "bodyParams" => array(),
    ),
    "get" => array(
        "method" => "GET",
        "path" => "/comments/{parent_type}/{parent_id}",
        "class" => CommentsController::class,
        "function" => "get",
        "desc" => "get comments",
        "options" => array(
            "auth" => true,
        ),
        "pathParams" => array(
            "parent_type" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Parent module",
                "example" => "Tasks",
            ),
            "parent_id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Parent ID",
                "example" => "223dee27-b9e7-432a-8da9-c84cc0770035",
            ),
        ),
        "queryParams" => array(),
        "bodyParams" => array(),
    ),
    "create" => array(
        "method" => "POST",
        "path" => "/comments/{parent_type}/{parent_id}",
        "class" => CommentsController::class,
        "function" => "create",
        "desc" => "create comment",
        "options" => array(
            "auth" => true,
        ),
        "pathParams" => array(
            "parent_type" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Parent module",
                "example" => "Tasks",
            ),
            "parent_id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Parent ID",
                "example" => "223dee27-b9e7-432a-8da9-c84cc0770035",
            ),
        ),
        "queryParams" => array(),
        "bodyParams" => array(
            "description" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "comment body",
                "example" => "Hello <strong>World!</strong>",
            ),
            "reply_to_id" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "reply to comment id",
                "example" => "223dee27-b9e7-432a-8da9-c84cc0770035",
            ),
        ),
    ),
    "update" => array(
        "method" => "PATCH",
        "path" => "/comments/{parent_type}/{parent_id}/{id}",
        "class" => CommentsController::class,
        "function" => "update",
        "desc" => "update comment",
        "options" => array(
            "auth" => true,
        ),
        "pathParams" => array(
            "parent_type" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Parent module",
                "example" => "Tasks",
            ),
            "parent_id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Parent ID",
                "example" => "223dee27-b9e7-432a-8da9-c84cc0770035",
            ),
            "id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Comment ID",
                "example" => "223dee27-b9e7-432a-8da9-c84cc0770035",
            ),
        ),
        "queryParams" => array(),
        "bodyParams" => array(
            "attributes" => array(
                "type" => ArrayType::class,
                "required" => true,
                "desc" => "comment fields",
                "example" => '
                    "attributes": {
                        "pinned": false
                    },
                ',
            ),
        ),
    ),
);
