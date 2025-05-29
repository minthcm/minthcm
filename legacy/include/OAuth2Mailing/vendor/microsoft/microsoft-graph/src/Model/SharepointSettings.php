<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* SharepointSettings File
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
* SharepointSettings class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class SharepointSettings extends Entity
{
    /**
    * Gets the allowedDomainGuidsForSyncApp
    * Collection of trusted domain GUIDs for the OneDrive sync app.
    *
    * @return array|null The allowedDomainGuidsForSyncApp
    */
    public function getAllowedDomainGuidsForSyncApp()
    {
        if (array_key_exists("allowedDomainGuidsForSyncApp", $this->_propDict)) {
            return $this->_propDict["allowedDomainGuidsForSyncApp"];
        } else {
            return null;
        }
    }

    /**
    * Sets the allowedDomainGuidsForSyncApp
    * Collection of trusted domain GUIDs for the OneDrive sync app.
    *
    * @param string[] $val The allowedDomainGuidsForSyncApp
    *
    * @return SharepointSettings
    */
    public function setAllowedDomainGuidsForSyncApp($val)
    {
        $this->_propDict["allowedDomainGuidsForSyncApp"] = $val;
        return $this;
    }

    /**
    * Gets the availableManagedPathsForSiteCreation
    * Collection of managed paths available for site creation. Read-only.
    *
    * @return array|null The availableManagedPathsForSiteCreation
    */
    public function getAvailableManagedPathsForSiteCreation()
    {
        if (array_key_exists("availableManagedPathsForSiteCreation", $this->_propDict)) {
            return $this->_propDict["availableManagedPathsForSiteCreation"];
        } else {
            return null;
        }
    }

    /**
    * Sets the availableManagedPathsForSiteCreation
    * Collection of managed paths available for site creation. Read-only.
    *
    * @param string[] $val The availableManagedPathsForSiteCreation
    *
    * @return SharepointSettings
    */
    public function setAvailableManagedPathsForSiteCreation($val)
    {
        $this->_propDict["availableManagedPathsForSiteCreation"] = $val;
        return $this;
    }

    /**
    * Gets the deletedUserPersonalSiteRetentionPeriodInDays
    * The number of days for preserving a deleted user's OneDrive.
    *
    * @return int|null The deletedUserPersonalSiteRetentionPeriodInDays
    */
    public function getDeletedUserPersonalSiteRetentionPeriodInDays()
    {
        if (array_key_exists("deletedUserPersonalSiteRetentionPeriodInDays", $this->_propDict)) {
            return $this->_propDict["deletedUserPersonalSiteRetentionPeriodInDays"];
        } else {
            return null;
        }
    }

    /**
    * Sets the deletedUserPersonalSiteRetentionPeriodInDays
    * The number of days for preserving a deleted user's OneDrive.
    *
    * @param int $val The deletedUserPersonalSiteRetentionPeriodInDays
    *
    * @return SharepointSettings
    */
    public function setDeletedUserPersonalSiteRetentionPeriodInDays($val)
    {
        $this->_propDict["deletedUserPersonalSiteRetentionPeriodInDays"] = intval($val);
        return $this;
    }

    /**
    * Gets the excludedFileExtensionsForSyncApp
    * Collection of file extensions not uploaded by the OneDrive sync app.
    *
    * @return array|null The excludedFileExtensionsForSyncApp
    */
    public function getExcludedFileExtensionsForSyncApp()
    {
        if (array_key_exists("excludedFileExtensionsForSyncApp", $this->_propDict)) {
            return $this->_propDict["excludedFileExtensionsForSyncApp"];
        } else {
            return null;
        }
    }

    /**
    * Sets the excludedFileExtensionsForSyncApp
    * Collection of file extensions not uploaded by the OneDrive sync app.
    *
    * @param string[] $val The excludedFileExtensionsForSyncApp
    *
    * @return SharepointSettings
    */
    public function setExcludedFileExtensionsForSyncApp($val)
    {
        $this->_propDict["excludedFileExtensionsForSyncApp"] = $val;
        return $this;
    }

    /**
    * Gets the idleSessionSignOut
    * Specifies the idle session sign-out policies for the tenant.
    *
    * @return IdleSessionSignOut|null The idleSessionSignOut
    */
    public function getIdleSessionSignOut()
    {
        if (array_key_exists("idleSessionSignOut", $this->_propDict)) {
            if (is_a($this->_propDict["idleSessionSignOut"], "\Microsoft\Graph\Model\IdleSessionSignOut") || is_null($this->_propDict["idleSessionSignOut"])) {
                return $this->_propDict["idleSessionSignOut"];
            } else {
                $this->_propDict["idleSessionSignOut"] = new IdleSessionSignOut($this->_propDict["idleSessionSignOut"]);
                return $this->_propDict["idleSessionSignOut"];
            }
        }
        return null;
    }

    /**
    * Sets the idleSessionSignOut
    * Specifies the idle session sign-out policies for the tenant.
    *
    * @param IdleSessionSignOut $val The idleSessionSignOut
    *
    * @return SharepointSettings
    */
    public function setIdleSessionSignOut($val)
    {
        $this->_propDict["idleSessionSignOut"] = $val;
        return $this;
    }

    /**
    * Gets the imageTaggingOption
    * Specifies the image tagging option for the tenant. Possible values are: disabled, basic, enhanced.
    *
    * @return ImageTaggingChoice|null The imageTaggingOption
    */
    public function getImageTaggingOption()
    {
        if (array_key_exists("imageTaggingOption", $this->_propDict)) {
            if (is_a($this->_propDict["imageTaggingOption"], "\Microsoft\Graph\Model\ImageTaggingChoice") || is_null($this->_propDict["imageTaggingOption"])) {
                return $this->_propDict["imageTaggingOption"];
            } else {
                $this->_propDict["imageTaggingOption"] = new ImageTaggingChoice($this->_propDict["imageTaggingOption"]);
                return $this->_propDict["imageTaggingOption"];
            }
        }
        return null;
    }

    /**
    * Sets the imageTaggingOption
    * Specifies the image tagging option for the tenant. Possible values are: disabled, basic, enhanced.
    *
    * @param ImageTaggingChoice $val The imageTaggingOption
    *
    * @return SharepointSettings
    */
    public function setImageTaggingOption($val)
    {
        $this->_propDict["imageTaggingOption"] = $val;
        return $this;
    }

    /**
    * Gets the isCommentingOnSitePagesEnabled
    * Indicates whether comments are allowed on modern site pages in SharePoint.
    *
    * @return bool|null The isCommentingOnSitePagesEnabled
    */
    public function getIsCommentingOnSitePagesEnabled()
    {
        if (array_key_exists("isCommentingOnSitePagesEnabled", $this->_propDict)) {
            return $this->_propDict["isCommentingOnSitePagesEnabled"];
        } else {
            return null;
        }
    }

    /**
    * Sets the isCommentingOnSitePagesEnabled
    * Indicates whether comments are allowed on modern site pages in SharePoint.
    *
    * @param bool $val The isCommentingOnSitePagesEnabled
    *
    * @return SharepointSettings
    */
    public function setIsCommentingOnSitePagesEnabled($val)
    {
        $this->_propDict["isCommentingOnSitePagesEnabled"] = boolval($val);
        return $this;
    }

    /**
    * Gets the isFileActivityNotificationEnabled
    * Indicates whether push notifications are enabled for OneDrive events.
    *
    * @return bool|null The isFileActivityNotificationEnabled
    */
    public function getIsFileActivityNotificationEnabled()
    {
        if (array_key_exists("isFileActivityNotificationEnabled", $this->_propDict)) {
            return $this->_propDict["isFileActivityNotificationEnabled"];
        } else {
            return null;
        }
    }

    /**
    * Sets the isFileActivityNotificationEnabled
    * Indicates whether push notifications are enabled for OneDrive events.
    *
    * @param bool $val The isFileActivityNotificationEnabled
    *
    * @return SharepointSettings
    */
    public function setIsFileActivityNotificationEnabled($val)
    {
        $this->_propDict["isFileActivityNotificationEnabled"] = boolval($val);
        return $this;
    }

    /**
    * Gets the isLegacyAuthProtocolsEnabled
    * Indicates whether legacy authentication protocols are enabled for the tenant.
    *
    * @return bool|null The isLegacyAuthProtocolsEnabled
    */
    public function getIsLegacyAuthProtocolsEnabled()
    {
        if (array_key_exists("isLegacyAuthProtocolsEnabled", $this->_propDict)) {
            return $this->_propDict["isLegacyAuthProtocolsEnabled"];
        } else {
            return null;
        }
    }

    /**
    * Sets the isLegacyAuthProtocolsEnabled
    * Indicates whether legacy authentication protocols are enabled for the tenant.
    *
    * @param bool $val The isLegacyAuthProtocolsEnabled
    *
    * @return SharepointSettings
    */
    public function setIsLegacyAuthProtocolsEnabled($val)
    {
        $this->_propDict["isLegacyAuthProtocolsEnabled"] = boolval($val);
        return $this;
    }

    /**
    * Gets the isLoopEnabled
    * Indicates whether if Fluid Framework is allowed on SharePoint sites.
    *
    * @return bool|null The isLoopEnabled
    */
    public function getIsLoopEnabled()
    {
        if (array_key_exists("isLoopEnabled", $this->_propDict)) {
            return $this->_propDict["isLoopEnabled"];
        } else {
            return null;
        }
    }

    /**
    * Sets the isLoopEnabled
    * Indicates whether if Fluid Framework is allowed on SharePoint sites.
    *
    * @param bool $val The isLoopEnabled
    *
    * @return SharepointSettings
    */
    public function setIsLoopEnabled($val)
    {
        $this->_propDict["isLoopEnabled"] = boolval($val);
        return $this;
    }

    /**
    * Gets the isMacSyncAppEnabled
    * Indicates whether files can be synced using the OneDrive sync app for Mac.
    *
    * @return bool|null The isMacSyncAppEnabled
    */
    public function getIsMacSyncAppEnabled()
    {
        if (array_key_exists("isMacSyncAppEnabled", $this->_propDict)) {
            return $this->_propDict["isMacSyncAppEnabled"];
        } else {
            return null;
        }
    }

    /**
    * Sets the isMacSyncAppEnabled
    * Indicates whether files can be synced using the OneDrive sync app for Mac.
    *
    * @param bool $val The isMacSyncAppEnabled
    *
    * @return SharepointSettings
    */
    public function setIsMacSyncAppEnabled($val)
    {
        $this->_propDict["isMacSyncAppEnabled"] = boolval($val);
        return $this;
    }

    /**
    * Gets the isRequireAcceptingUserToMatchInvitedUserEnabled
    * Indicates whether guests must sign in using the same account to which sharing invitations are sent.
    *
    * @return bool|null The isRequireAcceptingUserToMatchInvitedUserEnabled
    */
    public function getIsRequireAcceptingUserToMatchInvitedUserEnabled()
    {
        if (array_key_exists("isRequireAcceptingUserToMatchInvitedUserEnabled", $this->_propDict)) {
            return $this->_propDict["isRequireAcceptingUserToMatchInvitedUserEnabled"];
        } else {
            return null;
        }
    }

    /**
    * Sets the isRequireAcceptingUserToMatchInvitedUserEnabled
    * Indicates whether guests must sign in using the same account to which sharing invitations are sent.
    *
    * @param bool $val The isRequireAcceptingUserToMatchInvitedUserEnabled
    *
    * @return SharepointSettings
    */
    public function setIsRequireAcceptingUserToMatchInvitedUserEnabled($val)
    {
        $this->_propDict["isRequireAcceptingUserToMatchInvitedUserEnabled"] = boolval($val);
        return $this;
    }

    /**
    * Gets the isResharingByExternalUsersEnabled
    * Indicates whether guests are allowed to reshare files, folders, and sites they don't own.
    *
    * @return bool|null The isResharingByExternalUsersEnabled
    */
    public function getIsResharingByExternalUsersEnabled()
    {
        if (array_key_exists("isResharingByExternalUsersEnabled", $this->_propDict)) {
            return $this->_propDict["isResharingByExternalUsersEnabled"];
        } else {
            return null;
        }
    }

    /**
    * Sets the isResharingByExternalUsersEnabled
    * Indicates whether guests are allowed to reshare files, folders, and sites they don't own.
    *
    * @param bool $val The isResharingByExternalUsersEnabled
    *
    * @return SharepointSettings
    */
    public function setIsResharingByExternalUsersEnabled($val)
    {
        $this->_propDict["isResharingByExternalUsersEnabled"] = boolval($val);
        return $this;
    }

    /**
    * Gets the isSharePointMobileNotificationEnabled
    * Indicates whether mobile push notifications are enabled for SharePoint.
    *
    * @return bool|null The isSharePointMobileNotificationEnabled
    */
    public function getIsSharePointMobileNotificationEnabled()
    {
        if (array_key_exists("isSharePointMobileNotificationEnabled", $this->_propDict)) {
            return $this->_propDict["isSharePointMobileNotificationEnabled"];
        } else {
            return null;
        }
    }

    /**
    * Sets the isSharePointMobileNotificationEnabled
    * Indicates whether mobile push notifications are enabled for SharePoint.
    *
    * @param bool $val The isSharePointMobileNotificationEnabled
    *
    * @return SharepointSettings
    */
    public function setIsSharePointMobileNotificationEnabled($val)
    {
        $this->_propDict["isSharePointMobileNotificationEnabled"] = boolval($val);
        return $this;
    }

    /**
    * Gets the isSharePointNewsfeedEnabled
    * Indicates whether the newsfeed is allowed on the modern site pages in SharePoint.
    *
    * @return bool|null The isSharePointNewsfeedEnabled
    */
    public function getIsSharePointNewsfeedEnabled()
    {
        if (array_key_exists("isSharePointNewsfeedEnabled", $this->_propDict)) {
            return $this->_propDict["isSharePointNewsfeedEnabled"];
        } else {
            return null;
        }
    }

    /**
    * Sets the isSharePointNewsfeedEnabled
    * Indicates whether the newsfeed is allowed on the modern site pages in SharePoint.
    *
    * @param bool $val The isSharePointNewsfeedEnabled
    *
    * @return SharepointSettings
    */
    public function setIsSharePointNewsfeedEnabled($val)
    {
        $this->_propDict["isSharePointNewsfeedEnabled"] = boolval($val);
        return $this;
    }

    /**
    * Gets the isSiteCreationEnabled
    * Indicates whether users are allowed to create sites.
    *
    * @return bool|null The isSiteCreationEnabled
    */
    public function getIsSiteCreationEnabled()
    {
        if (array_key_exists("isSiteCreationEnabled", $this->_propDict)) {
            return $this->_propDict["isSiteCreationEnabled"];
        } else {
            return null;
        }
    }

    /**
    * Sets the isSiteCreationEnabled
    * Indicates whether users are allowed to create sites.
    *
    * @param bool $val The isSiteCreationEnabled
    *
    * @return SharepointSettings
    */
    public function setIsSiteCreationEnabled($val)
    {
        $this->_propDict["isSiteCreationEnabled"] = boolval($val);
        return $this;
    }

    /**
    * Gets the isSiteCreationUIEnabled
    * Indicates whether the UI commands for creating sites are shown.
    *
    * @return bool|null The isSiteCreationUIEnabled
    */
    public function getIsSiteCreationUIEnabled()
    {
        if (array_key_exists("isSiteCreationUIEnabled", $this->_propDict)) {
            return $this->_propDict["isSiteCreationUIEnabled"];
        } else {
            return null;
        }
    }

    /**
    * Sets the isSiteCreationUIEnabled
    * Indicates whether the UI commands for creating sites are shown.
    *
    * @param bool $val The isSiteCreationUIEnabled
    *
    * @return SharepointSettings
    */
    public function setIsSiteCreationUIEnabled($val)
    {
        $this->_propDict["isSiteCreationUIEnabled"] = boolval($val);
        return $this;
    }

    /**
    * Gets the isSitePagesCreationEnabled
    * Indicates whether creating new modern pages is allowed on SharePoint sites.
    *
    * @return bool|null The isSitePagesCreationEnabled
    */
    public function getIsSitePagesCreationEnabled()
    {
        if (array_key_exists("isSitePagesCreationEnabled", $this->_propDict)) {
            return $this->_propDict["isSitePagesCreationEnabled"];
        } else {
            return null;
        }
    }

    /**
    * Sets the isSitePagesCreationEnabled
    * Indicates whether creating new modern pages is allowed on SharePoint sites.
    *
    * @param bool $val The isSitePagesCreationEnabled
    *
    * @return SharepointSettings
    */
    public function setIsSitePagesCreationEnabled($val)
    {
        $this->_propDict["isSitePagesCreationEnabled"] = boolval($val);
        return $this;
    }

    /**
    * Gets the isSitesStorageLimitAutomatic
    * Indicates whether site storage space is automatically managed or if specific storage limits are set per site.
    *
    * @return bool|null The isSitesStorageLimitAutomatic
    */
    public function getIsSitesStorageLimitAutomatic()
    {
        if (array_key_exists("isSitesStorageLimitAutomatic", $this->_propDict)) {
            return $this->_propDict["isSitesStorageLimitAutomatic"];
        } else {
            return null;
        }
    }

    /**
    * Sets the isSitesStorageLimitAutomatic
    * Indicates whether site storage space is automatically managed or if specific storage limits are set per site.
    *
    * @param bool $val The isSitesStorageLimitAutomatic
    *
    * @return SharepointSettings
    */
    public function setIsSitesStorageLimitAutomatic($val)
    {
        $this->_propDict["isSitesStorageLimitAutomatic"] = boolval($val);
        return $this;
    }

    /**
    * Gets the isSyncButtonHiddenOnPersonalSite
    * Indicates whether the sync button in OneDrive is hidden.
    *
    * @return bool|null The isSyncButtonHiddenOnPersonalSite
    */
    public function getIsSyncButtonHiddenOnPersonalSite()
    {
        if (array_key_exists("isSyncButtonHiddenOnPersonalSite", $this->_propDict)) {
            return $this->_propDict["isSyncButtonHiddenOnPersonalSite"];
        } else {
            return null;
        }
    }

    /**
    * Sets the isSyncButtonHiddenOnPersonalSite
    * Indicates whether the sync button in OneDrive is hidden.
    *
    * @param bool $val The isSyncButtonHiddenOnPersonalSite
    *
    * @return SharepointSettings
    */
    public function setIsSyncButtonHiddenOnPersonalSite($val)
    {
        $this->_propDict["isSyncButtonHiddenOnPersonalSite"] = boolval($val);
        return $this;
    }

    /**
    * Gets the isUnmanagedSyncAppForTenantRestricted
    * Indicates whether users are allowed to sync files only on PCs joined to specific domains.
    *
    * @return bool|null The isUnmanagedSyncAppForTenantRestricted
    */
    public function getIsUnmanagedSyncAppForTenantRestricted()
    {
        if (array_key_exists("isUnmanagedSyncAppForTenantRestricted", $this->_propDict)) {
            return $this->_propDict["isUnmanagedSyncAppForTenantRestricted"];
        } else {
            return null;
        }
    }

    /**
    * Sets the isUnmanagedSyncAppForTenantRestricted
    * Indicates whether users are allowed to sync files only on PCs joined to specific domains.
    *
    * @param bool $val The isUnmanagedSyncAppForTenantRestricted
    *
    * @return SharepointSettings
    */
    public function setIsUnmanagedSyncAppForTenantRestricted($val)
    {
        $this->_propDict["isUnmanagedSyncAppForTenantRestricted"] = boolval($val);
        return $this;
    }

    /**
    * Gets the personalSiteDefaultStorageLimitInMB
    * The default OneDrive storage limit for all new and existing users who are assigned a qualifying license. Measured in megabytes (MB).
    *
    * @return int|null The personalSiteDefaultStorageLimitInMB
    */
    public function getPersonalSiteDefaultStorageLimitInMB()
    {
        if (array_key_exists("personalSiteDefaultStorageLimitInMB", $this->_propDict)) {
            return $this->_propDict["personalSiteDefaultStorageLimitInMB"];
        } else {
            return null;
        }
    }

    /**
    * Sets the personalSiteDefaultStorageLimitInMB
    * The default OneDrive storage limit for all new and existing users who are assigned a qualifying license. Measured in megabytes (MB).
    *
    * @param int $val The personalSiteDefaultStorageLimitInMB
    *
    * @return SharepointSettings
    */
    public function setPersonalSiteDefaultStorageLimitInMB($val)
    {
        $this->_propDict["personalSiteDefaultStorageLimitInMB"] = intval($val);
        return $this;
    }

    /**
    * Gets the sharingAllowedDomainList
    * Collection of email domains that are allowed for sharing outside the organization.
    *
    * @return array|null The sharingAllowedDomainList
    */
    public function getSharingAllowedDomainList()
    {
        if (array_key_exists("sharingAllowedDomainList", $this->_propDict)) {
            return $this->_propDict["sharingAllowedDomainList"];
        } else {
            return null;
        }
    }

    /**
    * Sets the sharingAllowedDomainList
    * Collection of email domains that are allowed for sharing outside the organization.
    *
    * @param string[] $val The sharingAllowedDomainList
    *
    * @return SharepointSettings
    */
    public function setSharingAllowedDomainList($val)
    {
        $this->_propDict["sharingAllowedDomainList"] = $val;
        return $this;
    }

    /**
    * Gets the sharingBlockedDomainList
    * Collection of email domains that are blocked for sharing outside the organization.
    *
    * @return array|null The sharingBlockedDomainList
    */
    public function getSharingBlockedDomainList()
    {
        if (array_key_exists("sharingBlockedDomainList", $this->_propDict)) {
            return $this->_propDict["sharingBlockedDomainList"];
        } else {
            return null;
        }
    }

    /**
    * Sets the sharingBlockedDomainList
    * Collection of email domains that are blocked for sharing outside the organization.
    *
    * @param string[] $val The sharingBlockedDomainList
    *
    * @return SharepointSettings
    */
    public function setSharingBlockedDomainList($val)
    {
        $this->_propDict["sharingBlockedDomainList"] = $val;
        return $this;
    }

    /**
    * Gets the sharingCapability
    * Sharing capability for the tenant. Possible values are: disabled, externalUserSharingOnly, externalUserAndGuestSharing, existingExternalUserSharingOnly.
    *
    * @return SharingCapabilities|null The sharingCapability
    */
    public function getSharingCapability()
    {
        if (array_key_exists("sharingCapability", $this->_propDict)) {
            if (is_a($this->_propDict["sharingCapability"], "\Microsoft\Graph\Model\SharingCapabilities") || is_null($this->_propDict["sharingCapability"])) {
                return $this->_propDict["sharingCapability"];
            } else {
                $this->_propDict["sharingCapability"] = new SharingCapabilities($this->_propDict["sharingCapability"]);
                return $this->_propDict["sharingCapability"];
            }
        }
        return null;
    }

    /**
    * Sets the sharingCapability
    * Sharing capability for the tenant. Possible values are: disabled, externalUserSharingOnly, externalUserAndGuestSharing, existingExternalUserSharingOnly.
    *
    * @param SharingCapabilities $val The sharingCapability
    *
    * @return SharepointSettings
    */
    public function setSharingCapability($val)
    {
        $this->_propDict["sharingCapability"] = $val;
        return $this;
    }

    /**
    * Gets the sharingDomainRestrictionMode
    * Specifies the external sharing mode for domains. Possible values are: none, allowList, blockList.
    *
    * @return SharingDomainRestrictionMode|null The sharingDomainRestrictionMode
    */
    public function getSharingDomainRestrictionMode()
    {
        if (array_key_exists("sharingDomainRestrictionMode", $this->_propDict)) {
            if (is_a($this->_propDict["sharingDomainRestrictionMode"], "\Microsoft\Graph\Model\SharingDomainRestrictionMode") || is_null($this->_propDict["sharingDomainRestrictionMode"])) {
                return $this->_propDict["sharingDomainRestrictionMode"];
            } else {
                $this->_propDict["sharingDomainRestrictionMode"] = new SharingDomainRestrictionMode($this->_propDict["sharingDomainRestrictionMode"]);
                return $this->_propDict["sharingDomainRestrictionMode"];
            }
        }
        return null;
    }

    /**
    * Sets the sharingDomainRestrictionMode
    * Specifies the external sharing mode for domains. Possible values are: none, allowList, blockList.
    *
    * @param SharingDomainRestrictionMode $val The sharingDomainRestrictionMode
    *
    * @return SharepointSettings
    */
    public function setSharingDomainRestrictionMode($val)
    {
        $this->_propDict["sharingDomainRestrictionMode"] = $val;
        return $this;
    }

    /**
    * Gets the siteCreationDefaultManagedPath
    * The value of the team site managed path. This is the path under which new team sites will be created.
    *
    * @return string|null The siteCreationDefaultManagedPath
    */
    public function getSiteCreationDefaultManagedPath()
    {
        if (array_key_exists("siteCreationDefaultManagedPath", $this->_propDict)) {
            return $this->_propDict["siteCreationDefaultManagedPath"];
        } else {
            return null;
        }
    }

    /**
    * Sets the siteCreationDefaultManagedPath
    * The value of the team site managed path. This is the path under which new team sites will be created.
    *
    * @param string $val The siteCreationDefaultManagedPath
    *
    * @return SharepointSettings
    */
    public function setSiteCreationDefaultManagedPath($val)
    {
        $this->_propDict["siteCreationDefaultManagedPath"] = $val;
        return $this;
    }

    /**
    * Gets the siteCreationDefaultStorageLimitInMB
    * The default storage quota for a new site upon creation. Measured in megabytes (MB).
    *
    * @return int|null The siteCreationDefaultStorageLimitInMB
    */
    public function getSiteCreationDefaultStorageLimitInMB()
    {
        if (array_key_exists("siteCreationDefaultStorageLimitInMB", $this->_propDict)) {
            return $this->_propDict["siteCreationDefaultStorageLimitInMB"];
        } else {
            return null;
        }
    }

    /**
    * Sets the siteCreationDefaultStorageLimitInMB
    * The default storage quota for a new site upon creation. Measured in megabytes (MB).
    *
    * @param int $val The siteCreationDefaultStorageLimitInMB
    *
    * @return SharepointSettings
    */
    public function setSiteCreationDefaultStorageLimitInMB($val)
    {
        $this->_propDict["siteCreationDefaultStorageLimitInMB"] = intval($val);
        return $this;
    }

    /**
    * Gets the tenantDefaultTimezone
    * The default timezone of a tenant for newly created sites. For a list of possible values, see SPRegionalSettings.TimeZones property.
    *
    * @return string|null The tenantDefaultTimezone
    */
    public function getTenantDefaultTimezone()
    {
        if (array_key_exists("tenantDefaultTimezone", $this->_propDict)) {
            return $this->_propDict["tenantDefaultTimezone"];
        } else {
            return null;
        }
    }

    /**
    * Sets the tenantDefaultTimezone
    * The default timezone of a tenant for newly created sites. For a list of possible values, see SPRegionalSettings.TimeZones property.
    *
    * @param string $val The tenantDefaultTimezone
    *
    * @return SharepointSettings
    */
    public function setTenantDefaultTimezone($val)
    {
        $this->_propDict["tenantDefaultTimezone"] = $val;
        return $this;
    }

}
