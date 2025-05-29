<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* DefaultUserRolePermissions File
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
* DefaultUserRolePermissions class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class DefaultUserRolePermissions extends Entity
{
    /**
    * Gets the allowedToCreateApps
    * Indicates whether the default user role can create applications. This setting corresponds to the Users can register applications setting in the User settings menu in the Microsoft Entra admin center.
    *
    * @return bool|null The allowedToCreateApps
    */
    public function getAllowedToCreateApps()
    {
        if (array_key_exists("allowedToCreateApps", $this->_propDict)) {
            return $this->_propDict["allowedToCreateApps"];
        } else {
            return null;
        }
    }

    /**
    * Sets the allowedToCreateApps
    * Indicates whether the default user role can create applications. This setting corresponds to the Users can register applications setting in the User settings menu in the Microsoft Entra admin center.
    *
    * @param bool $val The value of the allowedToCreateApps
    *
    * @return DefaultUserRolePermissions
    */
    public function setAllowedToCreateApps($val)
    {
        $this->_propDict["allowedToCreateApps"] = $val;
        return $this;
    }
    /**
    * Gets the allowedToCreateSecurityGroups
    * Indicates whether the default user role can create security groups. This setting corresponds to the following menus in the Microsoft Entra admin center:  The Users can create security groups in Microsoft Entra admin centers, API or PowerShell setting in the Group settings menu.  Users can create security groups setting in the User settings menu.
    *
    * @return bool|null The allowedToCreateSecurityGroups
    */
    public function getAllowedToCreateSecurityGroups()
    {
        if (array_key_exists("allowedToCreateSecurityGroups", $this->_propDict)) {
            return $this->_propDict["allowedToCreateSecurityGroups"];
        } else {
            return null;
        }
    }

    /**
    * Sets the allowedToCreateSecurityGroups
    * Indicates whether the default user role can create security groups. This setting corresponds to the following menus in the Microsoft Entra admin center:  The Users can create security groups in Microsoft Entra admin centers, API or PowerShell setting in the Group settings menu.  Users can create security groups setting in the User settings menu.
    *
    * @param bool $val The value of the allowedToCreateSecurityGroups
    *
    * @return DefaultUserRolePermissions
    */
    public function setAllowedToCreateSecurityGroups($val)
    {
        $this->_propDict["allowedToCreateSecurityGroups"] = $val;
        return $this;
    }
    /**
    * Gets the allowedToCreateTenants
    * Indicates whether the default user role can create tenants. This setting corresponds to the Restrict non-admin users from creating tenants setting in the User settings menu in the Microsoft Entra admin center.  When this setting is false, users assigned the Tenant Creator role can still create tenants.
    *
    * @return bool|null The allowedToCreateTenants
    */
    public function getAllowedToCreateTenants()
    {
        if (array_key_exists("allowedToCreateTenants", $this->_propDict)) {
            return $this->_propDict["allowedToCreateTenants"];
        } else {
            return null;
        }
    }

    /**
    * Sets the allowedToCreateTenants
    * Indicates whether the default user role can create tenants. This setting corresponds to the Restrict non-admin users from creating tenants setting in the User settings menu in the Microsoft Entra admin center.  When this setting is false, users assigned the Tenant Creator role can still create tenants.
    *
    * @param bool $val The value of the allowedToCreateTenants
    *
    * @return DefaultUserRolePermissions
    */
    public function setAllowedToCreateTenants($val)
    {
        $this->_propDict["allowedToCreateTenants"] = $val;
        return $this;
    }
    /**
    * Gets the allowedToReadBitlockerKeysForOwnedDevice
    * Indicates whether the registered owners of a device can read their own BitLocker recovery keys with default user role.
    *
    * @return bool|null The allowedToReadBitlockerKeysForOwnedDevice
    */
    public function getAllowedToReadBitlockerKeysForOwnedDevice()
    {
        if (array_key_exists("allowedToReadBitlockerKeysForOwnedDevice", $this->_propDict)) {
            return $this->_propDict["allowedToReadBitlockerKeysForOwnedDevice"];
        } else {
            return null;
        }
    }

    /**
    * Sets the allowedToReadBitlockerKeysForOwnedDevice
    * Indicates whether the registered owners of a device can read their own BitLocker recovery keys with default user role.
    *
    * @param bool $val The value of the allowedToReadBitlockerKeysForOwnedDevice
    *
    * @return DefaultUserRolePermissions
    */
    public function setAllowedToReadBitlockerKeysForOwnedDevice($val)
    {
        $this->_propDict["allowedToReadBitlockerKeysForOwnedDevice"] = $val;
        return $this;
    }
    /**
    * Gets the allowedToReadOtherUsers
    * Indicates whether the default user role can read other users. DO NOT SET THIS VALUE TO false.
    *
    * @return bool|null The allowedToReadOtherUsers
    */
    public function getAllowedToReadOtherUsers()
    {
        if (array_key_exists("allowedToReadOtherUsers", $this->_propDict)) {
            return $this->_propDict["allowedToReadOtherUsers"];
        } else {
            return null;
        }
    }

    /**
    * Sets the allowedToReadOtherUsers
    * Indicates whether the default user role can read other users. DO NOT SET THIS VALUE TO false.
    *
    * @param bool $val The value of the allowedToReadOtherUsers
    *
    * @return DefaultUserRolePermissions
    */
    public function setAllowedToReadOtherUsers($val)
    {
        $this->_propDict["allowedToReadOtherUsers"] = $val;
        return $this;
    }
    /**
    * Gets the permissionGrantPoliciesAssigned
    * Indicates if user consent to apps is allowed, and if it is, which permission to grant consent and which app consent policy (permissionGrantPolicy) govern the permission for users to grant consent. Value should be in the format managePermissionGrantsForSelf.{id}, where {id} is the id of a built-in or custom app consent policy. An empty list indicates user consent to apps is disabled.
    *
    * @return string|null The permissionGrantPoliciesAssigned
    */
    public function getPermissionGrantPoliciesAssigned()
    {
        if (array_key_exists("permissionGrantPoliciesAssigned", $this->_propDict)) {
            return $this->_propDict["permissionGrantPoliciesAssigned"];
        } else {
            return null;
        }
    }

    /**
    * Sets the permissionGrantPoliciesAssigned
    * Indicates if user consent to apps is allowed, and if it is, which permission to grant consent and which app consent policy (permissionGrantPolicy) govern the permission for users to grant consent. Value should be in the format managePermissionGrantsForSelf.{id}, where {id} is the id of a built-in or custom app consent policy. An empty list indicates user consent to apps is disabled.
    *
    * @param string $val The value of the permissionGrantPoliciesAssigned
    *
    * @return DefaultUserRolePermissions
    */
    public function setPermissionGrantPoliciesAssigned($val)
    {
        $this->_propDict["permissionGrantPoliciesAssigned"] = $val;
        return $this;
    }
}
