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
 * SugarMetric Helper class
 *
 * Loads SugarMetric_Manager with depending objects such as sugar configuration
 * Used to take all loading logic in one place
 */
class SugarMetric_Helper
{
    /**
     * Loads SugarCRM configuration files
     *
     * In case global configuration files are not loaded
     * (f.e. on entryPoint "getImage" or "getYUIComboFile"
     * @see include/preDispatch.php)
     * we should load them to use in SugarMetric_Manager class
     */
    public static function loadSugarConfig()
    {
        global $sugar_config;

        if ($sugar_config) {
            return;
        }

        if (is_file('config.php')) {
            require_once('config.php');
        }

        if (is_file('config_override.php')) {
            require_once('config_override.php');
        }
    }

    /**
     * Helper method to load SugarMetric_Manager
     *
     * SugarAutoLoader is not available only in case of entryPoint = "getYUIComboFile"
     * @see include/preDispatch.php
     */
    public static function loadManagerClass()
    {
        if (class_exists('SugarAutoLoader')) {
            SugarAutoLoader::requireWithCustom('include/SugarMetric/Manager.php');
        } else {
            if (file_exists('custom/include/SugarMetric/Manager.php')) {
                require_once 'custom/include/SugarMetric/Manager.php';
            } elseif (file_exists('include/SugarMetric/Manager.php')) {
            }
        }
    }

    /**
     * Helper method to load SugarMetric_Manager and set endPoints and transaction name
     *
     * @param string|bool $transaction is $transaction is FALSE do not call setTransactionName method
     */
    public static function run($transaction = '')
    {
        self::loadSugarConfig();
        self::loadManagerClass();

        $instance = SugarMetric_Manager::getInstance();

        if ($transaction !== false) {
            $instance->setTransactionName($transaction);
        }
    }
}
