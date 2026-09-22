<?php

use MintHCM\Api\Controllers\CandidaturesSidepanelController;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = [
    'candidatures.sidepanel.score' => [
        'method' => 'GET',
        'path' => '/{id}/sidepanel/score',
        'class' => CandidaturesSidepanelController::class,
        'function' => 'getCandidateScore',
        'desc' => 'Get average appraisal score for candidature sidepanel',
        'options' => [
            'auth' => true,
        ],
        'pathParams' => [
            'id' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'Candidature ID',
            ],
        ],
    ],
    'candidatures.sidepanel.history' => [
        'method' => 'GET',
        'path' => '/{id}/sidepanel/history',
        'class' => CandidaturesSidepanelController::class,
        'function' => 'getApplicationHistory',
        'desc' => 'Get application history for candidature sidepanel',
        'options' => [
            'auth' => true,
        ],
        'pathParams' => [
            'id' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'Candidature ID',
            ],
        ],
    ],
];
