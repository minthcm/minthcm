<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* CloudPcFrontLineServicePlan File
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
* CloudPcFrontLineServicePlan class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class CloudPcFrontLineServicePlan extends Entity
{
    /**
    * Gets the displayName
    * The display name of the front-line service plan. For example, 2vCPU/8GB/128GB Front-line or 4vCPU/16GB/256GB Front-line.
    *
    * @return string|null The displayName
    */
    public function getDisplayName()
    {
        if (array_key_exists("displayName", $this->_propDict)) {
            return $this->_propDict["displayName"];
        } else {
            return null;
        }
    }

    /**
    * Sets the displayName
    * The display name of the front-line service plan. For example, 2vCPU/8GB/128GB Front-line or 4vCPU/16GB/256GB Front-line.
    *
    * @param string $val The displayName
    *
    * @return CloudPcFrontLineServicePlan
    */
    public function setDisplayName($val)
    {
        $this->_propDict["displayName"] = $val;
        return $this;
    }

    /**
    * Gets the totalCount
    * The total number of front-line service plans purchased by the customer.
    *
    * @return int|null The totalCount
    */
    public function getTotalCount()
    {
        if (array_key_exists("totalCount", $this->_propDict)) {
            return $this->_propDict["totalCount"];
        } else {
            return null;
        }
    }

    /**
    * Sets the totalCount
    * The total number of front-line service plans purchased by the customer.
    *
    * @param int $val The totalCount
    *
    * @return CloudPcFrontLineServicePlan
    */
    public function setTotalCount($val)
    {
        $this->_propDict["totalCount"] = intval($val);
        return $this;
    }

    /**
    * Gets the usedCount
    * The number of service plans that have been used for the account.
    *
    * @return int|null The usedCount
    */
    public function getUsedCount()
    {
        if (array_key_exists("usedCount", $this->_propDict)) {
            return $this->_propDict["usedCount"];
        } else {
            return null;
        }
    }

    /**
    * Sets the usedCount
    * The number of service plans that have been used for the account.
    *
    * @param int $val The usedCount
    *
    * @return CloudPcFrontLineServicePlan
    */
    public function setUsedCount($val)
    {
        $this->_propDict["usedCount"] = intval($val);
        return $this;
    }

}
