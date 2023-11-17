<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

$module_name = 'Files';
$metafiles[$module_name] = array(
    'detailviewdefs' => 'modules/' . $module_name . '/metadata/detailviewdefs.php',
    'editviewdefs' => 'modules/' . $module_name . '/metadata/editviewdefs.php',
    'listviewdefs' => 'modules/' . $module_name . '/metadata/listviewdefs.php',
    'searchdefs' => 'modules/' . $module_name . '/metadata/searchdefs.php',
    'popupdefs' => 'modules/' . $module_name . '/metadata/popupdefs.php',
    'searchfields' => 'modules/' . $module_name . '/metadata/SearchFields.php',
);
