<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* EmailAuthenticationMethodConfiguration File
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
* EmailAuthenticationMethodConfiguration class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class EmailAuthenticationMethodConfiguration extends AuthenticationMethodConfiguration
{
    /**
    * Gets the allowExternalIdToUseEmailOtp
    * Determines whether email OTP is usable by external users for authentication. Possible values are: default, enabled, disabled, unknownFutureValue. Tenants in the default state who didn't use public preview will automatically have email OTP enabled beginning in October 2021.
    *
    * @return ExternalEmailOtpState|null The allowExternalIdToUseEmailOtp
    */
    public function getAllowExternalIdToUseEmailOtp()
    {
        if (array_key_exists("allowExternalIdToUseEmailOtp", $this->_propDict)) {
            if (is_a($this->_propDict["allowExternalIdToUseEmailOtp"], "\Beta\Microsoft\Graph\Model\ExternalEmailOtpState") || is_null($this->_propDict["allowExternalIdToUseEmailOtp"])) {
                return $this->_propDict["allowExternalIdToUseEmailOtp"];
            } else {
                $this->_propDict["allowExternalIdToUseEmailOtp"] = new ExternalEmailOtpState($this->_propDict["allowExternalIdToUseEmailOtp"]);
                return $this->_propDict["allowExternalIdToUseEmailOtp"];
            }
        }
        return null;
    }

    /**
    * Sets the allowExternalIdToUseEmailOtp
    * Determines whether email OTP is usable by external users for authentication. Possible values are: default, enabled, disabled, unknownFutureValue. Tenants in the default state who didn't use public preview will automatically have email OTP enabled beginning in October 2021.
    *
    * @param ExternalEmailOtpState $val The allowExternalIdToUseEmailOtp
    *
    * @return EmailAuthenticationMethodConfiguration
    */
    public function setAllowExternalIdToUseEmailOtp($val)
    {
        $this->_propDict["allowExternalIdToUseEmailOtp"] = $val;
        return $this;
    }


     /**
     * Gets the includeTargets
    * A collection of groups that are enabled to use the authentication method.
     *
     * @return array|null The includeTargets
     */
    public function getIncludeTargets()
    {
        if (array_key_exists("includeTargets", $this->_propDict)) {
           return $this->_propDict["includeTargets"];
        } else {
            return null;
        }
    }

    /**
    * Sets the includeTargets
    * A collection of groups that are enabled to use the authentication method.
    *
    * @param AuthenticationMethodTarget[] $val The includeTargets
    *
    * @return EmailAuthenticationMethodConfiguration
    */
    public function setIncludeTargets($val)
    {
        $this->_propDict["includeTargets"] = $val;
        return $this;
    }

}
