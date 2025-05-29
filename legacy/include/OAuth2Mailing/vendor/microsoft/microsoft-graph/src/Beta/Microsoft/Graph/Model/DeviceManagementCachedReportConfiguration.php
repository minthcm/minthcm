<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* DeviceManagementCachedReportConfiguration File
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
* DeviceManagementCachedReportConfiguration class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class DeviceManagementCachedReportConfiguration extends Entity
{
    /**
    * Gets the expirationDateTime
    * Time that the cached report expires. This property is read-only.
    *
    * @return \DateTime|null The expirationDateTime
    */
    public function getExpirationDateTime()
    {
        if (array_key_exists("expirationDateTime", $this->_propDict)) {
            if (is_a($this->_propDict["expirationDateTime"], "\DateTime") || is_null($this->_propDict["expirationDateTime"])) {
                return $this->_propDict["expirationDateTime"];
            } else {
                $this->_propDict["expirationDateTime"] = new \DateTime($this->_propDict["expirationDateTime"]);
                return $this->_propDict["expirationDateTime"];
            }
        }
        return null;
    }

    /**
    * Sets the expirationDateTime
    * Time that the cached report expires. This property is read-only.
    *
    * @param \DateTime $val The expirationDateTime
    *
    * @return DeviceManagementCachedReportConfiguration
    */
    public function setExpirationDateTime($val)
    {
        $this->_propDict["expirationDateTime"] = $val;
        return $this;
    }

    /**
    * Gets the filter
    * Filters applied on report creation.
    *
    * @return string|null The filter
    */
    public function getFilter()
    {
        if (array_key_exists("filter", $this->_propDict)) {
            return $this->_propDict["filter"];
        } else {
            return null;
        }
    }

    /**
    * Sets the filter
    * Filters applied on report creation.
    *
    * @param string $val The filter
    *
    * @return DeviceManagementCachedReportConfiguration
    */
    public function setFilter($val)
    {
        $this->_propDict["filter"] = $val;
        return $this;
    }

    /**
    * Gets the lastRefreshDateTime
    * Time that the cached report was last refreshed. This property is read-only.
    *
    * @return \DateTime|null The lastRefreshDateTime
    */
    public function getLastRefreshDateTime()
    {
        if (array_key_exists("lastRefreshDateTime", $this->_propDict)) {
            if (is_a($this->_propDict["lastRefreshDateTime"], "\DateTime") || is_null($this->_propDict["lastRefreshDateTime"])) {
                return $this->_propDict["lastRefreshDateTime"];
            } else {
                $this->_propDict["lastRefreshDateTime"] = new \DateTime($this->_propDict["lastRefreshDateTime"]);
                return $this->_propDict["lastRefreshDateTime"];
            }
        }
        return null;
    }

    /**
    * Sets the lastRefreshDateTime
    * Time that the cached report was last refreshed. This property is read-only.
    *
    * @param \DateTime $val The lastRefreshDateTime
    *
    * @return DeviceManagementCachedReportConfiguration
    */
    public function setLastRefreshDateTime($val)
    {
        $this->_propDict["lastRefreshDateTime"] = $val;
        return $this;
    }

    /**
    * Gets the metadata
    * Caller-managed metadata associated with the report
    *
    * @return string|null The metadata
    */
    public function getMetadata()
    {
        if (array_key_exists("metadata", $this->_propDict)) {
            return $this->_propDict["metadata"];
        } else {
            return null;
        }
    }

    /**
    * Sets the metadata
    * Caller-managed metadata associated with the report
    *
    * @param string $val The metadata
    *
    * @return DeviceManagementCachedReportConfiguration
    */
    public function setMetadata($val)
    {
        $this->_propDict["metadata"] = $val;
        return $this;
    }

    /**
    * Gets the orderBy
    * Ordering of columns in the report
    *
    * @return array|null The orderBy
    */
    public function getOrderBy()
    {
        if (array_key_exists("orderBy", $this->_propDict)) {
            return $this->_propDict["orderBy"];
        } else {
            return null;
        }
    }

    /**
    * Sets the orderBy
    * Ordering of columns in the report
    *
    * @param string[] $val The orderBy
    *
    * @return DeviceManagementCachedReportConfiguration
    */
    public function setOrderBy($val)
    {
        $this->_propDict["orderBy"] = $val;
        return $this;
    }

    /**
    * Gets the reportName
    * Name of the report. This property is read-only.
    *
    * @return string|null The reportName
    */
    public function getReportName()
    {
        if (array_key_exists("reportName", $this->_propDict)) {
            return $this->_propDict["reportName"];
        } else {
            return null;
        }
    }

    /**
    * Sets the reportName
    * Name of the report. This property is read-only.
    *
    * @param string $val The reportName
    *
    * @return DeviceManagementCachedReportConfiguration
    */
    public function setReportName($val)
    {
        $this->_propDict["reportName"] = $val;
        return $this;
    }

    /**
    * Gets the select
    * Columns selected from the report
    *
    * @return array|null The select
    */
    public function getSelect()
    {
        if (array_key_exists("select", $this->_propDict)) {
            return $this->_propDict["select"];
        } else {
            return null;
        }
    }

    /**
    * Sets the select
    * Columns selected from the report
    *
    * @param string[] $val The select
    *
    * @return DeviceManagementCachedReportConfiguration
    */
    public function setSelect($val)
    {
        $this->_propDict["select"] = $val;
        return $this;
    }

    /**
    * Gets the status
    * Status of the cached report. This property is read-only. Possible values are: unknown, notStarted, inProgress, completed, failed.
    *
    * @return DeviceManagementReportStatus|null The status
    */
    public function getStatus()
    {
        if (array_key_exists("status", $this->_propDict)) {
            if (is_a($this->_propDict["status"], "\Beta\Microsoft\Graph\Model\DeviceManagementReportStatus") || is_null($this->_propDict["status"])) {
                return $this->_propDict["status"];
            } else {
                $this->_propDict["status"] = new DeviceManagementReportStatus($this->_propDict["status"]);
                return $this->_propDict["status"];
            }
        }
        return null;
    }

    /**
    * Sets the status
    * Status of the cached report. This property is read-only. Possible values are: unknown, notStarted, inProgress, completed, failed.
    *
    * @param DeviceManagementReportStatus $val The status
    *
    * @return DeviceManagementCachedReportConfiguration
    */
    public function setStatus($val)
    {
        $this->_propDict["status"] = $val;
        return $this;
    }

}
