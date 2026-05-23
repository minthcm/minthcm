<?php

use MintHCM\Modules\Users\api\controllers\UserNameUnique;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = array(
    "isUserNameUnique" => array(
        "method" => "POST",
        "path" => "/unique",
        "class" => UserNameUnique::class,
        "desc" => "Check if username is unique",
        "options" => array(
            'auth' => true,
        ),
        "bodyParams" => array(
            "username" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Username to check",
                "example" => 'john_doe',
            ),
        ),
    ),
);
