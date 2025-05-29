<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* ObjectMappingMetadataEntry File
* PHP version 7
*
* @category  Library
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
namespace Microsoft\Graph\Model;
/**
* ObjectMappingMetadataEntry class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class ObjectMappingMetadataEntry extends Entity
{

    /**
    * Gets the key
    * Possible values are: EscrowBehavior, DisableMonitoringForChanges, OriginalJoiningProperty, Disposition, IsCustomerDefined, ExcludeFromReporting, Unsynchronized.
    *
    * @return ObjectMappingMetadata|null The key
    */
    public function getKey()
    {
        if (array_key_exists("key", $this->_propDict)) {
            if (is_a($this->_propDict["key"], "\Microsoft\Graph\Model\ObjectMappingMetadata") || is_null($this->_propDict["key"])) {
                return $this->_propDict["key"];
            } else {
                $this->_propDict["key"] = new ObjectMappingMetadata($this->_propDict["key"]);
                return $this->_propDict["key"];
            }
        }
        return null;
    }

    /**
    * Sets the key
    * Possible values are: EscrowBehavior, DisableMonitoringForChanges, OriginalJoiningProperty, Disposition, IsCustomerDefined, ExcludeFromReporting, Unsynchronized.
    *
    * @param ObjectMappingMetadata $val The value to assign to the key
    *
    * @return ObjectMappingMetadataEntry The ObjectMappingMetadataEntry
    */
    public function setKey($val)
    {
        $this->_propDict["key"] = $val;
         return $this;
    }
    /**
    * Gets the value
    * Value of the metadata property.
    *
    * @return string|null The value
    */
    public function getValue()
    {
        if (array_key_exists("value", $this->_propDict)) {
            return $this->_propDict["value"];
        } else {
            return null;
        }
    }

    /**
    * Sets the value
    * Value of the metadata property.
    *
    * @param string $val The value of the value
    *
    * @return ObjectMappingMetadataEntry
    */
    public function setValue($val)
    {
        $this->_propDict["value"] = $val;
        return $this;
    }
}
