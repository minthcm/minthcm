<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* SitePage File
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
* SitePage class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class SitePage extends BaseSitePage
{
    /**
    * Gets the promotionKind
    * Indicates the promotion kind of the sitePage. The possible values are: microsoftReserved, page, newsPost, unknownFutureValue.
    *
    * @return PagePromotionType|null The promotionKind
    */
    public function getPromotionKind()
    {
        if (array_key_exists("promotionKind", $this->_propDict)) {
            if (is_a($this->_propDict["promotionKind"], "\Beta\Microsoft\Graph\Model\PagePromotionType") || is_null($this->_propDict["promotionKind"])) {
                return $this->_propDict["promotionKind"];
            } else {
                $this->_propDict["promotionKind"] = new PagePromotionType($this->_propDict["promotionKind"]);
                return $this->_propDict["promotionKind"];
            }
        }
        return null;
    }

    /**
    * Sets the promotionKind
    * Indicates the promotion kind of the sitePage. The possible values are: microsoftReserved, page, newsPost, unknownFutureValue.
    *
    * @param PagePromotionType $val The promotionKind
    *
    * @return SitePage
    */
    public function setPromotionKind($val)
    {
        $this->_propDict["promotionKind"] = $val;
        return $this;
    }

    /**
    * Gets the reactions
    * Reactions information for the page.
    *
    * @return ReactionsFacet|null The reactions
    */
    public function getReactions()
    {
        if (array_key_exists("reactions", $this->_propDict)) {
            if (is_a($this->_propDict["reactions"], "\Beta\Microsoft\Graph\Model\ReactionsFacet") || is_null($this->_propDict["reactions"])) {
                return $this->_propDict["reactions"];
            } else {
                $this->_propDict["reactions"] = new ReactionsFacet($this->_propDict["reactions"]);
                return $this->_propDict["reactions"];
            }
        }
        return null;
    }

    /**
    * Sets the reactions
    * Reactions information for the page.
    *
    * @param ReactionsFacet $val The reactions
    *
    * @return SitePage
    */
    public function setReactions($val)
    {
        $this->_propDict["reactions"] = $val;
        return $this;
    }

    /**
    * Gets the showComments
    * Determines whether or not to show comments at the bottom of the page.
    *
    * @return bool|null The showComments
    */
    public function getShowComments()
    {
        if (array_key_exists("showComments", $this->_propDict)) {
            return $this->_propDict["showComments"];
        } else {
            return null;
        }
    }

    /**
    * Sets the showComments
    * Determines whether or not to show comments at the bottom of the page.
    *
    * @param bool $val The showComments
    *
    * @return SitePage
    */
    public function setShowComments($val)
    {
        $this->_propDict["showComments"] = boolval($val);
        return $this;
    }

    /**
    * Gets the showRecommendedPages
    * Determines whether or not to show recommended pages at the bottom of the page.
    *
    * @return bool|null The showRecommendedPages
    */
    public function getShowRecommendedPages()
    {
        if (array_key_exists("showRecommendedPages", $this->_propDict)) {
            return $this->_propDict["showRecommendedPages"];
        } else {
            return null;
        }
    }

    /**
    * Sets the showRecommendedPages
    * Determines whether or not to show recommended pages at the bottom of the page.
    *
    * @param bool $val The showRecommendedPages
    *
    * @return SitePage
    */
    public function setShowRecommendedPages($val)
    {
        $this->_propDict["showRecommendedPages"] = boolval($val);
        return $this;
    }

    /**
    * Gets the thumbnailWebUrl
    * Url of the sitePage's thumbnail image
    *
    * @return string|null The thumbnailWebUrl
    */
    public function getThumbnailWebUrl()
    {
        if (array_key_exists("thumbnailWebUrl", $this->_propDict)) {
            return $this->_propDict["thumbnailWebUrl"];
        } else {
            return null;
        }
    }

    /**
    * Sets the thumbnailWebUrl
    * Url of the sitePage's thumbnail image
    *
    * @param string $val The thumbnailWebUrl
    *
    * @return SitePage
    */
    public function setThumbnailWebUrl($val)
    {
        $this->_propDict["thumbnailWebUrl"] = $val;
        return $this;
    }

    /**
    * Gets the titleArea
    * Title area on the SharePoint page.
    *
    * @return TitleArea|null The titleArea
    */
    public function getTitleArea()
    {
        if (array_key_exists("titleArea", $this->_propDict)) {
            if (is_a($this->_propDict["titleArea"], "\Beta\Microsoft\Graph\Model\TitleArea") || is_null($this->_propDict["titleArea"])) {
                return $this->_propDict["titleArea"];
            } else {
                $this->_propDict["titleArea"] = new TitleArea($this->_propDict["titleArea"]);
                return $this->_propDict["titleArea"];
            }
        }
        return null;
    }

    /**
    * Sets the titleArea
    * Title area on the SharePoint page.
    *
    * @param TitleArea $val The titleArea
    *
    * @return SitePage
    */
    public function setTitleArea($val)
    {
        $this->_propDict["titleArea"] = $val;
        return $this;
    }

    /**
    * Gets the canvasLayout
    * Indicates the layout of the content in a given SharePoint page, including horizontal sections and vertical sections.
    *
    * @return CanvasLayout|null The canvasLayout
    */
    public function getCanvasLayout()
    {
        if (array_key_exists("canvasLayout", $this->_propDict)) {
            if (is_a($this->_propDict["canvasLayout"], "\Beta\Microsoft\Graph\Model\CanvasLayout") || is_null($this->_propDict["canvasLayout"])) {
                return $this->_propDict["canvasLayout"];
            } else {
                $this->_propDict["canvasLayout"] = new CanvasLayout($this->_propDict["canvasLayout"]);
                return $this->_propDict["canvasLayout"];
            }
        }
        return null;
    }

    /**
    * Sets the canvasLayout
    * Indicates the layout of the content in a given SharePoint page, including horizontal sections and vertical sections.
    *
    * @param CanvasLayout $val The canvasLayout
    *
    * @return SitePage
    */
    public function setCanvasLayout($val)
    {
        $this->_propDict["canvasLayout"] = $val;
        return $this;
    }


     /**
     * Gets the webParts
    * Collection of webparts on the SharePoint page.
     *
     * @return array|null The webParts
     */
    public function getWebParts()
    {
        if (array_key_exists("webParts", $this->_propDict)) {
           return $this->_propDict["webParts"];
        } else {
            return null;
        }
    }

    /**
    * Sets the webParts
    * Collection of webparts on the SharePoint page.
    *
    * @param WebPart[] $val The webParts
    *
    * @return SitePage
    */
    public function setWebParts($val)
    {
        $this->_propDict["webParts"] = $val;
        return $this;
    }

}
