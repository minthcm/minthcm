<?php

use MintHCM\Api\Controllers\RecruitmentsSidepanelController;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = [
    'recruitments.sidepanel.candidature_statuses' => [
        'method' => 'GET',
        'path' => '/{id}/sidepanel/candidature-statuses',
        'class' => RecruitmentsSidepanelController::class,
        'function' => 'getCandidatureStatusCounts',
        'desc' => 'Get candidature counts grouped by status for recruitment sidepanel',
        'options' => [
            'auth' => true,
        ],
        'pathParams' => [
            'id' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'Recruitment ID',
            ],
        ],
    ],
];
