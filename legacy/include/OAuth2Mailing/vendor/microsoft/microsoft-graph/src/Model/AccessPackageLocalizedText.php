<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* AccessPackageLocalizedText File
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
* AccessPackageLocalizedText class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class AccessPackageLocalizedText extends Entity
{
    /**
    * Gets the languageCode
    * The language code that text is in. For example, 'en-us'. The language component follows 2-letter codes as defined in ISO 639-1, and the country component follows 2-letter codes as defined in ISO 3166-1 alpha-2. Required.
    *
    * @return string|null The languageCode
    */
    public function getLanguageCode()
    {
        if (array_key_exists("languageCode", $this->_propDict)) {
            return $this->_propDict["languageCode"];
        } else {
            return null;
        }
    }

    /**
    * Sets the languageCode
    * The language code that text is in. For example, 'en-us'. The language component follows 2-letter codes as defined in ISO 639-1, and the country component follows 2-letter codes as defined in ISO 3166-1 alpha-2. Required.
    *
    * @param string $val The value of the languageCode
    *
    * @return AccessPackageLocalizedText
    */
    public function setLanguageCode($val)
    {
        $this->_propDict["languageCode"] = $val;
        return $this;
    }
    /**
    * Gets the text
    * The question in the specific language. Required.
    *
    * @return string|null The text
    */
    public function getText()
    {
        if (array_key_exists("text", $this->_propDict)) {
            return $this->_propDict["text"];
        } else {
            return null;
        }
    }

    /**
    * Sets the text
    * The question in the specific language. Required.
    *
    * @param string $val The value of the text
    *
    * @return AccessPackageLocalizedText
    */
    public function setText($val)
    {
        $this->_propDict["text"] = $val;
        return $this;
    }
}
