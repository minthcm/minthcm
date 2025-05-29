<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* MobileAppTroubleshootingEvent File
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
* MobileAppTroubleshootingEvent class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class MobileAppTroubleshootingEvent extends DeviceManagementTroubleshootingEvent
{
    /**
    * Gets the applicationId
    * Intune application identifier.
    *
    * @return string|null The applicationId
    */
    public function getApplicationId()
    {
        if (array_key_exists("applicationId", $this->_propDict)) {
            return $this->_propDict["applicationId"];
        } else {
            return null;
        }
    }

    /**
    * Sets the applicationId
    * Intune application identifier.
    *
    * @param string $val The applicationId
    *
    * @return MobileAppTroubleshootingEvent
    */
    public function setApplicationId($val)
    {
        $this->_propDict["applicationId"] = $val;
        return $this;
    }

    /**
    * Gets the deviceId
    * Device identifier created or collected by Intune.
    *
    * @return string|null The deviceId
    */
    public function getDeviceId()
    {
        if (array_key_exists("deviceId", $this->_propDict)) {
            return $this->_propDict["deviceId"];
        } else {
            return null;
        }
    }

    /**
    * Sets the deviceId
    * Device identifier created or collected by Intune.
    *
    * @param string $val The deviceId
    *
    * @return MobileAppTroubleshootingEvent
    */
    public function setDeviceId($val)
    {
        $this->_propDict["deviceId"] = $val;
        return $this;
    }


     /**
     * Gets the history
    * Intune Mobile Application Troubleshooting History Item
     *
     * @return array|null The history
     */
    public function getHistory()
    {
        if (array_key_exists("history", $this->_propDict)) {
           return $this->_propDict["history"];
        } else {
            return null;
        }
    }

    /**
    * Sets the history
    * Intune Mobile Application Troubleshooting History Item
    *
    * @param MobileAppTroubleshootingHistoryItem[] $val The history
    *
    * @return MobileAppTroubleshootingEvent
    */
    public function setHistory($val)
    {
        $this->_propDict["history"] = $val;
        return $this;
    }

    /**
    * Gets the managedDeviceIdentifier
    * Device identifier created or collected by Intune.
    *
    * @return string|null The managedDeviceIdentifier
    */
    public function getManagedDeviceIdentifier()
    {
        if (array_key_exists("managedDeviceIdentifier", $this->_propDict)) {
            return $this->_propDict["managedDeviceIdentifier"];
        } else {
            return null;
        }
    }

    /**
    * Sets the managedDeviceIdentifier
    * Device identifier created or collected by Intune.
    *
    * @param string $val The managedDeviceIdentifier
    *
    * @return MobileAppTroubleshootingEvent
    */
    public function setManagedDeviceIdentifier($val)
    {
        $this->_propDict["managedDeviceIdentifier"] = $val;
        return $this;
    }

    /**
    * Gets the userId
    * Identifier for the user that tried to enroll the device.
    *
    * @return string|null The userId
    */
    public function getUserId()
    {
        if (array_key_exists("userId", $this->_propDict)) {
            return $this->_propDict["userId"];
        } else {
            return null;
        }
    }

    /**
    * Sets the userId
    * Identifier for the user that tried to enroll the device.
    *
    * @param string $val The userId
    *
    * @return MobileAppTroubleshootingEvent
    */
    public function setUserId($val)
    {
        $this->_propDict["userId"] = $val;
        return $this;
    }


     /**
     * Gets the appLogCollectionRequests
    * The collection property of AppLogUploadRequest.
     *
     * @return array|null The appLogCollectionRequests
     */
    public function getAppLogCollectionRequests()
    {
        if (array_key_exists("appLogCollectionRequests", $this->_propDict)) {
           return $this->_propDict["appLogCollectionRequests"];
        } else {
            return null;
        }
    }

    /**
    * Sets the appLogCollectionRequests
    * The collection property of AppLogUploadRequest.
    *
    * @param AppLogCollectionRequest[] $val The appLogCollectionRequests
    *
    * @return MobileAppTroubleshootingEvent
    */
    public function setAppLogCollectionRequests($val)
    {
        $this->_propDict["appLogCollectionRequests"] = $val;
        return $this;
    }

}
