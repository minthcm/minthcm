<?php

use MintHCM\Api\Controllers\CyclicRecordsController;
use MintHCM\Api\Middlewares\Params\ParamTypes\ArrayType;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = array(

    "canBeRepeated" => array(
        "method" => "GET",
        "path" => "/CanBeRepeated/{module}/{id}",
        "class" => CyclicRecordsController::class,
        "function" => 'recordCanBeRepeated',
        "desc" => "Check if module can be repeated",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(
            "id" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "Module id",
                "example" => '223dee27-b9e7-432a-8da9-c84cc0770035',
            ),
            "module" => array(
                "type" => StringType::class,
                "required" => false,
                "desc" => "Module name",
                "example" => 'Meetings',
            ),
        ),
    ),

    "planCyclicRecords" => array(
        "method" => "GET",
        "path" => "/CyclicRecords/{module}/{id}/plan",
        "class" => CyclicRecordsController::class,
        "function" => 'planCyclicRecords',
        "desc" => "Return all cyclic record date pairs that would be created, without saving",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(
            "id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Module id",
                "example" => '223dee27-b9e7-432a-8da9-c84cc0770035',
            ),
            "module" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Module name",
                "example" => 'Meetings',
            ),
        ),
    ),

    "createCyclicRecordsBatch" => array(
        "method" => "POST",
        "path" => "/CyclicRecords/{module}/{id}/batch",
        "class" => CyclicRecordsController::class,
        "function" => 'createCyclicRecordsBatch',
        "desc" => "Create a batch of cyclic records from pre-calculated date pairs",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(
            "id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Module id",
                "example" => '223dee27-b9e7-432a-8da9-c84cc0770035',
            ),
            "module" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Module name",
                "example" => 'Meetings',
            ),
        ),
        "bodyParams" => array(
            "records" => array(
                "type" => ArrayType::class,
                "required" => true,
                "desc" => "Array of {date_start, date_end} pairs to create",
                "example" => '[{"date_start":"2026-03-11 10:00:00","date_end":"2026-03-11 11:00:00"}]',
            ),
            "related_ids" => array(
                "type" => ArrayType::class,
                "required" => false,
                "desc" => "Pre-collected relationship IDs keyed by link field name (from plan response)",
                "example" => '{"users":["id1","id2"],"candidates":["id3"]}',
            ),
        ),
    ),

    "planCyclicRecordsUpdate" => array(
        "method" => "GET",
        "path" => "/CyclicRecords/{module}/{id}/plan-update",
        "class" => CyclicRecordsController::class,
        "function" => 'planCyclicRecordsUpdate',
        "desc" => "Return IDs of all cyclic children of a record so they can be updated in batches",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(
            "id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Parent record id",
                "example" => '223dee27-b9e7-432a-8da9-c84cc0770035',
            ),
            "module" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Module name",
                "example" => 'Meetings',
            ),
        ),
    ),

    "updateCyclicRecordsBatch" => array(
        "method" => "POST",
        "path" => "/CyclicRecords/{module}/{id}/batch-update",
        "class" => CyclicRecordsController::class,
        "function" => 'updateCyclicRecordsBatch',
        "desc" => "Propagate parent record changes onto a batch of cyclic children by ID",
        "options" => array(
            'auth' => true,
        ),
        "pathParams" => array(
            "id" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Parent record id",
                "example" => '223dee27-b9e7-432a-8da9-c84cc0770035',
            ),
            "module" => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Module name",
                "example" => 'Meetings',
            ),
        ),
        "bodyParams" => array(
            "ids" => array(
                "type" => ArrayType::class,
                "required" => true,
                "desc" => "Array of cyclic child record IDs to update",
                "example" => '["223dee27-b9e7-432a-8da9-c84cc0770035"]',
            ),
        ),
    ),
);
