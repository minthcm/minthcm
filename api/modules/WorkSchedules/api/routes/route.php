<?php

use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;
use MintHCM\Modules\WorkSchedules\api\controllers\CloseWorkSchedule;

$routes = array(
    "checkIfCanBeClosed" => array(
        "method" => "POST",
        "path" => "/checkIfCanBeClosed",
        "class" => CloseWorkSchedule::class,
        "desc" => "Checks if work schedule can be closed",
        "options" => array(
            'auth' => true,
        ),
        "bodyParams" => array(
            "record" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Work schedule ID",
                "example" => 'guid',
            ),
        ),
    ),
);
