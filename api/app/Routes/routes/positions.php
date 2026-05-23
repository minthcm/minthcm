<?php

use MintHCM\Api\Controllers\PositionsController;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = array(
    "get_competencies" => array(
        "method" => "GET",
        "path" => "/positionsPanel/competencies/{module}/{record_id}",
        "class" => PositionsController::class,
        "function" => 'getCompetencies',
        "desc" => "Get all competencies for given module and record id",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(
            "module" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Name of the module",
                "example" => 'Positions',
            ),
            "record_id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Id of the record",
                "example" => '223dee27-b9e7-432a-8da9-c84cc0770035',
            ),
        ),
    ),
    "get_responsibilities" => array(
        "method" => "GET",
        "path" => "/positionsPanel/responsibilities/{module}/{record_id}",
        "class" => PositionsController::class,
        "function" => 'getResponsibilities',
        "desc" => "Get all responsibilities for given module and record id",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(
            "module" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Name of the module",
                "example" => 'Positions',
            ),
            "record_id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Id of the record",
                "example" => '223dee27-b9e7-432a-8da9-c84cc0770035',
            ),
        ),
    ),
);