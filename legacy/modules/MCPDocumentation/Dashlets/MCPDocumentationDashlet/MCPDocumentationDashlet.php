<?php

/**
 * MintHCM is a Human Capital Management software based on SuiteCRM developed by MintHCM,
 * Copyright (C) 2018-2025 MintHCM
 */

if (!defined('sugarEntry') || !sugarEntry) {
    die('Not A Valid Entry Point');
}

require_once 'include/Dashlets/DashletGeneric.php';
require_once 'modules/MCPDocumentation/MCPDocumentation.php';

#[\AllowDynamicProperties]
class MCPDocumentationDashlet extends DashletGeneric
{
    public function __construct($id, $def = null)
    {
        require 'modules/MCPDocumentation/metadata/dashletviewdefs.php';

        parent::__construct($id, $def);

        if (empty($def['title'])) {
            $this->title = translate('LBL_HOMEPAGE_TITLE', 'MCPDocumentation');
        }

        $this->searchFields = $dashletData['MCPDocumentationDashlet']['searchFields'];
        $this->columns = $dashletData['MCPDocumentationDashlet']['columns'];

        $this->seedBean = BeanFactory::newBean('MCPDocumentation');
    }
}
