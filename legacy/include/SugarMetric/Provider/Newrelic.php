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
 * Newrelic data provider class
 *
 * Implements basic Newrelic functions and configuration
 */
require_once('include/SugarMetric/Provider/Interface.php');

class SugarMetric_Provider_Newrelic implements SugarMetric_Provider_Interface
{
    /**
     * Contains information about loaded status of newrelic extension
     *
     * @var bool
     */
    protected $isLoaded = false;

    /**
     * Initialize Newrelic Metric Provider and add it to SugarMetric_Manager listeners chain
     *
     * @param array $additionalParams
     */
    public function __construct(array $additionalParams)
    {
        if ($this->isLoaded = extension_loaded('newrelic')) {

            foreach ($additionalParams as $name => $param) {

                switch (strtolower($name)) {
                    case 'applicationname' :
                        newrelic_set_appname($param);
                        break;
                    default :
                        break;
                }
            }
        } else {
            if (isset($GLOBALS['log'])) {
                $GLOBALS['log']->debug('SugarMetric_Provider_Newrelic: newrelic php extension is not loaded on server');
            }
        }
    }

    /**
     * Returns "true" if Newrelic extension is loaded
     * Otherwise returns false
     *
     * @return bool
     */
    public function isLoaded()
    {
        return $this->isLoaded;
    }

    /**
     * Set up a name for current Web Transaction
     *
     * @param string $name
     * @return null
     */
    public function setTransactionName($name)
    {
        newrelic_name_transaction($name);
    }

    /**
     * Add custom parameter to transaction stack trace
     *
     * @param string $name
     * @param mixed $value
     * @return null
     */
    public function addTransactionParam($name, $value)
    {
        newrelic_add_custom_parameter($name, $value);
    }

    public function setCustomMetric($name, $value)
    {
        newrelic_custom_metric($name, floatval($value));
    }

    /**
     * Provide exception handling and reports to server stack trace information
     *
     * @param Exception $exception
     * @return null
     */
    public function handleException(Exception $exception)
    {
        newrelic_notice_error($exception->getMessage(), $exception);
    }

    /**
     * Mark transaction as background or web transaction
     *
     * @param string $name
     * @return null|void
     */
    public function setMetricClass($name)
    {
        if ($name == 'background') {
            newrelic_background_job(true);
        }
    }
}
