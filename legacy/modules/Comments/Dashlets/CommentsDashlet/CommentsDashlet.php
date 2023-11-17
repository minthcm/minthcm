<?php

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once('include/Dashlets/DashletGeneric.php');
require_once('modules/Comments/Comments.php');

class CommentsDashlet extends DashletGeneric {
    function __construct($id, $def = null)
    {
        global $current_user, $app_strings;
        require('modules/Comments/metadata/dashletviewdefs.php');

        parent::__construct($id, $def);

        if (empty($def['title'])) {
            $this->title = translate('LBL_HOMEPAGE_TITLE', 'Comments');
        }

        $this->searchFields = $dashletData['CommentsDashlet']['searchFields'];
        $this->columns = $dashletData['CommentsDashlet']['columns'];

        $this->seedBean = BeanFactory::newBean('Comments');        
    }
}
