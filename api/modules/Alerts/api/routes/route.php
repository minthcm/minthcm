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

use MintHCM\Api\Middlewares\Params\ParamTypes\ArrayType;
use MintHCM\Api\Middlewares\Params\ParamTypes\BoolType;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;
use MintHCM\Modules\Alerts\api\controllers\ListAction;
use MintHCM\Modules\Alerts\api\controllers\MassActionController;
use MintHCM\Modules\Alerts\api\controllers\UpdateAction;

$routes = array(
    "detail" => array(),
    "list_data" => array(),
    "list" => array(
        "method" => "GET",
        "path" => "",
        "class" => ListAction::class,
        "desc" => "Get modules list",
        "options" => array(
            'auth' => true,
        ),
    ),
    "update" => array(
        "method" => "PATCH",
        "path" => "/{id}",
        "class" => UpdateAction::class,
        "desc" => "Update module fields from body",
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
        "bodyParams" => array(
            "is_read" => array(
                "type" => BoolType::class,
                "required" => false,
                "desc" => "Set alert as readed",
                "example" => 'true or 1 or "1"',
            ),
            "is_closed" => array(
                "type" => BoolType::class,
                "required" => false,
                "desc" => "Set alert as closed",
                "example" => 'false or 0 or "0"',
            ),
        ),
    ),
    "readAlerts" => [
        "method" => "PATCH",
        "path" => "/update/ReadAlerts",
        "class" => MassActionController::class,
        "desc" => "Mark passed alerts as read",
        "function" => 'readAlerts',
        "options" => [
            'auth' => true,
        ],
        "pathParams" => [],
        "bodyParams" => [ 
            "records" => [
                "type" => ArrayType::class,
                "required" => false,
                "example" => '
                {
                    "records":[
                        "223dee27-b9e7-432a-8da9-c84cc0770035",
                        "723wee27-b9e7-432a-8da9-c831c0770031",
                        "1236ee27-b9e7-432a-8da9-c84c4fsa0033",
                    ]
                }
                ',
            ],
        ],
    ],
    "closeAlerts" => [
        "method" => "PATCH",
        "path" => "/update/CloseAlerts",
        "class" => MassActionController::class,
        "desc" => "Mark passed alerts as closed",
        "function" => 'closeAlerts',
        "options" => [
            'auth' => true,
        ],
        "pathParams" => [],
        "bodyParams" => [ 
            "records" => [
                "type" => ArrayType::class,
                "required" => false,
                "desc" => "Set alert as readed",
                "example" => '
                {
                    "records":[
                        "223dee27-b9e7-432a-8da9-c84cc0770035",
                        "723wee27-b9e7-432a-8da9-c831c0770031",
                        "1236ee27-b9e7-432a-8da9-c84c4fsa0033",
                    ]
                }
                ',
            ],
        ],
    ],
);
