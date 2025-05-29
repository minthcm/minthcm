<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* UserExperienceAnalyticsAppHealthDeviceModelPerformance File
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
* UserExperienceAnalyticsAppHealthDeviceModelPerformance class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class UserExperienceAnalyticsAppHealthDeviceModelPerformance extends Entity
{
    /**
    * Gets the activeDeviceCount
    * The number of active devices for the model. Valid values 0 to 2147483647. Supports: $filter, $select, $OrderBy. Read-only. Valid values -2147483648 to 2147483647
    *
    * @return int|null The activeDeviceCount
    */
    public function getActiveDeviceCount()
    {
        if (array_key_exists("activeDeviceCount", $this->_propDict)) {
            return $this->_propDict["activeDeviceCount"];
        } else {
            return null;
        }
    }

    /**
    * Sets the activeDeviceCount
    * The number of active devices for the model. Valid values 0 to 2147483647. Supports: $filter, $select, $OrderBy. Read-only. Valid values -2147483648 to 2147483647
    *
    * @param int $val The activeDeviceCount
    *
    * @return UserExperienceAnalyticsAppHealthDeviceModelPerformance
    */
    public function setActiveDeviceCount($val)
    {
        $this->_propDict["activeDeviceCount"] = intval($val);
        return $this;
    }

    /**
    * Gets the deviceManufacturer
    * The manufacturer name of the device. Supports: $select, $OrderBy. Read-only.
    *
    * @return string|null The deviceManufacturer
    */
    public function getDeviceManufacturer()
    {
        if (array_key_exists("deviceManufacturer", $this->_propDict)) {
            return $this->_propDict["deviceManufacturer"];
        } else {
            return null;
        }
    }

    /**
    * Sets the deviceManufacturer
    * The manufacturer name of the device. Supports: $select, $OrderBy. Read-only.
    *
    * @param string $val The deviceManufacturer
    *
    * @return UserExperienceAnalyticsAppHealthDeviceModelPerformance
    */
    public function setDeviceManufacturer($val)
    {
        $this->_propDict["deviceManufacturer"] = $val;
        return $this;
    }

    /**
    * Gets the deviceModel
    * The model name of the device. Supports: $select, $OrderBy. Read-only.
    *
    * @return string|null The deviceModel
    */
    public function getDeviceModel()
    {
        if (array_key_exists("deviceModel", $this->_propDict)) {
            return $this->_propDict["deviceModel"];
        } else {
            return null;
        }
    }

    /**
    * Sets the deviceModel
    * The model name of the device. Supports: $select, $OrderBy. Read-only.
    *
    * @param string $val The deviceModel
    *
    * @return UserExperienceAnalyticsAppHealthDeviceModelPerformance
    */
    public function setDeviceModel($val)
    {
        $this->_propDict["deviceModel"] = $val;
        return $this;
    }

    /**
    * Gets the healthStatus
    * The health state of the user experience analytics model. Possible values are: unknown, insufficientData, needsAttention, meetingGoals. Unknown by default. Supports: $filter, $select, $OrderBy. Read-only. Possible values are: unknown, insufficientData, needsAttention, meetingGoals, unknownFutureValue.
    *
    * @return UserExperienceAnalyticsHealthState|null The healthStatus
    */
    public function getHealthStatus()
    {
        if (array_key_exists("healthStatus", $this->_propDict)) {
            if (is_a($this->_propDict["healthStatus"], "\Beta\Microsoft\Graph\Model\UserExperienceAnalyticsHealthState") || is_null($this->_propDict["healthStatus"])) {
                return $this->_propDict["healthStatus"];
            } else {
                $this->_propDict["healthStatus"] = new UserExperienceAnalyticsHealthState($this->_propDict["healthStatus"]);
                return $this->_propDict["healthStatus"];
            }
        }
        return null;
    }

    /**
    * Sets the healthStatus
    * The health state of the user experience analytics model. Possible values are: unknown, insufficientData, needsAttention, meetingGoals. Unknown by default. Supports: $filter, $select, $OrderBy. Read-only. Possible values are: unknown, insufficientData, needsAttention, meetingGoals, unknownFutureValue.
    *
    * @param UserExperienceAnalyticsHealthState $val The healthStatus
    *
    * @return UserExperienceAnalyticsAppHealthDeviceModelPerformance
    */
    public function setHealthStatus($val)
    {
        $this->_propDict["healthStatus"] = $val;
        return $this;
    }

    /**
    * Gets the meanTimeToFailureInMinutes
    * The mean time to failure for the application in minutes. Valid values 0 to 2147483647. Supports: $filter, $select, $OrderBy. Read-only. Valid values -2147483648 to 2147483647
    *
    * @return int|null The meanTimeToFailureInMinutes
    */
    public function getMeanTimeToFailureInMinutes()
    {
        if (array_key_exists("meanTimeToFailureInMinutes", $this->_propDict)) {
            return $this->_propDict["meanTimeToFailureInMinutes"];
        } else {
            return null;
        }
    }

    /**
    * Sets the meanTimeToFailureInMinutes
    * The mean time to failure for the application in minutes. Valid values 0 to 2147483647. Supports: $filter, $select, $OrderBy. Read-only. Valid values -2147483648 to 2147483647
    *
    * @param int $val The meanTimeToFailureInMinutes
    *
    * @return UserExperienceAnalyticsAppHealthDeviceModelPerformance
    */
    public function setMeanTimeToFailureInMinutes($val)
    {
        $this->_propDict["meanTimeToFailureInMinutes"] = intval($val);
        return $this;
    }

    /**
    * Gets the modelAppHealthScore
    * The application health score of the device model. Valid values 0 to 100. Supports: $filter, $select, $OrderBy. Read-only. Valid values -1.79769313486232E+308 to 1.79769313486232E+308
    *
    * @return float|null The modelAppHealthScore
    */
    public function getModelAppHealthScore()
    {
        if (array_key_exists("modelAppHealthScore", $this->_propDict)) {
            return $this->_propDict["modelAppHealthScore"];
        } else {
            return null;
        }
    }

    /**
    * Sets the modelAppHealthScore
    * The application health score of the device model. Valid values 0 to 100. Supports: $filter, $select, $OrderBy. Read-only. Valid values -1.79769313486232E+308 to 1.79769313486232E+308
    *
    * @param float $val The modelAppHealthScore
    *
    * @return UserExperienceAnalyticsAppHealthDeviceModelPerformance
    */
    public function setModelAppHealthScore($val)
    {
        $this->_propDict["modelAppHealthScore"] = floatval($val);
        return $this;
    }

}
