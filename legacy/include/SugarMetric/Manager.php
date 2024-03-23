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
 * Metric Manager class
 *
 * Provides basic interface to work with Metric Providers such as Newrelic provider
 * All configuration should be done in Config/providers.php file
 * or initialize method should be called with config array
 */
class SugarMetric_Manager
{
    /**
     * @var SugarMetric_Provider_Interface[]
     */
    protected $metricProviders = array();

    /**
     * @var SugarMetric_Manager
     */
    protected static $instance = null;

    /**
     * Transaction naming state
     *
     * @var bool
     */
    protected $transactionNamed = false;

    /**
     * Singleton constructor
     */
    protected function __construct()
    {
        global $sugar_config;

        // Check metrics is enabled in configuration
        if (empty($sugar_config['metrics_enabled'])) {
            return $this;
        }

        if (isset($sugar_config['metric_providers'])) {
            foreach ($sugar_config['metric_providers'] as $name => $path) {

                // Could not use SugarAutoLoader there, because in case of
                // entryPoint=getYUIComboFile script do not loads SugarAutoLoader
                if (file_exists($path)) {
                    require_once $path;

                    $additionalConfig = isset($sugar_config['metric_settings'][$name])
                        ? $sugar_config['metric_settings'][$name]
                        : array();

                    /** @var SugarMetric_Provider_Interface $metric  */
                    $metric = new $name($additionalConfig);

                    if ($metric->isLoaded()) {
                        $this->registerMetricProvider($name, $metric);
                    }
                }
            }
        }
    }

    /**
     * Deny cloning Singleton
     */
    protected function __clone()
    {

    }

    /**
     * Singleton initialization
     *
     * @return SugarMetric_Manager
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new SugarMetric_Manager();
        }
        $GLOBALS['log']->debug('Manager::getInstance');
        return self::$instance;
    }


    /**
     * Register Metric Provider as listener
     *
     * @param string $name
     * @param SugarMetric_Provider_Interface $metricProvider
     * @return SugarMetric_Manager
     */
    public function registerMetricProvider($name, SugarMetric_Provider_Interface $metricProvider)
    {
        if (!isset($this->metricProviders[$name])) {
            $this->metricProviders[$name] = $metricProvider;
        }

        return $this;
    }

    /**
     * Return registered Metric Providers
     *
     * @return SugarMetric_Provider_Interface[]
     */
    public function getMetricProviders()
    {
        return $this->metricProviders;
    }

    /**
     * Set up a name for current transaction
     *
     * @param string $name
     * @return SugarMetric_Manager
     */
    public function setTransactionName($name = '')
    {
        $GLOBALS['log']->debug('Manager::setTransactionName');
        foreach ($this->metricProviders as $provider) {
            $provider->setTransactionName($name);
        }

        $this->transactionNamed = true;

        return $this;
    }

    /**
     * Returns transaction naming state
     *
     * @return bool
     */
    public function isNamedTransaction()
    {
        return $this->transactionNamed;
    }

    /**
     * Set current transaction as background
     *
     * @param string $name class metrics job name (f.e. "background", "massupdate")
     * @return SugarMetric_Manager
     */
    public function setMetricClass($name)
    {
        $GLOBALS['log']->debug('Manager::setMetricClass');
        foreach ($this->metricProviders as $provider) {
            $provider->setMetricClass($name);
        }

        return $this;
    }

    /**
     * Add params to current transaction stack trace
     *
     * @param string $name
     * @param mixed $value
     * @return SugarMetric_Manager
     */
    public function addTransactionParam($name, $value)
    {
        foreach ($this->metricProviders as $provider) {
            $provider->addTransactionParam($name, $value);
        }

        return $this;
    }

    /**
     * Send exception trace to providers
     *
     * @param Exception $exception
     */
    public function handleException($exception)
    {
        foreach ($this->metricProviders as $provider) {
            $provider->handleException($exception);
        }
    }

}
