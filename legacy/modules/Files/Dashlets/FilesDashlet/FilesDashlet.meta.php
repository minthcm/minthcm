<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $app_strings;

$dashletMeta['FilesDashlet'] = array(
    'module' => 'Files',
    'title' => translate('LBL_HOMEPAGE_TITLE', 'Files'),
    'description' => 'A customizable view into Files',
    'category' => 'Module Views',
);
