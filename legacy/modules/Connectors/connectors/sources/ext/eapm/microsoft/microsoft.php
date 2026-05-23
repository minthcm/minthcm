<?php
/*
 * Your installation or use of this SugarCRM file is subject to the applicable
 * terms available at
 * http://support.sugarcrm.com/Resources/Master_Subscription_Agreements/.
 * If you do not agree to all of the applicable terms or do not have the
 * authority to bind the entity as an authorized representative, then do not
 * install or use this SugarCRM file.
 *
 * Copyright (C) SugarCRM Inc. All rights reserved.
 */

class ext_eapm_microsoft extends source
{
    /**
     * Overrides parent __construct to set new variable defaults
     */
    public function __construct()
    {
        parent::__construct();
        $this->_enable_in_wizard = false;
        $this->_enable_in_hover = false;
        $this->_has_testing_enabled = false;
    }

    /**
     * getItem is not used by this connector
     */
    public function getItem($args = array(), $module = null)
    {
    }

    /**
     * getList is not used by this connector
     */
    public function getList($args = array(), $module = null)
    {
    }
}
