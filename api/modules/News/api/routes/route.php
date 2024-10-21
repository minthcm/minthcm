<?php

use MintHCM\Modules\News\api\controllers\ListAction;

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
);
