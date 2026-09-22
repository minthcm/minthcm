<?php

use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;
use MintHCM\Modules\Meetings\api\controllers\CloseMeeting;

$routes = array(
    "closeMeeting" => array(
        "method" => "POST",
        "path" => "/closeMeeting",
        "class" => CloseMeeting::class,
        "desc" => "Sets a Meeting's status to Held",
        "options" => array(
            'auth' => true,
        ),
        "bodyParams" => array(
            "id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Meeting ID",
                "example" => 'guid',
            ),
            "module" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Module name (Meetings)",
                "example" => 'Meetings',
            ),
        ),
    ),
);
