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
 * SugarMetric_Provider_Log class used for logging and debugging metric's providers
 *
 * Registered in SugarMetric_Manager only if some logger class is available
 *
 * //TODO : Add setLogger($logger) and getLogger() method
 */
class SugarMetric_Provider_Log implements SugarMetric_Provider_Interface
{
    /**
     * @var LoggerManager
     */
    protected $logger;

    /**
     * Default logging level
     *
     * @var string
     */
    protected $logLevel = 'debug';

    /**
     * Initialize Log Metric Provider
     *
     * @param array $params optional params that come up from config.php
     */
    public function __construct($params)
    {
        if (!isset($GLOBALS['log']) && class_exists('SugarObject')) {
            $this->logger = LoggerManager::getLogger('SugarCRM');
        } elseif (isset($GLOBALS['log'])) {
            $this->logger = $GLOBALS['log'];
        }

        if (isset($params['log_level'])) {
            $this->logLevel = $params['log_level'];
        }
    }

    /**
     * Returns "true" if some logger is available and was
     * Otherwise returns false
     *
     * @return bool
     */
    public function isLoaded()
    {
        return (bool) $this->logger;
    }

    /**
     * Set up a name for current Web Transaction
     *
     * @param string $name
     * @return null
     */
    public function setTransactionName($name)
    {
        $this->logger->{$this->logLevel}('Log Metric Provider: setTransactionName with "' . $name . '" is called');
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
        $this->logger->{$this->logLevel}('Log Metric Provider: addTransactionParam with "' . $name . ' - ' . '" is called');
    }

    public function setCustomMetric($name, $value)
    {
        $this->logger->{$this->logLevel}('Log Metric Provider: setCustomMetric with "' . $name . ' - ' . $value . '" is called');
    }

    /**
     * Provide exception handling and reports to server stack trace information
     *
     * @param Exception $exception
     * @return null
     */
    public function handleException(Exception $exception)
    {
        $this->logger->{$this->logLevel}('Log Metric Provider: handleException with "' . $exception->getMessage() . '" is called');
    }

    /**
     * Set transaction class name (f.e. background, massupdate)
     *
     * @param string $name
     * @return null
     */
    public function setMetricClass($name)
    {
        $this->logger->{$this->logLevel}('Log Metric Provider: setMetricClass with class name: "' . $name . '" is called');
    }
}
