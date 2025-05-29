<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* AccessPackageResourceRoleScope File
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
* AccessPackageResourceRoleScope class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class AccessPackageResourceRoleScope extends Entity
{
    /**
    * Gets the createdBy
    *
    * @return string|null The createdBy
    */
    public function getCreatedBy()
    {
        if (array_key_exists("createdBy", $this->_propDict)) {
            return $this->_propDict["createdBy"];
        } else {
            return null;
        }
    }

    /**
    * Sets the createdBy
    *
    * @param string $val The createdBy
    *
    * @return AccessPackageResourceRoleScope
    */
    public function setCreatedBy($val)
    {
        $this->_propDict["createdBy"] = $val;
        return $this;
    }

    /**
    * Gets the createdDateTime
    * The Timestamp type represents date and time information using ISO 8601 format and is always in UTC time. For example, midnight UTC on Jan 1, 2014 is 2014-01-01T00:00:00Z
    *
    * @return \DateTime|null The createdDateTime
    */
    public function getCreatedDateTime()
    {
        if (array_key_exists("createdDateTime", $this->_propDict)) {
            if (is_a($this->_propDict["createdDateTime"], "\DateTime") || is_null($this->_propDict["createdDateTime"])) {
                return $this->_propDict["createdDateTime"];
            } else {
                $this->_propDict["createdDateTime"] = new \DateTime($this->_propDict["createdDateTime"]);
                return $this->_propDict["createdDateTime"];
            }
        }
        return null;
    }

    /**
    * Sets the createdDateTime
    * The Timestamp type represents date and time information using ISO 8601 format and is always in UTC time. For example, midnight UTC on Jan 1, 2014 is 2014-01-01T00:00:00Z
    *
    * @param \DateTime $val The createdDateTime
    *
    * @return AccessPackageResourceRoleScope
    */
    public function setCreatedDateTime($val)
    {
        $this->_propDict["createdDateTime"] = $val;
        return $this;
    }

    /**
    * Gets the modifiedBy
    *
    * @return string|null The modifiedBy
    */
    public function getModifiedBy()
    {
        if (array_key_exists("modifiedBy", $this->_propDict)) {
            return $this->_propDict["modifiedBy"];
        } else {
            return null;
        }
    }

    /**
    * Sets the modifiedBy
    *
    * @param string $val The modifiedBy
    *
    * @return AccessPackageResourceRoleScope
    */
    public function setModifiedBy($val)
    {
        $this->_propDict["modifiedBy"] = $val;
        return $this;
    }

    /**
    * Gets the modifiedDateTime
    * The Timestamp type represents date and time information using ISO 8601 format and is always in UTC time. For example, midnight UTC on Jan 1, 2014 is 2014-01-01T00:00:00Z
    *
    * @return \DateTime|null The modifiedDateTime
    */
    public function getModifiedDateTime()
    {
        if (array_key_exists("modifiedDateTime", $this->_propDict)) {
            if (is_a($this->_propDict["modifiedDateTime"], "\DateTime") || is_null($this->_propDict["modifiedDateTime"])) {
                return $this->_propDict["modifiedDateTime"];
            } else {
                $this->_propDict["modifiedDateTime"] = new \DateTime($this->_propDict["modifiedDateTime"]);
                return $this->_propDict["modifiedDateTime"];
            }
        }
        return null;
    }

    /**
    * Sets the modifiedDateTime
    * The Timestamp type represents date and time information using ISO 8601 format and is always in UTC time. For example, midnight UTC on Jan 1, 2014 is 2014-01-01T00:00:00Z
    *
    * @param \DateTime $val The modifiedDateTime
    *
    * @return AccessPackageResourceRoleScope
    */
    public function setModifiedDateTime($val)
    {
        $this->_propDict["modifiedDateTime"] = $val;
        return $this;
    }

    /**
    * Gets the accessPackageResourceRole
    * Read-only. Nullable. Supports $expand.
    *
    * @return AccessPackageResourceRole|null The accessPackageResourceRole
    */
    public function getAccessPackageResourceRole()
    {
        if (array_key_exists("accessPackageResourceRole", $this->_propDict)) {
            if (is_a($this->_propDict["accessPackageResourceRole"], "\Beta\Microsoft\Graph\Model\AccessPackageResourceRole") || is_null($this->_propDict["accessPackageResourceRole"])) {
                return $this->_propDict["accessPackageResourceRole"];
            } else {
                $this->_propDict["accessPackageResourceRole"] = new AccessPackageResourceRole($this->_propDict["accessPackageResourceRole"]);
                return $this->_propDict["accessPackageResourceRole"];
            }
        }
        return null;
    }

    /**
    * Sets the accessPackageResourceRole
    * Read-only. Nullable. Supports $expand.
    *
    * @param AccessPackageResourceRole $val The accessPackageResourceRole
    *
    * @return AccessPackageResourceRoleScope
    */
    public function setAccessPackageResourceRole($val)
    {
        $this->_propDict["accessPackageResourceRole"] = $val;
        return $this;
    }

    /**
    * Gets the accessPackageResourceScope
    *
    * @return AccessPackageResourceScope|null The accessPackageResourceScope
    */
    public function getAccessPackageResourceScope()
    {
        if (array_key_exists("accessPackageResourceScope", $this->_propDict)) {
            if (is_a($this->_propDict["accessPackageResourceScope"], "\Beta\Microsoft\Graph\Model\AccessPackageResourceScope") || is_null($this->_propDict["accessPackageResourceScope"])) {
                return $this->_propDict["accessPackageResourceScope"];
            } else {
                $this->_propDict["accessPackageResourceScope"] = new AccessPackageResourceScope($this->_propDict["accessPackageResourceScope"]);
                return $this->_propDict["accessPackageResourceScope"];
            }
        }
        return null;
    }

    /**
    * Sets the accessPackageResourceScope
    *
    * @param AccessPackageResourceScope $val The accessPackageResourceScope
    *
    * @return AccessPackageResourceRoleScope
    */
    public function setAccessPackageResourceScope($val)
    {
        $this->_propDict["accessPackageResourceScope"] = $val;
        return $this;
    }

}
