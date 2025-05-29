<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* CloudPcDeviceImageStatusDetails File
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
* CloudPcDeviceImageStatusDetails class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class CloudPcDeviceImageStatusDetails extends Enum
{
    /**
    * The Enum CloudPcDeviceImageStatusDetails
    */
    const INTERNAL_SERVER_ERROR = "internalServerError";
    const SOURCE_IMAGE_NOT_FOUND = "sourceImageNotFound";
    const OS_VERSION_NOT_SUPPORTED = "osVersionNotSupported";
    const SOURCE_IMAGE_INVALID = "sourceImageInvalid";
    const SOURCE_IMAGE_NOT_GENERALIZED = "sourceImageNotGeneralized";
    const UNKNOWN_FUTURE_VALUE = "unknownFutureValue";
    const VM_ALREADY_AZURE_ADJOINED = "vmAlreadyAzureAdjoined";
    const PAID_SOURCE_IMAGE_NOT_SUPPORT = "paidSourceImageNotSupport";
    const SOURCE_IMAGE_NOT_SUPPORT_CUSTOMIZE_VM_NAME = "sourceImageNotSupportCustomizeVMName";
    const SOURCE_IMAGE_SIZE_EXCEEDS_LIMITATION = "sourceImageSizeExceedsLimitation";
}
