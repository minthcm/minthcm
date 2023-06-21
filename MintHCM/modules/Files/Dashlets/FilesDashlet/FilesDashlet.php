<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/Dashlets/DashletGeneric.php';
require_once 'modules/Files/Files.php';

class FilesDashlet extends DashletGeneric
{
    public function __construct($id, $def = null)
    {
        global $current_user, $app_strings;
        require 'modules/Files/metadata/dashletviewdefs.php';

        parent::__construct($id, $def);

        if (empty($def['title'])) {
            $this->title = translate('LBL_HOMEPAGE_TITLE', 'Files');
        }

        $this->searchFields = $dashletData['FilesDashlet']['searchFields'];
        $this->columns = $dashletData['FilesDashlet']['columns'];

        $this->seedBean = BeanFactory::newBean("Files");
    }
}
