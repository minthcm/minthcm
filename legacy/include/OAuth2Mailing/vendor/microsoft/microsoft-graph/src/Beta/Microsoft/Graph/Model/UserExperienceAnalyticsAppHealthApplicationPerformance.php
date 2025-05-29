<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* UserExperienceAnalyticsAppHealthApplicationPerformance File
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
* UserExperienceAnalyticsAppHealthApplicationPerformance class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class UserExperienceAnalyticsAppHealthApplicationPerformance extends Entity
{
    /**
    * Gets the activeDeviceCount
    * The health score of the application. Valid values 0 to 100. Supports: $filter, $select, $OrderBy. Read-only. Valid values -2147483648 to 2147483647
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
    * The health score of the application. Valid values 0 to 100. Supports: $filter, $select, $OrderBy. Read-only. Valid values -2147483648 to 2147483647
    *
    * @param int $val The activeDeviceCount
    *
    * @return UserExperienceAnalyticsAppHealthApplicationPerformance
    */
    public function setActiveDeviceCount($val)
    {
        $this->_propDict["activeDeviceCount"] = intval($val);
        return $this;
    }

    /**
    * Gets the appCrashCount
    * The number of crashes for the application. Valid values 0 to 2147483647. Supports: $select, $OrderBy. Read-only. Valid values -2147483648 to 2147483647
    *
    * @return int|null The appCrashCount
    */
    public function getAppCrashCount()
    {
        if (array_key_exists("appCrashCount", $this->_propDict)) {
            return $this->_propDict["appCrashCount"];
        } else {
            return null;
        }
    }

    /**
    * Sets the appCrashCount
    * The number of crashes for the application. Valid values 0 to 2147483647. Supports: $select, $OrderBy. Read-only. Valid values -2147483648 to 2147483647
    *
    * @param int $val The appCrashCount
    *
    * @return UserExperienceAnalyticsAppHealthApplicationPerformance
    */
    public function setAppCrashCount($val)
    {
        $this->_propDict["appCrashCount"] = intval($val);
        return $this;
    }

    /**
    * Gets the appDisplayName
    * The friendly name of the application. Possible values are: Outlook, Excel. Supports: $select, $OrderBy. Read-only.
    *
    * @return string|null The appDisplayName
    */
    public function getAppDisplayName()
    {
        if (array_key_exists("appDisplayName", $this->_propDict)) {
            return $this->_propDict["appDisplayName"];
        } else {
            return null;
        }
    }

    /**
    * Sets the appDisplayName
    * The friendly name of the application. Possible values are: Outlook, Excel. Supports: $select, $OrderBy. Read-only.
    *
    * @param string $val The appDisplayName
    *
    * @return UserExperienceAnalyticsAppHealthApplicationPerformance
    */
    public function setAppDisplayName($val)
    {
        $this->_propDict["appDisplayName"] = $val;
        return $this;
    }

    /**
    * Gets the appHangCount
    * The number of hangs for the application. Supports: $select, $OrderBy. Read-only. Valid values -2147483648 to 2147483647
    *
    * @return int|null The appHangCount
    */
    public function getAppHangCount()
    {
        if (array_key_exists("appHangCount", $this->_propDict)) {
            return $this->_propDict["appHangCount"];
        } else {
            return null;
        }
    }

    /**
    * Sets the appHangCount
    * The number of hangs for the application. Supports: $select, $OrderBy. Read-only. Valid values -2147483648 to 2147483647
    *
    * @param int $val The appHangCount
    *
    * @return UserExperienceAnalyticsAppHealthApplicationPerformance
    */
    public function setAppHangCount($val)
    {
        $this->_propDict["appHangCount"] = intval($val);
        return $this;
    }

    /**
    * Gets the appHealthScore
    * The health score of the application. Valid values 0 to 100. Supports: $filter, $select, $OrderBy. Read-only. Valid values -1.79769313486232E+308 to 1.79769313486232E+308
    *
    * @return float|null The appHealthScore
    */
    public function getAppHealthScore()
    {
        if (array_key_exists("appHealthScore", $this->_propDict)) {
            return $this->_propDict["appHealthScore"];
        } else {
            return null;
        }
    }

    /**
    * Sets the appHealthScore
    * The health score of the application. Valid values 0 to 100. Supports: $filter, $select, $OrderBy. Read-only. Valid values -1.79769313486232E+308 to 1.79769313486232E+308
    *
    * @param float $val The appHealthScore
    *
    * @return UserExperienceAnalyticsAppHealthApplicationPerformance
    */
    public function setAppHealthScore($val)
    {
        $this->_propDict["appHealthScore"] = floatval($val);
        return $this;
    }

    /**
    * Gets the appName
    * The name of the application. Possible values are: outlook.exe, excel.exe. Supports: $select, $OrderBy. Read-only.
    *
    * @return string|null The appName
    */
    public function getAppName()
    {
        if (array_key_exists("appName", $this->_propDict)) {
            return $this->_propDict["appName"];
        } else {
            return null;
        }
    }

    /**
    * Sets the appName
    * The name of the application. Possible values are: outlook.exe, excel.exe. Supports: $select, $OrderBy. Read-only.
    *
    * @param string $val The appName
    *
    * @return UserExperienceAnalyticsAppHealthApplicationPerformance
    */
    public function setAppName($val)
    {
        $this->_propDict["appName"] = $val;
        return $this;
    }

    /**
    * Gets the appPublisher
    * The publisher of the application. Supports: $select, $OrderBy. Read-only.
    *
    * @return string|null The appPublisher
    */
    public function getAppPublisher()
    {
        if (array_key_exists("appPublisher", $this->_propDict)) {
            return $this->_propDict["appPublisher"];
        } else {
            return null;
        }
    }

    /**
    * Sets the appPublisher
    * The publisher of the application. Supports: $select, $OrderBy. Read-only.
    *
    * @param string $val The appPublisher
    *
    * @return UserExperienceAnalyticsAppHealthApplicationPerformance
    */
    public function setAppPublisher($val)
    {
        $this->_propDict["appPublisher"] = $val;
        return $this;
    }

    /**
    * Gets the appUsageDuration
    * The total usage time of the application in minutes. Valid values 0 to 2147483647. Supports: $select, $OrderBy. Read-only. Valid values -2147483648 to 2147483647
    *
    * @return int|null The appUsageDuration
    */
    public function getAppUsageDuration()
    {
        if (array_key_exists("appUsageDuration", $this->_propDict)) {
            return $this->_propDict["appUsageDuration"];
        } else {
            return null;
        }
    }

    /**
    * Sets the appUsageDuration
    * The total usage time of the application in minutes. Valid values 0 to 2147483647. Supports: $select, $OrderBy. Read-only. Valid values -2147483648 to 2147483647
    *
    * @param int $val The appUsageDuration
    *
    * @return UserExperienceAnalyticsAppHealthApplicationPerformance
    */
    public function setAppUsageDuration($val)
    {
        $this->_propDict["appUsageDuration"] = intval($val);
        return $this;
    }

    /**
    * Gets the meanTimeToFailureInMinutes
    * The mean time to failure for the application in minutes. Valid values 0 to 2147483647. Supports: $select, $OrderBy. Read-only. Valid values -2147483648 to 2147483647
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
    * The mean time to failure for the application in minutes. Valid values 0 to 2147483647. Supports: $select, $OrderBy. Read-only. Valid values -2147483648 to 2147483647
    *
    * @param int $val The meanTimeToFailureInMinutes
    *
    * @return UserExperienceAnalyticsAppHealthApplicationPerformance
    */
    public function setMeanTimeToFailureInMinutes($val)
    {
        $this->_propDict["meanTimeToFailureInMinutes"] = intval($val);
        return $this;
    }

}
