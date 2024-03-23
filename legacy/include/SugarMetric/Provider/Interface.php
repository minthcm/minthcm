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
 * SugarMetric library data providers interface
 *
 * Declaration of common SugarMetric Provider listeners methods
 */
interface SugarMetric_Provider_Interface
{
    /**
     * Returns information about provider loaded status
     * F.e. could return false when specific extension is loaded on server
     *
     * @return bool
     */
    public function isLoaded();

    /**
     * Set up a name for current Web Transaction
     *
     * @param string $name
     * @return null
     */
    public function setTransactionName($name);

    /**
     * Add custom parameter to transaction stack trace
     *
     * @param string $name
     * @param mixed $value
     * @return null
     */
    public function addTransactionParam($name, $value);

    /**
     * Set up custom metric.
     *
     * @param string $name
     * @param float $value
     * @return mixed
     */
    public function setCustomMetric($name, $value);

    /**
     * Provide exception handling and reports to server stack trace information
     *
     * @param Exception $exception
     * @return mixed
     */
    public function handleException(Exception $exception);

    /**
     * Set transaction class name (f.e. background, massupdate)
     *
     * @param string $name
     * @return null
     */
    public function setMetricClass($name);
}
