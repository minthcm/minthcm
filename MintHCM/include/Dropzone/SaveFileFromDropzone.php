<?php

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

$result = false;

$record = filter_input(INPUT_GET, 'record', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$module = filter_input(INPUT_GET, 'module', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$candidature_id = filter_input(INPUT_POST, 'candidature_id', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$custom_data_fields = ['candidature_id', 'note_type'];

function getCustomData($file, $custom_data_fields){
    foreach ($custom_data_fields as $field) {
        $value = filter_input(INPUT_POST, $field, FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        if (!empty($value)) {
            $file->$field = $value;
        }
    }
}   

if (!empty($record) && !empty($module) && !empty($_FILES['file'])) {
    require_once('include/upload_file.php');
    $fileManager = new UploadFile('file');
    if($fileManager->confirm_upload()) {
        $file = BeanFactory::newBean('Files');
        $file->name = $fileManager->get_stored_file_name();
        $file->parent_type = $module;
        $file->parent_id = $record;
        $file->filename = $fileManager->get_stored_file_name();
        $file->file_mime_type = $fileManager->get_mime_type();
        getCustomData($file, $custom_data_fields);
        if($file->save(false) && $fileManager->final_move($file->id)) {
            $result = $file->id;
        }
    }
}

echo $result;
