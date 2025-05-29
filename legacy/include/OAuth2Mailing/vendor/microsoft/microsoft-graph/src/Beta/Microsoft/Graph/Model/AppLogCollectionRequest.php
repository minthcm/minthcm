<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* AppLogCollectionRequest File
* PHP version 7
*
* @category  Library
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
namespace Beta\Microsoft\Graph\Model;

/**
* AppLogCollectionRequest class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class AppLogCollectionRequest extends Entity
{
    /**
    * Gets the completedDateTime
    * Time at which the upload log request reached a completed state if not completed yet NULL will be returned.
    *
    * @return \DateTime|null The completedDateTime
    */
    public function getCompletedDateTime()
    {
        if (array_key_exists("completedDateTime", $this->_propDict)) {
            if (is_a($this->_propDict["completedDateTime"], "\DateTime") || is_null($this->_propDict["completedDateTime"])) {
                return $this->_propDict["completedDateTime"];
            } else {
                $this->_propDict["completedDateTime"] = new \DateTime($this->_propDict["completedDateTime"]);
                return $this->_propDict["completedDateTime"];
            }
        }
        return null;
    }

    /**
    * Sets the completedDateTime
    * Time at which the upload log request reached a completed state if not completed yet NULL will be returned.
    *
    * @param \DateTime $val The completedDateTime
    *
    * @return AppLogCollectionRequest
    */
    public function setCompletedDateTime($val)
    {
        $this->_propDict["completedDateTime"] = $val;
        return $this;
    }

    /**
    * Gets the customLogFolders
    * List of log folders.
    *
    * @return array|null The customLogFolders
    */
    public function getCustomLogFolders()
    {
        if (array_key_exists("customLogFolders", $this->_propDict)) {
            return $this->_propDict["customLogFolders"];
        } else {
            return null;
        }
    }

    /**
    * Sets the customLogFolders
    * List of log folders.
    *
    * @param string[] $val The customLogFolders
    *
    * @return AppLogCollectionRequest
    */
    public function setCustomLogFolders($val)
    {
        $this->_propDict["customLogFolders"] = $val;
        return $this;
    }

    /**
    * Gets the errorMessage
    * Indicates error message if any during the upload process.
    *
    * @return string|null The errorMessage
    */
    public function getErrorMessage()
    {
        if (array_key_exists("errorMessage", $this->_propDict)) {
            return $this->_propDict["errorMessage"];
        } else {
            return null;
        }
    }

    /**
    * Sets the errorMessage
    * Indicates error message if any during the upload process.
    *
    * @param string $val The errorMessage
    *
    * @return AppLogCollectionRequest
    */
    public function setErrorMessage($val)
    {
        $this->_propDict["errorMessage"] = $val;
        return $this;
    }

    /**
    * Gets the status
    * Indicates the status for the app log collection request if it is pending, completed or failed, Default is pending. Possible values are: pending, completed, failed, unknownFutureValue.
    *
    * @return AppLogUploadState|null The status
    */
    public function getStatus()
    {
        if (array_key_exists("status", $this->_propDict)) {
            if (is_a($this->_propDict["status"], "\Beta\Microsoft\Graph\Model\AppLogUploadState") || is_null($this->_propDict["status"])) {
                return $this->_propDict["status"];
            } else {
                $this->_propDict["status"] = new AppLogUploadState($this->_propDict["status"]);
                return $this->_propDict["status"];
            }
        }
        return null;
    }

    /**
    * Sets the status
    * Indicates the status for the app log collection request if it is pending, completed or failed, Default is pending. Possible values are: pending, completed, failed, unknownFutureValue.
    *
    * @param AppLogUploadState $val The status
    *
    * @return AppLogCollectionRequest
    */
    public function setStatus($val)
    {
        $this->_propDict["status"] = $val;
        return $this;
    }

}
