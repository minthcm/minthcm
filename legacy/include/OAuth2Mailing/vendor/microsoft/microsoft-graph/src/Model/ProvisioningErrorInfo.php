<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* ProvisioningErrorInfo File
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
* ProvisioningErrorInfo class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class ProvisioningErrorInfo extends Entity
{
    /**
    * Gets the additionalDetails
    * Additional details if there's error.
    *
    * @return string|null The additionalDetails
    */
    public function getAdditionalDetails()
    {
        if (array_key_exists("additionalDetails", $this->_propDict)) {
            return $this->_propDict["additionalDetails"];
        } else {
            return null;
        }
    }

    /**
    * Sets the additionalDetails
    * Additional details if there's error.
    *
    * @param string $val The value of the additionalDetails
    *
    * @return ProvisioningErrorInfo
    */
    public function setAdditionalDetails($val)
    {
        $this->_propDict["additionalDetails"] = $val;
        return $this;
    }

    /**
    * Gets the errorCategory
    * Categorizes the error code. Possible values are failure, nonServiceFailure, success, unknownFutureValue
    *
    * @return ProvisioningStatusErrorCategory|null The errorCategory
    */
    public function getErrorCategory()
    {
        if (array_key_exists("errorCategory", $this->_propDict)) {
            if (is_a($this->_propDict["errorCategory"], "\Microsoft\Graph\Model\ProvisioningStatusErrorCategory") || is_null($this->_propDict["errorCategory"])) {
                return $this->_propDict["errorCategory"];
            } else {
                $this->_propDict["errorCategory"] = new ProvisioningStatusErrorCategory($this->_propDict["errorCategory"]);
                return $this->_propDict["errorCategory"];
            }
        }
        return null;
    }

    /**
    * Sets the errorCategory
    * Categorizes the error code. Possible values are failure, nonServiceFailure, success, unknownFutureValue
    *
    * @param ProvisioningStatusErrorCategory $val The value to assign to the errorCategory
    *
    * @return ProvisioningErrorInfo The ProvisioningErrorInfo
    */
    public function setErrorCategory($val)
    {
        $this->_propDict["errorCategory"] = $val;
         return $this;
    }
    /**
    * Gets the errorCode
    * Unique error code if any occurred. Learn more
    *
    * @return string|null The errorCode
    */
    public function getErrorCode()
    {
        if (array_key_exists("errorCode", $this->_propDict)) {
            return $this->_propDict["errorCode"];
        } else {
            return null;
        }
    }

    /**
    * Sets the errorCode
    * Unique error code if any occurred. Learn more
    *
    * @param string $val The value of the errorCode
    *
    * @return ProvisioningErrorInfo
    */
    public function setErrorCode($val)
    {
        $this->_propDict["errorCode"] = $val;
        return $this;
    }
    /**
    * Gets the reason
    * Summarizes the status and describes why the status happened.
    *
    * @return string|null The reason
    */
    public function getReason()
    {
        if (array_key_exists("reason", $this->_propDict)) {
            return $this->_propDict["reason"];
        } else {
            return null;
        }
    }

    /**
    * Sets the reason
    * Summarizes the status and describes why the status happened.
    *
    * @param string $val The value of the reason
    *
    * @return ProvisioningErrorInfo
    */
    public function setReason($val)
    {
        $this->_propDict["reason"] = $val;
        return $this;
    }
    /**
    * Gets the recommendedAction
    * Provides the resolution for the corresponding error.
    *
    * @return string|null The recommendedAction
    */
    public function getRecommendedAction()
    {
        if (array_key_exists("recommendedAction", $this->_propDict)) {
            return $this->_propDict["recommendedAction"];
        } else {
            return null;
        }
    }

    /**
    * Sets the recommendedAction
    * Provides the resolution for the corresponding error.
    *
    * @param string $val The value of the recommendedAction
    *
    * @return ProvisioningErrorInfo
    */
    public function setRecommendedAction($val)
    {
        $this->_propDict["recommendedAction"] = $val;
        return $this;
    }
}
