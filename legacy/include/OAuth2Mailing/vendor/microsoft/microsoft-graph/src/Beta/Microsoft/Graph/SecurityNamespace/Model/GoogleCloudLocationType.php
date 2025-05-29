<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* GoogleCloudLocationType File
* PHP version 7
*
* @category  Library
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
namespace Beta\Microsoft\Graph\SecurityNamespace\Model;

use Microsoft\Graph\Core\Enum;

/**
* GoogleCloudLocationType class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class GoogleCloudLocationType extends Enum
{
    /**
    * The Enum GoogleCloudLocationType
    */
    const UNKNOWN = "unknown";
    const REGIONAL = "regional";
    const ZONAL = "zonal";
    const GRAPHGLOBAL = "global";
    const UNKNOWN_FUTURE_VALUE = "unknownFutureValue";
}
