<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* PlannerExternalPlanSource File
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
* PlannerExternalPlanSource class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class PlannerExternalPlanSource extends PlannerPlanCreation
{
    /**
    * Set the @odata.type since this type is immediately descended from an abstract
    * type that is referenced as the type in an entity.
    * @param array $propDict The property dictionary
    */
    public function __construct($propDict = array())
    {
        parent::__construct($propDict);
        $this->setODataType("#microsoft.graph.plannerExternalPlanSource");
    }

    /**
    * Gets the contextScenarioId
    * Nullable. An identifier for the scenario associated with this external source. This should be in reverse DNS format. For example, Contoso company owned application for customer support would have a value like 'com.constoso.customerSupport'.
    *
    * @return string|null The contextScenarioId
    */
    public function getContextScenarioId()
    {
        if (array_key_exists("contextScenarioId", $this->_propDict)) {
            return $this->_propDict["contextScenarioId"];
        } else {
            return null;
        }
    }

    /**
    * Sets the contextScenarioId
    * Nullable. An identifier for the scenario associated with this external source. This should be in reverse DNS format. For example, Contoso company owned application for customer support would have a value like 'com.constoso.customerSupport'.
    *
    * @param string $val The value of the contextScenarioId
    *
    * @return PlannerExternalPlanSource
    */
    public function setContextScenarioId($val)
    {
        $this->_propDict["contextScenarioId"] = $val;
        return $this;
    }
    /**
    * Gets the externalContextId
    * Nullable. The ID of the external entity's containing entity or context.
    *
    * @return string|null The externalContextId
    */
    public function getExternalContextId()
    {
        if (array_key_exists("externalContextId", $this->_propDict)) {
            return $this->_propDict["externalContextId"];
        } else {
            return null;
        }
    }

    /**
    * Sets the externalContextId
    * Nullable. The ID of the external entity's containing entity or context.
    *
    * @param string $val The value of the externalContextId
    *
    * @return PlannerExternalPlanSource
    */
    public function setExternalContextId($val)
    {
        $this->_propDict["externalContextId"] = $val;
        return $this;
    }
    /**
    * Gets the externalObjectId
    * Nullable. The ID of the entity that an external service associates with a plan.
    *
    * @return string|null The externalObjectId
    */
    public function getExternalObjectId()
    {
        if (array_key_exists("externalObjectId", $this->_propDict)) {
            return $this->_propDict["externalObjectId"];
        } else {
            return null;
        }
    }

    /**
    * Sets the externalObjectId
    * Nullable. The ID of the entity that an external service associates with a plan.
    *
    * @param string $val The value of the externalObjectId
    *
    * @return PlannerExternalPlanSource
    */
    public function setExternalObjectId($val)
    {
        $this->_propDict["externalObjectId"] = $val;
        return $this;
    }
}
