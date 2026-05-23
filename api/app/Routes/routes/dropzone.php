<?php

use MintHCM\Api\Controllers\FilesController;
use MintHCM\Api\Middlewares\Params\ParamTypes\FileType;
use MintHCM\Api\Middlewares\Params\ParamTypes\StringType;

$routes = array(
    "get_files" => array(
        "method" => "GET",
        "path" => "/files/{module_name}/{record_id}",
        "class" => FilesController::class,
        "function" => "getFiles",
        "desc" => "Get all files for given record",
        "options" => array(
            "auth" => true,
        ),
        "pathParams" => array(
            'module_name' => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Name of module",
                "example" => 'Candidates',
            ),
            'record_id' => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Id of a record",
                "example" => 'dac8dede-425f-b97a-aac5-25b7751a2f1x',
            )
        ),
        "queryParams" => array(),
        "bodyParams" => array(),
    ),
    "save_file" => array(
        "method" => "POST",
        "path" => "/files/save",
        "class" => FilesController::class,
        "function" => "saveFile",
        "desc" => "Route for saving a file",
        "options" => array(
            "auth" => true,
        ),
        "bodyParams" => array(
            'module_name' => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Name of module",
                "example" => 'Candidates',
            ),
            'record_id' => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Id of a record",
                "example" => 'dac8dede-425f-b97a-aac5-25b7751a2f1x',
            ),
            'file' => array(
                "type" => FileType::class,
                "required" => true,
                "desc" => "File to save",
                "example" => 'somePicture.png',
            ),
        ),
    ),
    "delete_file" => array(
        "method" => "POST",
        "path" => "/files/delete",
        "class" => FilesController::class,
        "function" => "deleteFile",
        "desc" => "Route for deleting a file",
        "options" => array(
            "auth" => true,
        ),
        "bodyParams" => array(
            'file_id' => array(
                "type" => StringType::class,
                "required" => true,
                "desc" => "Id of a File to delete",
                "example" => 'dac8dede-425f-b97a-aac5-25b7751a2f1x',
            ),
        ),
    ),
);