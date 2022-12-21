<?php

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

require_once 'modules/Home/Dashlets/OrganizationStructureDashlet/OrganizationStructure.php';

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_STRING);
echo (new OrganizationStructure($id, []))->displayFullScreen($id);
