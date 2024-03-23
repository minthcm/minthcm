<?php

$routes = [
    [
        'path' => 'api/init',
        'method' => 'GET',
        'function' => 'redirectToInstaller',
    ],
    [
        'path' => 'api/install/init',
        'method' => 'GET',
        'function' => 'getInitialData',
    ],
    [
        'path' => 'api/install/validate_db',
        'method' => 'POST',
        'function' => 'validateDb',
    ],
    [
        'path' => 'api/install/validate_elastic',
        'method' => 'POST',
        'function' => 'validateElastic',
    ],
    [
        'path' => 'api/install/submit',
        'method' => 'POST',
        'function' => 'install',
    ],
    [
        'path' => 'api/install/status',
        'method' => 'GET',
        'function' => 'checkStatus',
    ],
];
