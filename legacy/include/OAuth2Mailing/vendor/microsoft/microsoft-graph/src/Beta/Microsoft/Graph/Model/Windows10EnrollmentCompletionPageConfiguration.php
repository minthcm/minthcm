<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* Windows10EnrollmentCompletionPageConfiguration File
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
* Windows10EnrollmentCompletionPageConfiguration class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class Windows10EnrollmentCompletionPageConfiguration extends DeviceEnrollmentConfiguration
{
    /**
    * Gets the allowDeviceResetOnInstallFailure
    * Allow or block device reset on installation failure
    *
    * @return bool|null The allowDeviceResetOnInstallFailure
    */
    public function getAllowDeviceResetOnInstallFailure()
    {
        if (array_key_exists("allowDeviceResetOnInstallFailure", $this->_propDict)) {
            return $this->_propDict["allowDeviceResetOnInstallFailure"];
        } else {
            return null;
        }
    }

    /**
    * Sets the allowDeviceResetOnInstallFailure
    * Allow or block device reset on installation failure
    *
    * @param bool $val The allowDeviceResetOnInstallFailure
    *
    * @return Windows10EnrollmentCompletionPageConfiguration
    */
    public function setAllowDeviceResetOnInstallFailure($val)
    {
        $this->_propDict["allowDeviceResetOnInstallFailure"] = boolval($val);
        return $this;
    }

    /**
    * Gets the allowDeviceUseOnInstallFailure
    * Allow the user to continue using the device on installation failure
    *
    * @return bool|null The allowDeviceUseOnInstallFailure
    */
    public function getAllowDeviceUseOnInstallFailure()
    {
        if (array_key_exists("allowDeviceUseOnInstallFailure", $this->_propDict)) {
            return $this->_propDict["allowDeviceUseOnInstallFailure"];
        } else {
            return null;
        }
    }

    /**
    * Sets the allowDeviceUseOnInstallFailure
    * Allow the user to continue using the device on installation failure
    *
    * @param bool $val The allowDeviceUseOnInstallFailure
    *
    * @return Windows10EnrollmentCompletionPageConfiguration
    */
    public function setAllowDeviceUseOnInstallFailure($val)
    {
        $this->_propDict["allowDeviceUseOnInstallFailure"] = boolval($val);
        return $this;
    }

    /**
    * Gets the allowLogCollectionOnInstallFailure
    * Allow or block log collection on installation failure
    *
    * @return bool|null The allowLogCollectionOnInstallFailure
    */
    public function getAllowLogCollectionOnInstallFailure()
    {
        if (array_key_exists("allowLogCollectionOnInstallFailure", $this->_propDict)) {
            return $this->_propDict["allowLogCollectionOnInstallFailure"];
        } else {
            return null;
        }
    }

    /**
    * Sets the allowLogCollectionOnInstallFailure
    * Allow or block log collection on installation failure
    *
    * @param bool $val The allowLogCollectionOnInstallFailure
    *
    * @return Windows10EnrollmentCompletionPageConfiguration
    */
    public function setAllowLogCollectionOnInstallFailure($val)
    {
        $this->_propDict["allowLogCollectionOnInstallFailure"] = boolval($val);
        return $this;
    }

    /**
    * Gets the allowNonBlockingAppInstallation
    * Install all required apps as non blocking apps during white glove
    *
    * @return bool|null The allowNonBlockingAppInstallation
    */
    public function getAllowNonBlockingAppInstallation()
    {
        if (array_key_exists("allowNonBlockingAppInstallation", $this->_propDict)) {
            return $this->_propDict["allowNonBlockingAppInstallation"];
        } else {
            return null;
        }
    }

    /**
    * Sets the allowNonBlockingAppInstallation
    * Install all required apps as non blocking apps during white glove
    *
    * @param bool $val The allowNonBlockingAppInstallation
    *
    * @return Windows10EnrollmentCompletionPageConfiguration
    */
    public function setAllowNonBlockingAppInstallation($val)
    {
        $this->_propDict["allowNonBlockingAppInstallation"] = boolval($val);
        return $this;
    }

    /**
    * Gets the blockDeviceSetupRetryByUser
    * Allow the user to retry the setup on installation failure
    *
    * @return bool|null The blockDeviceSetupRetryByUser
    */
    public function getBlockDeviceSetupRetryByUser()
    {
        if (array_key_exists("blockDeviceSetupRetryByUser", $this->_propDict)) {
            return $this->_propDict["blockDeviceSetupRetryByUser"];
        } else {
            return null;
        }
    }

    /**
    * Sets the blockDeviceSetupRetryByUser
    * Allow the user to retry the setup on installation failure
    *
    * @param bool $val The blockDeviceSetupRetryByUser
    *
    * @return Windows10EnrollmentCompletionPageConfiguration
    */
    public function setBlockDeviceSetupRetryByUser($val)
    {
        $this->_propDict["blockDeviceSetupRetryByUser"] = boolval($val);
        return $this;
    }

    /**
    * Gets the customErrorMessage
    * Set custom error message to show upon installation failure
    *
    * @return string|null The customErrorMessage
    */
    public function getCustomErrorMessage()
    {
        if (array_key_exists("customErrorMessage", $this->_propDict)) {
            return $this->_propDict["customErrorMessage"];
        } else {
            return null;
        }
    }

    /**
    * Sets the customErrorMessage
    * Set custom error message to show upon installation failure
    *
    * @param string $val The customErrorMessage
    *
    * @return Windows10EnrollmentCompletionPageConfiguration
    */
    public function setCustomErrorMessage($val)
    {
        $this->_propDict["customErrorMessage"] = $val;
        return $this;
    }

    /**
    * Gets the disableUserStatusTrackingAfterFirstUser
    * Only show installation progress for first user post enrollment
    *
    * @return bool|null The disableUserStatusTrackingAfterFirstUser
    */
    public function getDisableUserStatusTrackingAfterFirstUser()
    {
        if (array_key_exists("disableUserStatusTrackingAfterFirstUser", $this->_propDict)) {
            return $this->_propDict["disableUserStatusTrackingAfterFirstUser"];
        } else {
            return null;
        }
    }

    /**
    * Sets the disableUserStatusTrackingAfterFirstUser
    * Only show installation progress for first user post enrollment
    *
    * @param bool $val The disableUserStatusTrackingAfterFirstUser
    *
    * @return Windows10EnrollmentCompletionPageConfiguration
    */
    public function setDisableUserStatusTrackingAfterFirstUser($val)
    {
        $this->_propDict["disableUserStatusTrackingAfterFirstUser"] = boolval($val);
        return $this;
    }

    /**
    * Gets the installProgressTimeoutInMinutes
    * Set installation progress timeout in minutes
    *
    * @return int|null The installProgressTimeoutInMinutes
    */
    public function getInstallProgressTimeoutInMinutes()
    {
        if (array_key_exists("installProgressTimeoutInMinutes", $this->_propDict)) {
            return $this->_propDict["installProgressTimeoutInMinutes"];
        } else {
            return null;
        }
    }

    /**
    * Sets the installProgressTimeoutInMinutes
    * Set installation progress timeout in minutes
    *
    * @param int $val The installProgressTimeoutInMinutes
    *
    * @return Windows10EnrollmentCompletionPageConfiguration
    */
    public function setInstallProgressTimeoutInMinutes($val)
    {
        $this->_propDict["installProgressTimeoutInMinutes"] = intval($val);
        return $this;
    }

    /**
    * Gets the installQualityUpdates
    * Allows quality updates installation during OOBE
    *
    * @return bool|null The installQualityUpdates
    */
    public function getInstallQualityUpdates()
    {
        if (array_key_exists("installQualityUpdates", $this->_propDict)) {
            return $this->_propDict["installQualityUpdates"];
        } else {
            return null;
        }
    }

    /**
    * Sets the installQualityUpdates
    * Allows quality updates installation during OOBE
    *
    * @param bool $val The installQualityUpdates
    *
    * @return Windows10EnrollmentCompletionPageConfiguration
    */
    public function setInstallQualityUpdates($val)
    {
        $this->_propDict["installQualityUpdates"] = boolval($val);
        return $this;
    }

    /**
    * Gets the selectedMobileAppIds
    * Selected applications to track the installation status
    *
    * @return array|null The selectedMobileAppIds
    */
    public function getSelectedMobileAppIds()
    {
        if (array_key_exists("selectedMobileAppIds", $this->_propDict)) {
            return $this->_propDict["selectedMobileAppIds"];
        } else {
            return null;
        }
    }

    /**
    * Sets the selectedMobileAppIds
    * Selected applications to track the installation status
    *
    * @param string[] $val The selectedMobileAppIds
    *
    * @return Windows10EnrollmentCompletionPageConfiguration
    */
    public function setSelectedMobileAppIds($val)
    {
        $this->_propDict["selectedMobileAppIds"] = $val;
        return $this;
    }

    /**
    * Gets the showInstallationProgress
    * Show or hide installation progress to user
    *
    * @return bool|null The showInstallationProgress
    */
    public function getShowInstallationProgress()
    {
        if (array_key_exists("showInstallationProgress", $this->_propDict)) {
            return $this->_propDict["showInstallationProgress"];
        } else {
            return null;
        }
    }

    /**
    * Sets the showInstallationProgress
    * Show or hide installation progress to user
    *
    * @param bool $val The showInstallationProgress
    *
    * @return Windows10EnrollmentCompletionPageConfiguration
    */
    public function setShowInstallationProgress($val)
    {
        $this->_propDict["showInstallationProgress"] = boolval($val);
        return $this;
    }

    /**
    * Gets the trackInstallProgressForAutopilotOnly
    * Only show installation progress for Autopilot enrollment scenarios
    *
    * @return bool|null The trackInstallProgressForAutopilotOnly
    */
    public function getTrackInstallProgressForAutopilotOnly()
    {
        if (array_key_exists("trackInstallProgressForAutopilotOnly", $this->_propDict)) {
            return $this->_propDict["trackInstallProgressForAutopilotOnly"];
        } else {
            return null;
        }
    }

    /**
    * Sets the trackInstallProgressForAutopilotOnly
    * Only show installation progress for Autopilot enrollment scenarios
    *
    * @param bool $val The trackInstallProgressForAutopilotOnly
    *
    * @return Windows10EnrollmentCompletionPageConfiguration
    */
    public function setTrackInstallProgressForAutopilotOnly($val)
    {
        $this->_propDict["trackInstallProgressForAutopilotOnly"] = boolval($val);
        return $this;
    }

}
