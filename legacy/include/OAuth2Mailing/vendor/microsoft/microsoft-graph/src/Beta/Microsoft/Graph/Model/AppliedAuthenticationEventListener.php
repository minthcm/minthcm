<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* AppliedAuthenticationEventListener File
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
* AppliedAuthenticationEventListener class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class AppliedAuthenticationEventListener extends Entity
{

    /**
    * Gets the eventType
    * The type of authentication event that triggered the custom authentication extension request. The possible values are: tokenIssuanceStart, pageRenderStart, unknownFutureValue.
    *
    * @return AuthenticationEventType|null The eventType
    */
    public function getEventType()
    {
        if (array_key_exists("eventType", $this->_propDict)) {
            if (is_a($this->_propDict["eventType"], "\Beta\Microsoft\Graph\Model\AuthenticationEventType") || is_null($this->_propDict["eventType"])) {
                return $this->_propDict["eventType"];
            } else {
                $this->_propDict["eventType"] = new AuthenticationEventType($this->_propDict["eventType"]);
                return $this->_propDict["eventType"];
            }
        }
        return null;
    }

    /**
    * Sets the eventType
    * The type of authentication event that triggered the custom authentication extension request. The possible values are: tokenIssuanceStart, pageRenderStart, unknownFutureValue.
    *
    * @param AuthenticationEventType $val The value to assign to the eventType
    *
    * @return AppliedAuthenticationEventListener The AppliedAuthenticationEventListener
    */
    public function setEventType($val)
    {
        $this->_propDict["eventType"] = $val;
         return $this;
    }
    /**
    * Gets the executedListenerId
    * ID of the authentication event listener that was executed.
    *
    * @return string|null The executedListenerId
    */
    public function getExecutedListenerId()
    {
        if (array_key_exists("executedListenerId", $this->_propDict)) {
            return $this->_propDict["executedListenerId"];
        } else {
            return null;
        }
    }

    /**
    * Sets the executedListenerId
    * ID of the authentication event listener that was executed.
    *
    * @param string $val The value of the executedListenerId
    *
    * @return AppliedAuthenticationEventListener
    */
    public function setExecutedListenerId($val)
    {
        $this->_propDict["executedListenerId"] = $val;
        return $this;
    }

    /**
    * Gets the handlerResult
    * The result from the listening client, such as an Azure Logic App and Azure Functions, of this authentication event.
    *
    * @return AuthenticationEventHandlerResult|null The handlerResult
    */
    public function getHandlerResult()
    {
        if (array_key_exists("handlerResult", $this->_propDict)) {
            if (is_a($this->_propDict["handlerResult"], "\Beta\Microsoft\Graph\Model\AuthenticationEventHandlerResult") || is_null($this->_propDict["handlerResult"])) {
                return $this->_propDict["handlerResult"];
            } else {
                $this->_propDict["handlerResult"] = new AuthenticationEventHandlerResult($this->_propDict["handlerResult"]);
                return $this->_propDict["handlerResult"];
            }
        }
        return null;
    }

    /**
    * Sets the handlerResult
    * The result from the listening client, such as an Azure Logic App and Azure Functions, of this authentication event.
    *
    * @param AuthenticationEventHandlerResult $val The value to assign to the handlerResult
    *
    * @return AppliedAuthenticationEventListener The AppliedAuthenticationEventListener
    */
    public function setHandlerResult($val)
    {
        $this->_propDict["handlerResult"] = $val;
         return $this;
    }
}
