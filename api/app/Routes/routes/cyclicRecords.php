<?php

use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;
use MintHCM\Api\Controllers\CyclicRecordsController;

$routes = array(
    
    "canBeRepeated" => array(
        "method" => "GET",
        "path" => "/CanBeRepeated/{module}/{id}",
        "class" => CyclicRecordsController::class,
        "function" => 'recordCanBeRepeated',
        "desc" => "Check if module can be repeated",
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
            "module" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "Module name",
                "example" => 'Meetings',
            ),
        ),
    ),
);