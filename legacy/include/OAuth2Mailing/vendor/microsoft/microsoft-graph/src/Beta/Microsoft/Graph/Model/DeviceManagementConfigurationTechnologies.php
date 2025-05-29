<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* DeviceManagementConfigurationTechnologies File
* PHP version 7
*
* @category  Library
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
namespace Beta\Microsoft\Graph\Model;

use Microsoft\Graph\Core\Enum;

/**
* DeviceManagementConfigurationTechnologies class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class DeviceManagementConfigurationTechnologies extends Enum
{
    /**
    * The Enum DeviceManagementConfigurationTechnologies
    */
    const NONE = "none";
    const MDM = "mdm";
    const WINDOWS10_X_MANAGEMENT = "windows10XManagement";
    const CONFIG_MANAGER = "configManager";
    const APPLE_REMOTE_MANAGEMENT = "appleRemoteManagement";
    const MICROSOFT_SENSE = "microsoftSense";
    const EXCHANGE_ONLINE = "exchangeOnline";
    const MOBILE_APPLICATION_MANAGEMENT = "mobileApplicationManagement";
    const LINUX_MDM = "linuxMdm";
    const ENROLLMENT = "enrollment";
    const ENDPOINT_PRIVILEGE_MANAGEMENT = "endpointPrivilegeManagement";
    const UNKNOWN_FUTURE_VALUE = "unknownFutureValue";
}
