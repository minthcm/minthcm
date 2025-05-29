<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* DeviceHealthScriptRemediationHistoryData File
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
* DeviceHealthScriptRemediationHistoryData class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class DeviceHealthScriptRemediationHistoryData extends Entity
{

    /**
    * Gets the date
    * The date on which devices were remediated by the device health script.
    *
    * @return \DateTime|null The date
    */
    public function getDate()
    {
        if (array_key_exists("date", $this->_propDict)) {
            if (is_a($this->_propDict["date"], "\DateTime") || is_null($this->_propDict["date"])) {
                return $this->_propDict["date"];
            } else {
                $this->_propDict["date"] = new \DateTime($this->_propDict["date"]);
                return $this->_propDict["date"];
            }
        }
        return null;
    }

    /**
    * Sets the date
    * The date on which devices were remediated by the device health script.
    *
    * @param \DateTime $val The value to assign to the date
    *
    * @return DeviceHealthScriptRemediationHistoryData The DeviceHealthScriptRemediationHistoryData
    */
    public function setDate($val)
    {
        $this->_propDict["date"] = $val;
         return $this;
    }
    /**
    * Gets the detectFailedDeviceCount
    * The number of devices for which the detection script found an issue.
    *
    * @return int|null The detectFailedDeviceCount
    */
    public function getDetectFailedDeviceCount()
    {
        if (array_key_exists("detectFailedDeviceCount", $this->_propDict)) {
            return $this->_propDict["detectFailedDeviceCount"];
        } else {
            return null;
        }
    }

    /**
    * Sets the detectFailedDeviceCount
    * The number of devices for which the detection script found an issue.
    *
    * @param int $val The value of the detectFailedDeviceCount
    *
    * @return DeviceHealthScriptRemediationHistoryData
    */
    public function setDetectFailedDeviceCount($val)
    {
        $this->_propDict["detectFailedDeviceCount"] = $val;
        return $this;
    }
    /**
    * Gets the noIssueDeviceCount
    * The number of devices that were found to have no issue by the device health script.
    *
    * @return int|null The noIssueDeviceCount
    */
    public function getNoIssueDeviceCount()
    {
        if (array_key_exists("noIssueDeviceCount", $this->_propDict)) {
            return $this->_propDict["noIssueDeviceCount"];
        } else {
            return null;
        }
    }

    /**
    * Sets the noIssueDeviceCount
    * The number of devices that were found to have no issue by the device health script.
    *
    * @param int $val The value of the noIssueDeviceCount
    *
    * @return DeviceHealthScriptRemediationHistoryData
    */
    public function setNoIssueDeviceCount($val)
    {
        $this->_propDict["noIssueDeviceCount"] = $val;
        return $this;
    }
    /**
    * Gets the remediatedDeviceCount
    * The number of devices remediated by the device health script.
    *
    * @return int|null The remediatedDeviceCount
    */
    public function getRemediatedDeviceCount()
    {
        if (array_key_exists("remediatedDeviceCount", $this->_propDict)) {
            return $this->_propDict["remediatedDeviceCount"];
        } else {
            return null;
        }
    }

    /**
    * Sets the remediatedDeviceCount
    * The number of devices remediated by the device health script.
    *
    * @param int $val The value of the remediatedDeviceCount
    *
    * @return DeviceHealthScriptRemediationHistoryData
    */
    public function setRemediatedDeviceCount($val)
    {
        $this->_propDict["remediatedDeviceCount"] = $val;
        return $this;
    }
}
