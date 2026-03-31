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

use MintHCM\Api\Controllers\SchedulerController;
use MintHCM\Api\Middlewares\Params\ParamTypes\ArrayType;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = array(
    "get_data" => array(
        "method" => "POST",
        "path" => "/scheduler",
        "class" => SchedulerController::class,
        "function" => "getData",
        "desc" => "get scheduler data",
        "options" => array(
            "auth" => true,
        ),
        "pathParams" => array(),
        "queryParams" => array(),
        "bodyParams" => array(
            "date_from" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "start date of the scheduler",
                "example" => '2025-09-25 08:00:00',
            ),
            "date_to" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "end date of the scheduler",
                "example" => '2025-09-25 16:00:00',
            ),
            "participants" => array(
                "type" => ArrayType::class,
                "required" => false,
                "desc" => "participants",
                "example" => '[{"module": "Candidates", "id": "01997f95-bad0-726e-b730-eab70e80e7ff"}]',
            ),
            "parent_id" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "parent ID",
                "example" => '01997bd4-1531-7036-8c59-1e81ed0baed6',
            ),
            "parent_type" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "module of the parent",
                "example" => 'Meetings',
            ),
            "search" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "search query",
                "example" => 'john',
            ),
        ),
    ),
);
