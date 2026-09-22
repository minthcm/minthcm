<?php

use MintHCM\Api\Controllers\EmployeeSidepanelController;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = [
    'employees.sidepanel.tenure' => [
        'method' => 'GET',
        'path' => '/{id}/sidepanel/tenure',
        'class' => EmployeeSidepanelController::class,
        'function' => 'getTenureSummary',
        'desc' => 'Get tenure (period of employment) for employee sidepanel',
        'options' => [
            'auth' => true,
        ],
        'pathParams' => [
            'id' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'Employee ID',
            ],
        ],
    ],
    'employees.sidepanel.leave' => [
        'method' => 'GET',
        'path' => '/{id}/sidepanel/leave',
        'class' => EmployeeSidepanelController::class,
        'function' => 'getUpcomingLeave',
        'desc' => 'Get upcoming leave entries (next 2 months) for employee sidepanel',
        'options' => [
            'auth' => true,
        ],
        'pathParams' => [
            'id' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'Employee (user) ID',
            ],
        ],
    ],
    'employees.sidepanel.trainings' => [
        'method' => 'GET',
        'path' => '/{id}/sidepanel/trainings',
        'class' => EmployeeSidepanelController::class,
        'function' => 'getTrainingsSummary',
        'desc' => 'Get trainings summary for employee sidepanel',
        'options' => [
            'auth' => true,
        ],
        'pathParams' => [
            'id' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'Employee (user) ID',
            ],
        ],
    ],
    'employees.sidepanel.competencies' => [
        'method' => 'GET',
        'path' => '/{id}/sidepanel/competencies',
        'class' => EmployeeSidepanelController::class,
        'function' => 'getCompetenciesSummary',
        'desc' => 'Get competency ratings for employee sidepanel',
        'options' => [
            'auth' => true,
        ],
        'pathParams' => [
            'id' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'Employee (user) ID',
            ],
        ],
    ],
    'employees.sidepanel.kudos' => [
        'method' => 'GET',
        'path' => '/{id}/sidepanel/kudos',
        'class' => EmployeeSidepanelController::class,
        'function' => 'getKudosSummary',
        'desc' => 'Get kudos summary for employee sidepanel',
        'options' => [
            'auth' => true,
        ],
        'pathParams' => [
            'id' => [
                'type' => StringType::class,
                'required' => true,
                'desc' => 'Employee (user) ID',
            ],
        ],
    ],
];
