<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* RelatedPerson File
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
* RelatedPerson class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class RelatedPerson extends Entity
{
    /**
    * Gets the displayName
    * Name of the person.
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
    * Name of the person.
    *
    * @param string $val The value of the displayName
    *
    * @return RelatedPerson
    */
    public function setDisplayName($val)
    {
        $this->_propDict["displayName"] = $val;
        return $this;
    }

    /**
    * Gets the relationship
    * Possible values are: manager, colleague, directReport, dotLineReport, assistant, dotLineManager, alternateContact, friend, spouse, sibling, child, parent, sponsor, emergencyContact, other, unknownFutureValue.
    *
    * @return PersonRelationship|null The relationship
    */
    public function getRelationship()
    {
        if (array_key_exists("relationship", $this->_propDict)) {
            if (is_a($this->_propDict["relationship"], "\Beta\Microsoft\Graph\Model\PersonRelationship") || is_null($this->_propDict["relationship"])) {
                return $this->_propDict["relationship"];
            } else {
                $this->_propDict["relationship"] = new PersonRelationship($this->_propDict["relationship"]);
                return $this->_propDict["relationship"];
            }
        }
        return null;
    }

    /**
    * Sets the relationship
    * Possible values are: manager, colleague, directReport, dotLineReport, assistant, dotLineManager, alternateContact, friend, spouse, sibling, child, parent, sponsor, emergencyContact, other, unknownFutureValue.
    *
    * @param PersonRelationship $val The value to assign to the relationship
    *
    * @return RelatedPerson The RelatedPerson
    */
    public function setRelationship($val)
    {
        $this->_propDict["relationship"] = $val;
         return $this;
    }
    /**
    * Gets the userId
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
    *
    * @param string $val The value of the userId
    *
    * @return RelatedPerson
    */
    public function setUserId($val)
    {
        $this->_propDict["userId"] = $val;
        return $this;
    }
    /**
    * Gets the userPrincipalName
    * Email address or reference to person within organization.
    *
    * @return string|null The userPrincipalName
    */
    public function getUserPrincipalName()
    {
        if (array_key_exists("userPrincipalName", $this->_propDict)) {
            return $this->_propDict["userPrincipalName"];
        } else {
            return null;
        }
    }

    /**
    * Sets the userPrincipalName
    * Email address or reference to person within organization.
    *
    * @param string $val The value of the userPrincipalName
    *
    * @return RelatedPerson
    */
    public function setUserPrincipalName($val)
    {
        $this->_propDict["userPrincipalName"] = $val;
        return $this;
    }
}
