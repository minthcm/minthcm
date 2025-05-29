<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* UsersProcessingSummary File
* PHP version 7
*
* @category  Library
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
namespace Microsoft\Graph\IdentityGovernanceNamespace\Model;
/**
* UsersProcessingSummary class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class UsersProcessingSummary extends \Microsoft\Graph\Model\Entity
{
    /**
    * Gets the failedTasks
    *
    * @return int|null The failedTasks
    */
    public function getFailedTasks()
    {
        if (array_key_exists("failedTasks", $this->_propDict)) {
            return $this->_propDict["failedTasks"];
        } else {
            return null;
        }
    }

    /**
    * Sets the failedTasks
    *
    * @param int $val The value of the failedTasks
    *
    * @return UsersProcessingSummary
    */
    public function setFailedTasks($val)
    {
        $this->_propDict["failedTasks"] = $val;
        return $this;
    }
    /**
    * Gets the failedUsers
    *
    * @return int|null The failedUsers
    */
    public function getFailedUsers()
    {
        if (array_key_exists("failedUsers", $this->_propDict)) {
            return $this->_propDict["failedUsers"];
        } else {
            return null;
        }
    }

    /**
    * Sets the failedUsers
    *
    * @param int $val The value of the failedUsers
    *
    * @return UsersProcessingSummary
    */
    public function setFailedUsers($val)
    {
        $this->_propDict["failedUsers"] = $val;
        return $this;
    }
    /**
    * Gets the successfulUsers
    *
    * @return int|null The successfulUsers
    */
    public function getSuccessfulUsers()
    {
        if (array_key_exists("successfulUsers", $this->_propDict)) {
            return $this->_propDict["successfulUsers"];
        } else {
            return null;
        }
    }

    /**
    * Sets the successfulUsers
    *
    * @param int $val The value of the successfulUsers
    *
    * @return UsersProcessingSummary
    */
    public function setSuccessfulUsers($val)
    {
        $this->_propDict["successfulUsers"] = $val;
        return $this;
    }
    /**
    * Gets the totalTasks
    *
    * @return int|null The totalTasks
    */
    public function getTotalTasks()
    {
        if (array_key_exists("totalTasks", $this->_propDict)) {
            return $this->_propDict["totalTasks"];
        } else {
            return null;
        }
    }

    /**
    * Sets the totalTasks
    *
    * @param int $val The value of the totalTasks
    *
    * @return UsersProcessingSummary
    */
    public function setTotalTasks($val)
    {
        $this->_propDict["totalTasks"] = $val;
        return $this;
    }
    /**
    * Gets the totalUsers
    *
    * @return int|null The totalUsers
    */
    public function getTotalUsers()
    {
        if (array_key_exists("totalUsers", $this->_propDict)) {
            return $this->_propDict["totalUsers"];
        } else {
            return null;
        }
    }

    /**
    * Sets the totalUsers
    *
    * @param int $val The value of the totalUsers
    *
    * @return UsersProcessingSummary
    */
    public function setTotalUsers($val)
    {
        $this->_propDict["totalUsers"] = $val;
        return $this;
    }
}
