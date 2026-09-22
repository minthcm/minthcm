<?php

use MintHCM\Modules\News\api\controllers\ListAction;
use MintHCM\Modules\News\api\controllers\UpdateAction;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = array(
    "drawer_list" => array(
        "method" => "GET",
        "path" => "/drawer/list",
        "class" => ListAction::class,
        "desc" => "Get modules list",
        "options" => array(
            'auth' => true,
        ),
    ),
    "readAlerts" => [
        "method" => "PATCH",
        "path" => "/update/readAlerts",
        "class" => UpdateAction::class,
        "desc" => "Mark passed alerts as read",
        "function" => 'markAlertsAsRead',
        "options" => [
            'auth' => true,
        ],
        "pathParams" => [],
        "bodyParams" => [ 
            "news_id" => [
                "type" => StringType::class,
                "required" => false,
                "example" => '"223dee27-b9e7-432a-8da9-c84cc0770035"',
            ],
        ],
    ],
    "updateStatus" => [
        "method" => "PATCH",
        "path" => "/update/status",
        "class" => UpdateAction::class,
        "desc" => "Update news status and generate user news records on publish",
        "function" => 'updateStatus',
        "options" => [
            'auth' => true,
        ],
        "pathParams" => [],
        "bodyParams" => [
            "news_id" => [
                "type" => StringType::class,
                "required" => true,
                "example" => '"223dee27-b9e7-432a-8da9-c84cc0770035"',
            ],
            "status" => [
                "type" => StringType::class,
                "required" => true,
                "example" => '"published"',
            ],
        ],
    ],
);
