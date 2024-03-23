<?php

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

header('Content-Type: application/vnd.api+json');

$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_STRING);
$language = filter_input(INPUT_GET, 'language', FILTER_SANITIZE_STRING);
$module = filter_input(INPUT_GET, 'module', FILTER_SANITIZE_STRING);

if (empty($action)) {
    http_response_code(400);
    die(json_encode([
        "error" => "invalid_request",
        "message" => "The request is missing a required parameter: action",
    ]));
}

switch ($action) {
    case 'availableLanguages':
        die(json_encode(get_languages()));
        break;
    case 'appStrings':
        if (empty($language)) {
            http_response_code(400);
            die(json_encode([
                "error" => "invalid_request",
                "message" => "The request is missing a required parameter: language",
            ]));
        }
        die(json_encode([
            "app_strings" => return_application_language($language),
            "app_list_strings" => return_app_list_strings_language($language),
        ]));
        break;
    case 'modStrings';
        if (empty($language)) {
            http_response_code(400);
            die(json_encode([
                "error" => "invalid_request",
                "message" => "The request is missing a required parameter: language",
            ]));
        }
        if (empty($module)) {
            http_response_code(400);
            die(json_encode([
                "error" => "invalid_request",
                "message" => "The request is missing a required parameter: module",
            ]));
        }
        $data = return_module_language($language, $module, true);
        if (!$data) {
            http_response_code(400);
            $data = [
                "error" => "invalid_request",
                "message" => "There is no module called: {$module}",
            ];
        }
        die(json_encode($data));
        break;
    default:
        http_response_code(400);
        die(json_encode([
            "error" => "invalid_request",
            "message" => "There is no action called: {$action}",
        ]));
}
