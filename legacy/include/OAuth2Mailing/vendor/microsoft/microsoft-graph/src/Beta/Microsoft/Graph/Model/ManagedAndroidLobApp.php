<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* ManagedAndroidLobApp File
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
* ManagedAndroidLobApp class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class ManagedAndroidLobApp extends ManagedMobileLobApp
{
    /**
    * Gets the identityName
    * The Identity Name. This property is being deprecated in 2302(February 2023).
    *
    * @return string|null The identityName
    */
    public function getIdentityName()
    {
        if (array_key_exists("identityName", $this->_propDict)) {
            return $this->_propDict["identityName"];
        } else {
            return null;
        }
    }

    /**
    * Sets the identityName
    * The Identity Name. This property is being deprecated in 2302(February 2023).
    *
    * @param string $val The identityName
    *
    * @return ManagedAndroidLobApp
    */
    public function setIdentityName($val)
    {
        $this->_propDict["identityName"] = $val;
        return $this;
    }

    /**
    * Gets the identityVersion
    * The identity version. This property is being deprecated in 2302(February 2023).
    *
    * @return string|null The identityVersion
    */
    public function getIdentityVersion()
    {
        if (array_key_exists("identityVersion", $this->_propDict)) {
            return $this->_propDict["identityVersion"];
        } else {
            return null;
        }
    }

    /**
    * Sets the identityVersion
    * The identity version. This property is being deprecated in 2302(February 2023).
    *
    * @param string $val The identityVersion
    *
    * @return ManagedAndroidLobApp
    */
    public function setIdentityVersion($val)
    {
        $this->_propDict["identityVersion"] = $val;
        return $this;
    }

    /**
    * Gets the minimumSupportedOperatingSystem
    * The value for the minimum applicable operating system.
    *
    * @return AndroidMinimumOperatingSystem|null The minimumSupportedOperatingSystem
    */
    public function getMinimumSupportedOperatingSystem()
    {
        if (array_key_exists("minimumSupportedOperatingSystem", $this->_propDict)) {
            if (is_a($this->_propDict["minimumSupportedOperatingSystem"], "\Beta\Microsoft\Graph\Model\AndroidMinimumOperatingSystem") || is_null($this->_propDict["minimumSupportedOperatingSystem"])) {
                return $this->_propDict["minimumSupportedOperatingSystem"];
            } else {
                $this->_propDict["minimumSupportedOperatingSystem"] = new AndroidMinimumOperatingSystem($this->_propDict["minimumSupportedOperatingSystem"]);
                return $this->_propDict["minimumSupportedOperatingSystem"];
            }
        }
        return null;
    }

    /**
    * Sets the minimumSupportedOperatingSystem
    * The value for the minimum applicable operating system.
    *
    * @param AndroidMinimumOperatingSystem $val The minimumSupportedOperatingSystem
    *
    * @return ManagedAndroidLobApp
    */
    public function setMinimumSupportedOperatingSystem($val)
    {
        $this->_propDict["minimumSupportedOperatingSystem"] = $val;
        return $this;
    }

    /**
    * Gets the packageId
    * The package identifier.
    *
    * @return string|null The packageId
    */
    public function getPackageId()
    {
        if (array_key_exists("packageId", $this->_propDict)) {
            return $this->_propDict["packageId"];
        } else {
            return null;
        }
    }

    /**
    * Sets the packageId
    * The package identifier.
    *
    * @param string $val The packageId
    *
    * @return ManagedAndroidLobApp
    */
    public function setPackageId($val)
    {
        $this->_propDict["packageId"] = $val;
        return $this;
    }

    /**
    * Gets the targetedPlatforms
    * The platforms to which the application can be targeted. If not specified, will defauilt to Android Device Administrator. Possible values are: androidDeviceAdministrator, androidOpenSourceProject, unknownFutureValue.
    *
    * @return AndroidTargetedPlatforms|null The targetedPlatforms
    */
    public function getTargetedPlatforms()
    {
        if (array_key_exists("targetedPlatforms", $this->_propDict)) {
            if (is_a($this->_propDict["targetedPlatforms"], "\Beta\Microsoft\Graph\Model\AndroidTargetedPlatforms") || is_null($this->_propDict["targetedPlatforms"])) {
                return $this->_propDict["targetedPlatforms"];
            } else {
                $this->_propDict["targetedPlatforms"] = new AndroidTargetedPlatforms($this->_propDict["targetedPlatforms"]);
                return $this->_propDict["targetedPlatforms"];
            }
        }
        return null;
    }

    /**
    * Sets the targetedPlatforms
    * The platforms to which the application can be targeted. If not specified, will defauilt to Android Device Administrator. Possible values are: androidDeviceAdministrator, androidOpenSourceProject, unknownFutureValue.
    *
    * @param AndroidTargetedPlatforms $val The targetedPlatforms
    *
    * @return ManagedAndroidLobApp
    */
    public function setTargetedPlatforms($val)
    {
        $this->_propDict["targetedPlatforms"] = $val;
        return $this;
    }

    /**
    * Gets the versionCode
    * The version code of managed Android Line of Business (LoB) app.
    *
    * @return string|null The versionCode
    */
    public function getVersionCode()
    {
        if (array_key_exists("versionCode", $this->_propDict)) {
            return $this->_propDict["versionCode"];
        } else {
            return null;
        }
    }

    /**
    * Sets the versionCode
    * The version code of managed Android Line of Business (LoB) app.
    *
    * @param string $val The versionCode
    *
    * @return ManagedAndroidLobApp
    */
    public function setVersionCode($val)
    {
        $this->_propDict["versionCode"] = $val;
        return $this;
    }

    /**
    * Gets the versionName
    * The version name of managed Android Line of Business (LoB) app.
    *
    * @return string|null The versionName
    */
    public function getVersionName()
    {
        if (array_key_exists("versionName", $this->_propDict)) {
            return $this->_propDict["versionName"];
        } else {
            return null;
        }
    }

    /**
    * Sets the versionName
    * The version name of managed Android Line of Business (LoB) app.
    *
    * @param string $val The versionName
    *
    * @return ManagedAndroidLobApp
    */
    public function setVersionName($val)
    {
        $this->_propDict["versionName"] = $val;
        return $this;
    }

}
