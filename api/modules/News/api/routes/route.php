<?php

use MintHCM\Modules\News\api\controllers\ListAction;

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
);
