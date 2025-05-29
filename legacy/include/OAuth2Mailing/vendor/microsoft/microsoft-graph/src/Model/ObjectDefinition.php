<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* ObjectDefinition File
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
* ObjectDefinition class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class ObjectDefinition extends Entity
{

    /**
    * Gets the attributes
    * Defines attributes of the object.
    *
    * @return AttributeDefinition|null The attributes
    */
    public function getAttributes()
    {
        if (array_key_exists("attributes", $this->_propDict)) {
            if (is_a($this->_propDict["attributes"], "\Microsoft\Graph\Model\AttributeDefinition") || is_null($this->_propDict["attributes"])) {
                return $this->_propDict["attributes"];
            } else {
                $this->_propDict["attributes"] = new AttributeDefinition($this->_propDict["attributes"]);
                return $this->_propDict["attributes"];
            }
        }
        return null;
    }

    /**
    * Sets the attributes
    * Defines attributes of the object.
    *
    * @param AttributeDefinition $val The value to assign to the attributes
    *
    * @return ObjectDefinition The ObjectDefinition
    */
    public function setAttributes($val)
    {
        $this->_propDict["attributes"] = $val;
         return $this;
    }

    /**
    * Gets the metadata
    * Metadata for the given object.
    *
    * @return ObjectDefinitionMetadataEntry|null The metadata
    */
    public function getMetadata()
    {
        if (array_key_exists("metadata", $this->_propDict)) {
            if (is_a($this->_propDict["metadata"], "\Microsoft\Graph\Model\ObjectDefinitionMetadataEntry") || is_null($this->_propDict["metadata"])) {
                return $this->_propDict["metadata"];
            } else {
                $this->_propDict["metadata"] = new ObjectDefinitionMetadataEntry($this->_propDict["metadata"]);
                return $this->_propDict["metadata"];
            }
        }
        return null;
    }

    /**
    * Sets the metadata
    * Metadata for the given object.
    *
    * @param ObjectDefinitionMetadataEntry $val The value to assign to the metadata
    *
    * @return ObjectDefinition The ObjectDefinition
    */
    public function setMetadata($val)
    {
        $this->_propDict["metadata"] = $val;
         return $this;
    }
    /**
    * Gets the name
    * Name of the object. Must be unique within a directory definition. Not nullable.
    *
    * @return string|null The name
    */
    public function getName()
    {
        if (array_key_exists("name", $this->_propDict)) {
            return $this->_propDict["name"];
        } else {
            return null;
        }
    }

    /**
    * Sets the name
    * Name of the object. Must be unique within a directory definition. Not nullable.
    *
    * @param string $val The value of the name
    *
    * @return ObjectDefinition
    */
    public function setName($val)
    {
        $this->_propDict["name"] = $val;
        return $this;
    }
    /**
    * Gets the supportedApis
    * The API that the provisioning service queries to retrieve data for synchronization.
    *
    * @return string|null The supportedApis
    */
    public function getSupportedApis()
    {
        if (array_key_exists("supportedApis", $this->_propDict)) {
            return $this->_propDict["supportedApis"];
        } else {
            return null;
        }
    }

    /**
    * Sets the supportedApis
    * The API that the provisioning service queries to retrieve data for synchronization.
    *
    * @param string $val The value of the supportedApis
    *
    * @return ObjectDefinition
    */
    public function setSupportedApis($val)
    {
        $this->_propDict["supportedApis"] = $val;
        return $this;
    }
}
