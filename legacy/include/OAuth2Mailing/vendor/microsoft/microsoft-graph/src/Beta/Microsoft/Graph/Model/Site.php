<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* Site File
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
* Site class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class Site extends BaseItem
{
    /**
    * Gets the deleted
    *
    * @return Deleted|null The deleted
    */
    public function getDeleted()
    {
        if (array_key_exists("deleted", $this->_propDict)) {
            if (is_a($this->_propDict["deleted"], "\Beta\Microsoft\Graph\Model\Deleted") || is_null($this->_propDict["deleted"])) {
                return $this->_propDict["deleted"];
            } else {
                $this->_propDict["deleted"] = new Deleted($this->_propDict["deleted"]);
                return $this->_propDict["deleted"];
            }
        }
        return null;
    }

    /**
    * Sets the deleted
    *
    * @param Deleted $val The deleted
    *
    * @return Site
    */
    public function setDeleted($val)
    {
        $this->_propDict["deleted"] = $val;
        return $this;
    }

    /**
    * Gets the displayName
    * The full title for the site. Read-only.
    *
    * @return string|null The displayName
    */
    public function getDisplayName()
    {
        if (array_key_exists("displayName", $this->_propDict)) {
            return $this->_propDict["displayName"];
        } else {
            return null;
        }
    }

    /**
    * Sets the displayName
    * The full title for the site. Read-only.
    *
    * @param string $val The displayName
    *
    * @return Site
    */
    public function setDisplayName($val)
    {
        $this->_propDict["displayName"] = $val;
        return $this;
    }

    /**
    * Gets the isPersonalSite
    *
    * @return bool|null The isPersonalSite
    */
    public function getIsPersonalSite()
    {
        if (array_key_exists("isPersonalSite", $this->_propDict)) {
            return $this->_propDict["isPersonalSite"];
        } else {
            return null;
        }
    }

    /**
    * Sets the isPersonalSite
    *
    * @param bool $val The isPersonalSite
    *
    * @return Site
    */
    public function setIsPersonalSite($val)
    {
        $this->_propDict["isPersonalSite"] = boolval($val);
        return $this;
    }

    /**
    * Gets the root
    * If present, indicates that this is the root site in the site collection. Read-only.
    *
    * @return Root|null The root
    */
    public function getRoot()
    {
        if (array_key_exists("root", $this->_propDict)) {
            if (is_a($this->_propDict["root"], "\Beta\Microsoft\Graph\Model\Root") || is_null($this->_propDict["root"])) {
                return $this->_propDict["root"];
            } else {
                $this->_propDict["root"] = new Root($this->_propDict["root"]);
                return $this->_propDict["root"];
            }
        }
        return null;
    }

    /**
    * Sets the root
    * If present, indicates that this is the root site in the site collection. Read-only.
    *
    * @param Root $val The root
    *
    * @return Site
    */
    public function setRoot($val)
    {
        $this->_propDict["root"] = $val;
        return $this;
    }

    /**
    * Gets the settings
    * The settings on this site. Read-only.
    *
    * @return SiteSettings|null The settings
    */
    public function getSettings()
    {
        if (array_key_exists("settings", $this->_propDict)) {
            if (is_a($this->_propDict["settings"], "\Beta\Microsoft\Graph\Model\SiteSettings") || is_null($this->_propDict["settings"])) {
                return $this->_propDict["settings"];
            } else {
                $this->_propDict["settings"] = new SiteSettings($this->_propDict["settings"]);
                return $this->_propDict["settings"];
            }
        }
        return null;
    }

    /**
    * Sets the settings
    * The settings on this site. Read-only.
    *
    * @param SiteSettings $val The settings
    *
    * @return Site
    */
    public function setSettings($val)
    {
        $this->_propDict["settings"] = $val;
        return $this;
    }

    /**
    * Gets the sharepointIds
    * Returns identifiers useful for SharePoint REST compatibility. Read-only.
    *
    * @return SharepointIds|null The sharepointIds
    */
    public function getSharepointIds()
    {
        if (array_key_exists("sharepointIds", $this->_propDict)) {
            if (is_a($this->_propDict["sharepointIds"], "\Beta\Microsoft\Graph\Model\SharepointIds") || is_null($this->_propDict["sharepointIds"])) {
                return $this->_propDict["sharepointIds"];
            } else {
                $this->_propDict["sharepointIds"] = new SharepointIds($this->_propDict["sharepointIds"]);
                return $this->_propDict["sharepointIds"];
            }
        }
        return null;
    }

    /**
    * Sets the sharepointIds
    * Returns identifiers useful for SharePoint REST compatibility. Read-only.
    *
    * @param SharepointIds $val The sharepointIds
    *
    * @return Site
    */
    public function setSharepointIds($val)
    {
        $this->_propDict["sharepointIds"] = $val;
        return $this;
    }

    /**
    * Gets the siteCollection
    * Provides details about the site's site collection. Available only on the root site. Read-only.
    *
    * @return SiteCollection|null The siteCollection
    */
    public function getSiteCollection()
    {
        if (array_key_exists("siteCollection", $this->_propDict)) {
            if (is_a($this->_propDict["siteCollection"], "\Beta\Microsoft\Graph\Model\SiteCollection") || is_null($this->_propDict["siteCollection"])) {
                return $this->_propDict["siteCollection"];
            } else {
                $this->_propDict["siteCollection"] = new SiteCollection($this->_propDict["siteCollection"]);
                return $this->_propDict["siteCollection"];
            }
        }
        return null;
    }

    /**
    * Sets the siteCollection
    * Provides details about the site's site collection. Available only on the root site. Read-only.
    *
    * @param SiteCollection $val The siteCollection
    *
    * @return Site
    */
    public function setSiteCollection($val)
    {
        $this->_propDict["siteCollection"] = $val;
        return $this;
    }

    /**
    * Gets the informationProtection
    *
    * @return InformationProtection|null The informationProtection
    */
    public function getInformationProtection()
    {
        if (array_key_exists("informationProtection", $this->_propDict)) {
            if (is_a($this->_propDict["informationProtection"], "\Beta\Microsoft\Graph\Model\InformationProtection") || is_null($this->_propDict["informationProtection"])) {
                return $this->_propDict["informationProtection"];
            } else {
                $this->_propDict["informationProtection"] = new InformationProtection($this->_propDict["informationProtection"]);
                return $this->_propDict["informationProtection"];
            }
        }
        return null;
    }

    /**
    * Sets the informationProtection
    *
    * @param InformationProtection $val The informationProtection
    *
    * @return Site
    */
    public function setInformationProtection($val)
    {
        $this->_propDict["informationProtection"] = $val;
        return $this;
    }

    /**
    * Gets the analytics
    * Analytics about the view activities that took place in this site.
    *
    * @return ItemAnalytics|null The analytics
    */
    public function getAnalytics()
    {
        if (array_key_exists("analytics", $this->_propDict)) {
            if (is_a($this->_propDict["analytics"], "\Beta\Microsoft\Graph\Model\ItemAnalytics") || is_null($this->_propDict["analytics"])) {
                return $this->_propDict["analytics"];
            } else {
                $this->_propDict["analytics"] = new ItemAnalytics($this->_propDict["analytics"]);
                return $this->_propDict["analytics"];
            }
        }
        return null;
    }

    /**
    * Sets the analytics
    * Analytics about the view activities that took place in this site.
    *
    * @param ItemAnalytics $val The analytics
    *
    * @return Site
    */
    public function setAnalytics($val)
    {
        $this->_propDict["analytics"] = $val;
        return $this;
    }


     /**
     * Gets the columns
    * The collection of column definitions reusable across lists under this site.
     *
     * @return array|null The columns
     */
    public function getColumns()
    {
        if (array_key_exists("columns", $this->_propDict)) {
           return $this->_propDict["columns"];
        } else {
            return null;
        }
    }

    /**
    * Sets the columns
    * The collection of column definitions reusable across lists under this site.
    *
    * @param ColumnDefinition[] $val The columns
    *
    * @return Site
    */
    public function setColumns($val)
    {
        $this->_propDict["columns"] = $val;
        return $this;
    }


     /**
     * Gets the contentTypes
    * The collection of content types defined for this site.
     *
     * @return array|null The contentTypes
     */
    public function getContentTypes()
    {
        if (array_key_exists("contentTypes", $this->_propDict)) {
           return $this->_propDict["contentTypes"];
        } else {
            return null;
        }
    }

    /**
    * Sets the contentTypes
    * The collection of content types defined for this site.
    *
    * @param ContentType[] $val The contentTypes
    *
    * @return Site
    */
    public function setContentTypes($val)
    {
        $this->_propDict["contentTypes"] = $val;
        return $this;
    }

    /**
    * Gets the drive
    * The default drive (document library) for this site.
    *
    * @return Drive|null The drive
    */
    public function getDrive()
    {
        if (array_key_exists("drive", $this->_propDict)) {
            if (is_a($this->_propDict["drive"], "\Beta\Microsoft\Graph\Model\Drive") || is_null($this->_propDict["drive"])) {
                return $this->_propDict["drive"];
            } else {
                $this->_propDict["drive"] = new Drive($this->_propDict["drive"]);
                return $this->_propDict["drive"];
            }
        }
        return null;
    }

    /**
    * Sets the drive
    * The default drive (document library) for this site.
    *
    * @param Drive $val The drive
    *
    * @return Site
    */
    public function setDrive($val)
    {
        $this->_propDict["drive"] = $val;
        return $this;
    }


     /**
     * Gets the drives
    * The collection of drives (document libraries) under this site.
     *
     * @return array|null The drives
     */
    public function getDrives()
    {
        if (array_key_exists("drives", $this->_propDict)) {
           return $this->_propDict["drives"];
        } else {
            return null;
        }
    }

    /**
    * Sets the drives
    * The collection of drives (document libraries) under this site.
    *
    * @param Drive[] $val The drives
    *
    * @return Site
    */
    public function setDrives($val)
    {
        $this->_propDict["drives"] = $val;
        return $this;
    }


     /**
     * Gets the externalColumns
    * The collection of column definitions available in the site that are referenced from the sites in the parent hierarchy of the current site.
     *
     * @return array|null The externalColumns
     */
    public function getExternalColumns()
    {
        if (array_key_exists("externalColumns", $this->_propDict)) {
           return $this->_propDict["externalColumns"];
        } else {
            return null;
        }
    }

    /**
    * Sets the externalColumns
    * The collection of column definitions available in the site that are referenced from the sites in the parent hierarchy of the current site.
    *
    * @param ColumnDefinition[] $val The externalColumns
    *
    * @return Site
    */
    public function setExternalColumns($val)
    {
        $this->_propDict["externalColumns"] = $val;
        return $this;
    }


     /**
     * Gets the items
    * Used to address any item contained in this site. This collection cannot be enumerated.
     *
     * @return array|null The items
     */
    public function getItems()
    {
        if (array_key_exists("items", $this->_propDict)) {
           return $this->_propDict["items"];
        } else {
            return null;
        }
    }

    /**
    * Sets the items
    * Used to address any item contained in this site. This collection cannot be enumerated.
    *
    * @param BaseItem[] $val The items
    *
    * @return Site
    */
    public function setItems($val)
    {
        $this->_propDict["items"] = $val;
        return $this;
    }


     /**
     * Gets the lists
    * The collection of lists under this site.
     *
     * @return array|null The lists
     */
    public function getLists()
    {
        if (array_key_exists("lists", $this->_propDict)) {
           return $this->_propDict["lists"];
        } else {
            return null;
        }
    }

    /**
    * Sets the lists
    * The collection of lists under this site.
    *
    * @param GraphList[] $val The lists
    *
    * @return Site
    */
    public function setLists($val)
    {
        $this->_propDict["lists"] = $val;
        return $this;
    }


     /**
     * Gets the operations
    * The collection of long running operations for the site.
     *
     * @return array|null The operations
     */
    public function getOperations()
    {
        if (array_key_exists("operations", $this->_propDict)) {
           return $this->_propDict["operations"];
        } else {
            return null;
        }
    }

    /**
    * Sets the operations
    * The collection of long running operations for the site.
    *
    * @param RichLongRunningOperation[] $val The operations
    *
    * @return Site
    */
    public function setOperations($val)
    {
        $this->_propDict["operations"] = $val;
        return $this;
    }


     /**
     * Gets the pages
    * The collection of pages in the baseSitePages list in this site.
     *
     * @return array|null The pages
     */
    public function getPages()
    {
        if (array_key_exists("pages", $this->_propDict)) {
           return $this->_propDict["pages"];
        } else {
            return null;
        }
    }

    /**
    * Sets the pages
    * The collection of pages in the baseSitePages list in this site.
    *
    * @param BaseSitePage[] $val The pages
    *
    * @return Site
    */
    public function setPages($val)
    {
        $this->_propDict["pages"] = $val;
        return $this;
    }


     /**
     * Gets the permissions
    * The permissions associated with the site. Nullable.
     *
     * @return array|null The permissions
     */
    public function getPermissions()
    {
        if (array_key_exists("permissions", $this->_propDict)) {
           return $this->_propDict["permissions"];
        } else {
            return null;
        }
    }

    /**
    * Sets the permissions
    * The permissions associated with the site. Nullable.
    *
    * @param Permission[] $val The permissions
    *
    * @return Site
    */
    public function setPermissions($val)
    {
        $this->_propDict["permissions"] = $val;
        return $this;
    }

    /**
    * Gets the recycleBin
    * A container for a collection of recycleBinItem resources in this site.
    *
    * @return RecycleBin|null The recycleBin
    */
    public function getRecycleBin()
    {
        if (array_key_exists("recycleBin", $this->_propDict)) {
            if (is_a($this->_propDict["recycleBin"], "\Beta\Microsoft\Graph\Model\RecycleBin") || is_null($this->_propDict["recycleBin"])) {
                return $this->_propDict["recycleBin"];
            } else {
                $this->_propDict["recycleBin"] = new RecycleBin($this->_propDict["recycleBin"]);
                return $this->_propDict["recycleBin"];
            }
        }
        return null;
    }

    /**
    * Sets the recycleBin
    * A container for a collection of recycleBinItem resources in this site.
    *
    * @param RecycleBin $val The recycleBin
    *
    * @return Site
    */
    public function setRecycleBin($val)
    {
        $this->_propDict["recycleBin"] = $val;
        return $this;
    }


     /**
     * Gets the sites
    * The collection of the sub-sites under this site.
     *
     * @return array|null The sites
     */
    public function getSites()
    {
        if (array_key_exists("sites", $this->_propDict)) {
           return $this->_propDict["sites"];
        } else {
            return null;
        }
    }

    /**
    * Sets the sites
    * The collection of the sub-sites under this site.
    *
    * @param Site[] $val The sites
    *
    * @return Site
    */
    public function setSites($val)
    {
        $this->_propDict["sites"] = $val;
        return $this;
    }

    /**
    * Gets the termStore
    * The termStore under this site.
    *
    * @return \Beta\Microsoft\Graph\TermStore\Model\Store|null The termStore
    */
    public function getTermStore()
    {
        if (array_key_exists("termStore", $this->_propDict)) {
            if (is_a($this->_propDict["termStore"], "\Beta\Microsoft\Graph\TermStore\Model\Store") || is_null($this->_propDict["termStore"])) {
                return $this->_propDict["termStore"];
            } else {
                $this->_propDict["termStore"] = new \Beta\Microsoft\Graph\TermStore\Model\Store($this->_propDict["termStore"]);
                return $this->_propDict["termStore"];
            }
        }
        return null;
    }

    /**
    * Sets the termStore
    * The termStore under this site.
    *
    * @param \Beta\Microsoft\Graph\TermStore\Model\Store $val The termStore
    *
    * @return Site
    */
    public function setTermStore($val)
    {
        $this->_propDict["termStore"] = $val;
        return $this;
    }

    /**
    * Gets the onenote
    *
    * @return Onenote|null The onenote
    */
    public function getOnenote()
    {
        if (array_key_exists("onenote", $this->_propDict)) {
            if (is_a($this->_propDict["onenote"], "\Beta\Microsoft\Graph\Model\Onenote") || is_null($this->_propDict["onenote"])) {
                return $this->_propDict["onenote"];
            } else {
                $this->_propDict["onenote"] = new Onenote($this->_propDict["onenote"]);
                return $this->_propDict["onenote"];
            }
        }
        return null;
    }

    /**
    * Sets the onenote
    *
    * @param Onenote $val The onenote
    *
    * @return Site
    */
    public function setOnenote($val)
    {
        $this->_propDict["onenote"] = $val;
        return $this;
    }

}
