<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* NotifyUserAction File
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
* NotifyUserAction class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class NotifyUserAction extends DlpActionInfo
{

    /**
    * Gets the actionLastModifiedDateTime
    *
    * @return \DateTime|null The actionLastModifiedDateTime
    */
    public function getActionLastModifiedDateTime()
    {
        if (array_key_exists("actionLastModifiedDateTime", $this->_propDict)) {
            if (is_a($this->_propDict["actionLastModifiedDateTime"], "\DateTime") || is_null($this->_propDict["actionLastModifiedDateTime"])) {
                return $this->_propDict["actionLastModifiedDateTime"];
            } else {
                $this->_propDict["actionLastModifiedDateTime"] = new \DateTime($this->_propDict["actionLastModifiedDateTime"]);
                return $this->_propDict["actionLastModifiedDateTime"];
            }
        }
        return null;
    }

    /**
    * Sets the actionLastModifiedDateTime
    *
    * @param \DateTime $val The value to assign to the actionLastModifiedDateTime
    *
    * @return NotifyUserAction The NotifyUserAction
    */
    public function setActionLastModifiedDateTime($val)
    {
        $this->_propDict["actionLastModifiedDateTime"] = $val;
         return $this;
    }
    /**
    * Gets the emailText
    *
    * @return string|null The emailText
    */
    public function getEmailText()
    {
        if (array_key_exists("emailText", $this->_propDict)) {
            return $this->_propDict["emailText"];
        } else {
            return null;
        }
    }

    /**
    * Sets the emailText
    *
    * @param string $val The value of the emailText
    *
    * @return NotifyUserAction
    */
    public function setEmailText($val)
    {
        $this->_propDict["emailText"] = $val;
        return $this;
    }
    /**
    * Gets the policyTip
    *
    * @return string|null The policyTip
    */
    public function getPolicyTip()
    {
        if (array_key_exists("policyTip", $this->_propDict)) {
            return $this->_propDict["policyTip"];
        } else {
            return null;
        }
    }

    /**
    * Sets the policyTip
    *
    * @param string $val The value of the policyTip
    *
    * @return NotifyUserAction
    */
    public function setPolicyTip($val)
    {
        $this->_propDict["policyTip"] = $val;
        return $this;
    }
    /**
    * Gets the recipients
    *
    * @return string|null The recipients
    */
    public function getRecipients()
    {
        if (array_key_exists("recipients", $this->_propDict)) {
            return $this->_propDict["recipients"];
        } else {
            return null;
        }
    }

    /**
    * Sets the recipients
    *
    * @param string $val The value of the recipients
    *
    * @return NotifyUserAction
    */
    public function setRecipients($val)
    {
        $this->_propDict["recipients"] = $val;
        return $this;
    }
}
