<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* ContinuousAccessEvaluationMode File
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
* ContinuousAccessEvaluationMode class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class ContinuousAccessEvaluationMode extends Enum
{
    /**
    * The Enum ContinuousAccessEvaluationMode
    */
    const STRICT_ENFORCEMENT = "strictEnforcement";
    const DISABLED = "disabled";
    const UNKNOWN_FUTURE_VALUE = "unknownFutureValue";
    const STRICT_LOCATION = "strictLocation";
}
