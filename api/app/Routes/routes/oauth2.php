<?php

use MintHCM\Api\Controllers\OAuth2;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = array(
    "generate_token" => array(
        "method" => array("POST"),
        "path" => "/access_token",
        "class" => OAuth2\Controller::class,
        "function" => 'accessToken',
        "desc" => "Generate or refresh access token",
        "options" => array(
            'auth' => false,
        ),
        "pathParams" => array(),
        "queryParams" => array(),
        "bodyParams" => array(
            'grant_type' => array(
                "type" => StringType::class,
                "required" => true,
            ),
            'client_id' => array(
                "type" => StringType::class,
                "required" => true,
            ),
            'client_secret' => array(
                "type" => StringType::class,
                "required" => true,
            ),
            'username' => array(
                "type" => StringType::class,
                "required" => false,
            ),
            'password' => array(
                "type" => StringType::class,
                "required" => false,
            ),
            'refresh_token' => array(
                "type" => StringType::class,
                "required" => false,
            ),
        ),
    ),
);
