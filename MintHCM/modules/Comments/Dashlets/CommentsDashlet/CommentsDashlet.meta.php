<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

global $app_strings;

$dashletMeta['CommentsDashlet'] = array(
    'module' => 'Comments',
    'title' => translate('LBL_HOMEPAGE_TITLE', 'Comments'),
    'description' => 'A customizable view into Comments',
    'category' => 'Module Views'
);
