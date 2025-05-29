<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* CloudPcOnPremisesConnectionType File
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
* CloudPcOnPremisesConnectionType class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class CloudPcOnPremisesConnectionType extends Enum
{
    /**
    * The Enum CloudPcOnPremisesConnectionType
    */
    const HYBRID_AZURE_AD_JOIN = "hybridAzureADJoin";
    const AZURE_AD_JOIN = "azureADJoin";
    const UNKNOWN_FUTURE_VALUE = "unknownFutureValue";
}
