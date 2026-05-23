<?php

use MintHCM\Modules\Candidatures\api\controllers\Convert;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = array(
    "convert" => array(
        "method" => "POST",
        "path" => "/convert",
        "class" => Convert::class,
        "desc" => "Convert Candidature into Employee or User",
        "options" => array(
            'auth' => true,
        ),
        "bodyParams" => array(
            "usertype" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Define that created record is Employee or User",
                "example" => 'employee || user',
            ),
            "username" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "Username to check. It is required if the created record is User",
                "example" => 'john_doe',
            ),
            "candidature_id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "ID of the candidature to convert",
                "example" => '10b38dfa-fd54-356e-7996-5ce404fb6df0',
            ),
        ),
    ),
);
