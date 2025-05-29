<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* AuditLogRoot File
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
* AuditLogRoot class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class AuditLogRoot extends Entity
{

     /**
     * Gets the directoryAudits
     *
     * @return array|null The directoryAudits
     */
    public function getDirectoryAudits()
    {
        if (array_key_exists("directoryAudits", $this->_propDict)) {
           return $this->_propDict["directoryAudits"];
        } else {
            return null;
        }
    }

    /**
    * Sets the directoryAudits
    *
    * @param DirectoryAudit[] $val The directoryAudits
    *
    * @return AuditLogRoot
    */
    public function setDirectoryAudits($val)
    {
        $this->_propDict["directoryAudits"] = $val;
        return $this;
    }


     /**
     * Gets the provisioning
     *
     * @return array|null The provisioning
     */
    public function getProvisioning()
    {
        if (array_key_exists("provisioning", $this->_propDict)) {
           return $this->_propDict["provisioning"];
        } else {
            return null;
        }
    }

    /**
    * Sets the provisioning
    *
    * @param ProvisioningObjectSummary[] $val The provisioning
    *
    * @return AuditLogRoot
    */
    public function setProvisioning($val)
    {
        $this->_propDict["provisioning"] = $val;
        return $this;
    }


     /**
     * Gets the signIns
     *
     * @return array|null The signIns
     */
    public function getSignIns()
    {
        if (array_key_exists("signIns", $this->_propDict)) {
           return $this->_propDict["signIns"];
        } else {
            return null;
        }
    }

    /**
    * Sets the signIns
    *
    * @param SignIn[] $val The signIns
    *
    * @return AuditLogRoot
    */
    public function setSignIns($val)
    {
        $this->_propDict["signIns"] = $val;
        return $this;
    }

}
