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


/**
 * SugarMetric hook handler class
 *
 * @see custom/Extension/application/Ext/LogicHooks/SugarMetricHoolks.php
 * contains hook configuration for SugarMetric
 */
class SugarMetric_HookManager
{
    /**
     * Initialization state
     *
     * @var bool
     */
    protected $initialized = false;

    /**
     * SugarMetric initialization hook
     *
     * Serve "after_entry_point" hook handling
     */
    public function afterEntryPoint()
    {
        if ($this->initialized) {
            return;
        }

        SugarMetric_Helper::run(false);
        $this->initialized = true;
    }

    /**
     * Called on sugar_cleanup
     *
     * Serve "server_round_trip" hook handling
     */
    public function serverRoundTrip()
    {
        $instance = SugarMetric_Manager::getInstance();

        // Check transaction name was set on endPoints
        if (!$instance->isNamedTransaction()) {
            if (isset($GLOBALS['log']) && !empty($_SERVER['REQUEST_URI'])) {

                // Log REQUEST_URI to debug "dead" entryPoints
                $GLOBALS['log']->debug('Unregistered Transaction name for URI: ' . $_SERVER['REQUEST_URI']);
            }
        }
    }

}
