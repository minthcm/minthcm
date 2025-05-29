<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* DeviceManagementConfigurationTemplateFamily File
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
* DeviceManagementConfigurationTemplateFamily class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class DeviceManagementConfigurationTemplateFamily extends Enum
{
    /**
    * The Enum DeviceManagementConfigurationTemplateFamily
    */
    const NONE = "none";
    const ENDPOINT_SECURITY_ANTIVIRUS = "endpointSecurityAntivirus";
    const ENDPOINT_SECURITY_DISK_ENCRYPTION = "endpointSecurityDiskEncryption";
    const ENDPOINT_SECURITY_FIREWALL = "endpointSecurityFirewall";
    const ENDPOINT_SECURITY_ENDPOINT_DETECTION_AND_RESPONSE = "endpointSecurityEndpointDetectionAndResponse";
    const ENDPOINT_SECURITY_ATTACK_SURFACE_REDUCTION = "endpointSecurityAttackSurfaceReduction";
    const ENDPOINT_SECURITY_ACCOUNT_PROTECTION = "endpointSecurityAccountProtection";
    const ENDPOINT_SECURITY_APPLICATION_CONTROL = "endpointSecurityApplicationControl";
    const ENDPOINT_SECURITY_ENDPOINT_PRIVILEGE_MANAGEMENT = "endpointSecurityEndpointPrivilegeManagement";
    const ENROLLMENT_CONFIGURATION = "enrollmentConfiguration";
    const APP_QUIET_TIME = "appQuietTime";
    const BASELINE = "baseline";
    const UNKNOWN_FUTURE_VALUE = "unknownFutureValue";
    const DEVICE_CONFIGURATION_SCRIPTS = "deviceConfigurationScripts";
    const DEVICE_CONFIGURATION_POLICIES = "deviceConfigurationPolicies";
    const COMPANY_PORTAL = "companyPortal";
}
