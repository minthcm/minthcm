<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* HardwareInformation File
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
* HardwareInformation class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class HardwareInformation extends Entity
{
    /**
    * Gets the batteryChargeCycles
    * The number of charge cycles the device’s current battery has gone through. Valid values 0 to 2147483647
    *
    * @return int|null The batteryChargeCycles
    */
    public function getBatteryChargeCycles()
    {
        if (array_key_exists("batteryChargeCycles", $this->_propDict)) {
            return $this->_propDict["batteryChargeCycles"];
        } else {
            return null;
        }
    }

    /**
    * Sets the batteryChargeCycles
    * The number of charge cycles the device’s current battery has gone through. Valid values 0 to 2147483647
    *
    * @param int $val The value of the batteryChargeCycles
    *
    * @return HardwareInformation
    */
    public function setBatteryChargeCycles($val)
    {
        $this->_propDict["batteryChargeCycles"] = $val;
        return $this;
    }
    /**
    * Gets the batteryHealthPercentage
    * The device’s current battery’s health percentage. Valid values 0 to 100
    *
    * @return int|null The batteryHealthPercentage
    */
    public function getBatteryHealthPercentage()
    {
        if (array_key_exists("batteryHealthPercentage", $this->_propDict)) {
            return $this->_propDict["batteryHealthPercentage"];
        } else {
            return null;
        }
    }

    /**
    * Sets the batteryHealthPercentage
    * The device’s current battery’s health percentage. Valid values 0 to 100
    *
    * @param int $val The value of the batteryHealthPercentage
    *
    * @return HardwareInformation
    */
    public function setBatteryHealthPercentage($val)
    {
        $this->_propDict["batteryHealthPercentage"] = $val;
        return $this;
    }
    /**
    * Gets the batteryLevelPercentage
    * The battery level, between 0.0 and 100, or null if the battery level cannot be determined. The update frequency of this property is per-checkin. Note this property is currently supported only on devices running iOS 5.0 and later, and is available only when Device Information access right is obtained. Valid values 0 to 100
    *
    * @return float|null The batteryLevelPercentage
    */
    public function getBatteryLevelPercentage()
    {
        if (array_key_exists("batteryLevelPercentage", $this->_propDict)) {
            return $this->_propDict["batteryLevelPercentage"];
        } else {
            return null;
        }
    }

    /**
    * Sets the batteryLevelPercentage
    * The battery level, between 0.0 and 100, or null if the battery level cannot be determined. The update frequency of this property is per-checkin. Note this property is currently supported only on devices running iOS 5.0 and later, and is available only when Device Information access right is obtained. Valid values 0 to 100
    *
    * @param float $val The value of the batteryLevelPercentage
    *
    * @return HardwareInformation
    */
    public function setBatteryLevelPercentage($val)
    {
        $this->_propDict["batteryLevelPercentage"] = $val;
        return $this;
    }
    /**
    * Gets the batterySerialNumber
    * The serial number of the device’s current battery
    *
    * @return string|null The batterySerialNumber
    */
    public function getBatterySerialNumber()
    {
        if (array_key_exists("batterySerialNumber", $this->_propDict)) {
            return $this->_propDict["batterySerialNumber"];
        } else {
            return null;
        }
    }

    /**
    * Sets the batterySerialNumber
    * The serial number of the device’s current battery
    *
    * @param string $val The value of the batterySerialNumber
    *
    * @return HardwareInformation
    */
    public function setBatterySerialNumber($val)
    {
        $this->_propDict["batterySerialNumber"] = $val;
        return $this;
    }
    /**
    * Gets the cellularTechnology
    * Cellular technology of the device
    *
    * @return string|null The cellularTechnology
    */
    public function getCellularTechnology()
    {
        if (array_key_exists("cellularTechnology", $this->_propDict)) {
            return $this->_propDict["cellularTechnology"];
        } else {
            return null;
        }
    }

    /**
    * Sets the cellularTechnology
    * Cellular technology of the device
    *
    * @param string $val The value of the cellularTechnology
    *
    * @return HardwareInformation
    */
    public function setCellularTechnology($val)
    {
        $this->_propDict["cellularTechnology"] = $val;
        return $this;
    }
    /**
    * Gets the deviceFullQualifiedDomainName
    * Returns the fully qualified domain name of the device (if any). If the device is not domain-joined, it returns an empty string.
    *
    * @return string|null The deviceFullQualifiedDomainName
    */
    public function getDeviceFullQualifiedDomainName()
    {
        if (array_key_exists("deviceFullQualifiedDomainName", $this->_propDict)) {
            return $this->_propDict["deviceFullQualifiedDomainName"];
        } else {
            return null;
        }
    }

    /**
    * Sets the deviceFullQualifiedDomainName
    * Returns the fully qualified domain name of the device (if any). If the device is not domain-joined, it returns an empty string.
    *
    * @param string $val The value of the deviceFullQualifiedDomainName
    *
    * @return HardwareInformation
    */
    public function setDeviceFullQualifiedDomainName($val)
    {
        $this->_propDict["deviceFullQualifiedDomainName"] = $val;
        return $this;
    }

    /**
    * Gets the deviceGuardLocalSystemAuthorityCredentialGuardState
    * Local System Authority (LSA) credential guard status. . Possible values are: running, rebootRequired, notLicensed, notConfigured, virtualizationBasedSecurityNotRunning.
    *
    * @return DeviceGuardLocalSystemAuthorityCredentialGuardState|null The deviceGuardLocalSystemAuthorityCredentialGuardState
    */
    public function getDeviceGuardLocalSystemAuthorityCredentialGuardState()
    {
        if (array_key_exists("deviceGuardLocalSystemAuthorityCredentialGuardState", $this->_propDict)) {
            if (is_a($this->_propDict["deviceGuardLocalSystemAuthorityCredentialGuardState"], "\Beta\Microsoft\Graph\Model\DeviceGuardLocalSystemAuthorityCredentialGuardState") || is_null($this->_propDict["deviceGuardLocalSystemAuthorityCredentialGuardState"])) {
                return $this->_propDict["deviceGuardLocalSystemAuthorityCredentialGuardState"];
            } else {
                $this->_propDict["deviceGuardLocalSystemAuthorityCredentialGuardState"] = new DeviceGuardLocalSystemAuthorityCredentialGuardState($this->_propDict["deviceGuardLocalSystemAuthorityCredentialGuardState"]);
                return $this->_propDict["deviceGuardLocalSystemAuthorityCredentialGuardState"];
            }
        }
        return null;
    }

    /**
    * Sets the deviceGuardLocalSystemAuthorityCredentialGuardState
    * Local System Authority (LSA) credential guard status. . Possible values are: running, rebootRequired, notLicensed, notConfigured, virtualizationBasedSecurityNotRunning.
    *
    * @param DeviceGuardLocalSystemAuthorityCredentialGuardState $val The value to assign to the deviceGuardLocalSystemAuthorityCredentialGuardState
    *
    * @return HardwareInformation The HardwareInformation
    */
    public function setDeviceGuardLocalSystemAuthorityCredentialGuardState($val)
    {
        $this->_propDict["deviceGuardLocalSystemAuthorityCredentialGuardState"] = $val;
         return $this;
    }

    /**
    * Gets the deviceGuardVirtualizationBasedSecurityHardwareRequirementState
    * Virtualization-based security hardware requirement status. Possible values are: meetHardwareRequirements, secureBootRequired, dmaProtectionRequired, hyperVNotSupportedForGuestVM, hyperVNotAvailable.
    *
    * @return DeviceGuardVirtualizationBasedSecurityHardwareRequirementState|null The deviceGuardVirtualizationBasedSecurityHardwareRequirementState
    */
    public function getDeviceGuardVirtualizationBasedSecurityHardwareRequirementState()
    {
        if (array_key_exists("deviceGuardVirtualizationBasedSecurityHardwareRequirementState", $this->_propDict)) {
            if (is_a($this->_propDict["deviceGuardVirtualizationBasedSecurityHardwareRequirementState"], "\Beta\Microsoft\Graph\Model\DeviceGuardVirtualizationBasedSecurityHardwareRequirementState") || is_null($this->_propDict["deviceGuardVirtualizationBasedSecurityHardwareRequirementState"])) {
                return $this->_propDict["deviceGuardVirtualizationBasedSecurityHardwareRequirementState"];
            } else {
                $this->_propDict["deviceGuardVirtualizationBasedSecurityHardwareRequirementState"] = new DeviceGuardVirtualizationBasedSecurityHardwareRequirementState($this->_propDict["deviceGuardVirtualizationBasedSecurityHardwareRequirementState"]);
                return $this->_propDict["deviceGuardVirtualizationBasedSecurityHardwareRequirementState"];
            }
        }
        return null;
    }

    /**
    * Sets the deviceGuardVirtualizationBasedSecurityHardwareRequirementState
    * Virtualization-based security hardware requirement status. Possible values are: meetHardwareRequirements, secureBootRequired, dmaProtectionRequired, hyperVNotSupportedForGuestVM, hyperVNotAvailable.
    *
    * @param DeviceGuardVirtualizationBasedSecurityHardwareRequirementState $val The value to assign to the deviceGuardVirtualizationBasedSecurityHardwareRequirementState
    *
    * @return HardwareInformation The HardwareInformation
    */
    public function setDeviceGuardVirtualizationBasedSecurityHardwareRequirementState($val)
    {
        $this->_propDict["deviceGuardVirtualizationBasedSecurityHardwareRequirementState"] = $val;
         return $this;
    }

    /**
    * Gets the deviceGuardVirtualizationBasedSecurityState
    * Virtualization-based security status. . Possible values are: running, rebootRequired, require64BitArchitecture, notLicensed, notConfigured, doesNotMeetHardwareRequirements, other.
    *
    * @return DeviceGuardVirtualizationBasedSecurityState|null The deviceGuardVirtualizationBasedSecurityState
    */
    public function getDeviceGuardVirtualizationBasedSecurityState()
    {
        if (array_key_exists("deviceGuardVirtualizationBasedSecurityState", $this->_propDict)) {
            if (is_a($this->_propDict["deviceGuardVirtualizationBasedSecurityState"], "\Beta\Microsoft\Graph\Model\DeviceGuardVirtualizationBasedSecurityState") || is_null($this->_propDict["deviceGuardVirtualizationBasedSecurityState"])) {
                return $this->_propDict["deviceGuardVirtualizationBasedSecurityState"];
            } else {
                $this->_propDict["deviceGuardVirtualizationBasedSecurityState"] = new DeviceGuardVirtualizationBasedSecurityState($this->_propDict["deviceGuardVirtualizationBasedSecurityState"]);
                return $this->_propDict["deviceGuardVirtualizationBasedSecurityState"];
            }
        }
        return null;
    }

    /**
    * Sets the deviceGuardVirtualizationBasedSecurityState
    * Virtualization-based security status. . Possible values are: running, rebootRequired, require64BitArchitecture, notLicensed, notConfigured, doesNotMeetHardwareRequirements, other.
    *
    * @param DeviceGuardVirtualizationBasedSecurityState $val The value to assign to the deviceGuardVirtualizationBasedSecurityState
    *
    * @return HardwareInformation The HardwareInformation
    */
    public function setDeviceGuardVirtualizationBasedSecurityState($val)
    {
        $this->_propDict["deviceGuardVirtualizationBasedSecurityState"] = $val;
         return $this;
    }
    /**
    * Gets the deviceLicensingLastErrorCode
    * A standard error code indicating the last error, or 0 indicating no error (default). The update frequency of this property is daily. Note this property is currently supported only for Windows based Device based subscription licensing. Valid values 0 to 2147483647
    *
    * @return int|null The deviceLicensingLastErrorCode
    */
    public function getDeviceLicensingLastErrorCode()
    {
        if (array_key_exists("deviceLicensingLastErrorCode", $this->_propDict)) {
            return $this->_propDict["deviceLicensingLastErrorCode"];
        } else {
            return null;
        }
    }

    /**
    * Sets the deviceLicensingLastErrorCode
    * A standard error code indicating the last error, or 0 indicating no error (default). The update frequency of this property is daily. Note this property is currently supported only for Windows based Device based subscription licensing. Valid values 0 to 2147483647
    *
    * @param int $val The value of the deviceLicensingLastErrorCode
    *
    * @return HardwareInformation
    */
    public function setDeviceLicensingLastErrorCode($val)
    {
        $this->_propDict["deviceLicensingLastErrorCode"] = $val;
        return $this;
    }
    /**
    * Gets the deviceLicensingLastErrorDescription
    * Error text message as a descripition for deviceLicensingLastErrorCode. The update frequency of this property is daily. Note this property is currently supported only for Windows based Device based subscription licensing.
    *
    * @return string|null The deviceLicensingLastErrorDescription
    */
    public function getDeviceLicensingLastErrorDescription()
    {
        if (array_key_exists("deviceLicensingLastErrorDescription", $this->_propDict)) {
            return $this->_propDict["deviceLicensingLastErrorDescription"];
        } else {
            return null;
        }
    }

    /**
    * Sets the deviceLicensingLastErrorDescription
    * Error text message as a descripition for deviceLicensingLastErrorCode. The update frequency of this property is daily. Note this property is currently supported only for Windows based Device based subscription licensing.
    *
    * @param string $val The value of the deviceLicensingLastErrorDescription
    *
    * @return HardwareInformation
    */
    public function setDeviceLicensingLastErrorDescription($val)
    {
        $this->_propDict["deviceLicensingLastErrorDescription"] = $val;
        return $this;
    }

    /**
    * Gets the deviceLicensingStatus
    * Device based subscription licensing status. The update frequency of this property is daily. Note this property is currently supported only for Windows based Device based subscription licensing. In case it is not supported, the value will be set to unknown (-1). Possible values are: licenseRefreshStarted, licenseRefreshPending, deviceIsNotAzureActiveDirectoryJoined, verifyingMicrosoftDeviceIdentity, deviceIdentityVerificationFailed, verifyingMicrosoftAccountIdentity, microsoftAccountVerificationFailed, acquiringDeviceLicense, refreshingDeviceLicense, deviceLicenseRefreshSucceed, deviceLicenseRefreshFailed, removingDeviceLicense, deviceLicenseRemoveSucceed, deviceLicenseRemoveFailed, unknownFutureValue, unknown.
    *
    * @return DeviceLicensingStatus|null The deviceLicensingStatus
    */
    public function getDeviceLicensingStatus()
    {
        if (array_key_exists("deviceLicensingStatus", $this->_propDict)) {
            if (is_a($this->_propDict["deviceLicensingStatus"], "\Beta\Microsoft\Graph\Model\DeviceLicensingStatus") || is_null($this->_propDict["deviceLicensingStatus"])) {
                return $this->_propDict["deviceLicensingStatus"];
            } else {
                $this->_propDict["deviceLicensingStatus"] = new DeviceLicensingStatus($this->_propDict["deviceLicensingStatus"]);
                return $this->_propDict["deviceLicensingStatus"];
            }
        }
        return null;
    }

    /**
    * Sets the deviceLicensingStatus
    * Device based subscription licensing status. The update frequency of this property is daily. Note this property is currently supported only for Windows based Device based subscription licensing. In case it is not supported, the value will be set to unknown (-1). Possible values are: licenseRefreshStarted, licenseRefreshPending, deviceIsNotAzureActiveDirectoryJoined, verifyingMicrosoftDeviceIdentity, deviceIdentityVerificationFailed, verifyingMicrosoftAccountIdentity, microsoftAccountVerificationFailed, acquiringDeviceLicense, refreshingDeviceLicense, deviceLicenseRefreshSucceed, deviceLicenseRefreshFailed, removingDeviceLicense, deviceLicenseRemoveSucceed, deviceLicenseRemoveFailed, unknownFutureValue, unknown.
    *
    * @param DeviceLicensingStatus $val The value to assign to the deviceLicensingStatus
    *
    * @return HardwareInformation The HardwareInformation
    */
    public function setDeviceLicensingStatus($val)
    {
        $this->_propDict["deviceLicensingStatus"] = $val;
         return $this;
    }
    /**
    * Gets the esimIdentifier
    * eSIM identifier
    *
    * @return string|null The esimIdentifier
    */
    public function getEsimIdentifier()
    {
        if (array_key_exists("esimIdentifier", $this->_propDict)) {
            return $this->_propDict["esimIdentifier"];
        } else {
            return null;
        }
    }

    /**
    * Sets the esimIdentifier
    * eSIM identifier
    *
    * @param string $val The value of the esimIdentifier
    *
    * @return HardwareInformation
    */
    public function setEsimIdentifier($val)
    {
        $this->_propDict["esimIdentifier"] = $val;
        return $this;
    }
    /**
    * Gets the freeStorageSpace
    * Free storage space of the device.
    *
    * @return int|null The freeStorageSpace
    */
    public function getFreeStorageSpace()
    {
        if (array_key_exists("freeStorageSpace", $this->_propDict)) {
            return $this->_propDict["freeStorageSpace"];
        } else {
            return null;
        }
    }

    /**
    * Sets the freeStorageSpace
    * Free storage space of the device.
    *
    * @param int $val The value of the freeStorageSpace
    *
    * @return HardwareInformation
    */
    public function setFreeStorageSpace($val)
    {
        $this->_propDict["freeStorageSpace"] = $val;
        return $this;
    }
    /**
    * Gets the imei
    * IMEI
    *
    * @return string|null The imei
    */
    public function getImei()
    {
        if (array_key_exists("imei", $this->_propDict)) {
            return $this->_propDict["imei"];
        } else {
            return null;
        }
    }

    /**
    * Sets the imei
    * IMEI
    *
    * @param string $val The value of the imei
    *
    * @return HardwareInformation
    */
    public function setImei($val)
    {
        $this->_propDict["imei"] = $val;
        return $this;
    }
    /**
    * Gets the ipAddressV4
    * IPAddressV4
    *
    * @return string|null The ipAddressV4
    */
    public function getIpAddressV4()
    {
        if (array_key_exists("ipAddressV4", $this->_propDict)) {
            return $this->_propDict["ipAddressV4"];
        } else {
            return null;
        }
    }

    /**
    * Sets the ipAddressV4
    * IPAddressV4
    *
    * @param string $val The value of the ipAddressV4
    *
    * @return HardwareInformation
    */
    public function setIpAddressV4($val)
    {
        $this->_propDict["ipAddressV4"] = $val;
        return $this;
    }
    /**
    * Gets the isEncrypted
    * Encryption status of the device
    *
    * @return bool|null The isEncrypted
    */
    public function getIsEncrypted()
    {
        if (array_key_exists("isEncrypted", $this->_propDict)) {
            return $this->_propDict["isEncrypted"];
        } else {
            return null;
        }
    }

    /**
    * Sets the isEncrypted
    * Encryption status of the device
    *
    * @param bool $val The value of the isEncrypted
    *
    * @return HardwareInformation
    */
    public function setIsEncrypted($val)
    {
        $this->_propDict["isEncrypted"] = $val;
        return $this;
    }
    /**
    * Gets the isSharedDevice
    * Shared iPad
    *
    * @return bool|null The isSharedDevice
    */
    public function getIsSharedDevice()
    {
        if (array_key_exists("isSharedDevice", $this->_propDict)) {
            return $this->_propDict["isSharedDevice"];
        } else {
            return null;
        }
    }

    /**
    * Sets the isSharedDevice
    * Shared iPad
    *
    * @param bool $val The value of the isSharedDevice
    *
    * @return HardwareInformation
    */
    public function setIsSharedDevice($val)
    {
        $this->_propDict["isSharedDevice"] = $val;
        return $this;
    }
    /**
    * Gets the isSupervised
    * Supervised mode of the device
    *
    * @return bool|null The isSupervised
    */
    public function getIsSupervised()
    {
        if (array_key_exists("isSupervised", $this->_propDict)) {
            return $this->_propDict["isSupervised"];
        } else {
            return null;
        }
    }

    /**
    * Sets the isSupervised
    * Supervised mode of the device
    *
    * @param bool $val The value of the isSupervised
    *
    * @return HardwareInformation
    */
    public function setIsSupervised($val)
    {
        $this->_propDict["isSupervised"] = $val;
        return $this;
    }
    /**
    * Gets the manufacturer
    * Manufacturer of the device
    *
    * @return string|null The manufacturer
    */
    public function getManufacturer()
    {
        if (array_key_exists("manufacturer", $this->_propDict)) {
            return $this->_propDict["manufacturer"];
        } else {
            return null;
        }
    }

    /**
    * Sets the manufacturer
    * Manufacturer of the device
    *
    * @param string $val The value of the manufacturer
    *
    * @return HardwareInformation
    */
    public function setManufacturer($val)
    {
        $this->_propDict["manufacturer"] = $val;
        return $this;
    }
    /**
    * Gets the meid
    * MEID
    *
    * @return string|null The meid
    */
    public function getMeid()
    {
        if (array_key_exists("meid", $this->_propDict)) {
            return $this->_propDict["meid"];
        } else {
            return null;
        }
    }

    /**
    * Sets the meid
    * MEID
    *
    * @param string $val The value of the meid
    *
    * @return HardwareInformation
    */
    public function setMeid($val)
    {
        $this->_propDict["meid"] = $val;
        return $this;
    }
    /**
    * Gets the model
    * Model of the device
    *
    * @return string|null The model
    */
    public function getModel()
    {
        if (array_key_exists("model", $this->_propDict)) {
            return $this->_propDict["model"];
        } else {
            return null;
        }
    }

    /**
    * Sets the model
    * Model of the device
    *
    * @param string $val The value of the model
    *
    * @return HardwareInformation
    */
    public function setModel($val)
    {
        $this->_propDict["model"] = $val;
        return $this;
    }
    /**
    * Gets the operatingSystemEdition
    * String that specifies the OS edition.
    *
    * @return string|null The operatingSystemEdition
    */
    public function getOperatingSystemEdition()
    {
        if (array_key_exists("operatingSystemEdition", $this->_propDict)) {
            return $this->_propDict["operatingSystemEdition"];
        } else {
            return null;
        }
    }

    /**
    * Sets the operatingSystemEdition
    * String that specifies the OS edition.
    *
    * @param string $val The value of the operatingSystemEdition
    *
    * @return HardwareInformation
    */
    public function setOperatingSystemEdition($val)
    {
        $this->_propDict["operatingSystemEdition"] = $val;
        return $this;
    }
    /**
    * Gets the operatingSystemLanguage
    * Operating system language of the device
    *
    * @return string|null The operatingSystemLanguage
    */
    public function getOperatingSystemLanguage()
    {
        if (array_key_exists("operatingSystemLanguage", $this->_propDict)) {
            return $this->_propDict["operatingSystemLanguage"];
        } else {
            return null;
        }
    }

    /**
    * Sets the operatingSystemLanguage
    * Operating system language of the device
    *
    * @param string $val The value of the operatingSystemLanguage
    *
    * @return HardwareInformation
    */
    public function setOperatingSystemLanguage($val)
    {
        $this->_propDict["operatingSystemLanguage"] = $val;
        return $this;
    }
    /**
    * Gets the operatingSystemProductType
    * Int that specifies the Windows Operating System ProductType. More details here https://go.microsoft.com/fwlink/?linkid=2126950. Valid values 0 to 2147483647
    *
    * @return int|null The operatingSystemProductType
    */
    public function getOperatingSystemProductType()
    {
        if (array_key_exists("operatingSystemProductType", $this->_propDict)) {
            return $this->_propDict["operatingSystemProductType"];
        } else {
            return null;
        }
    }

    /**
    * Sets the operatingSystemProductType
    * Int that specifies the Windows Operating System ProductType. More details here https://go.microsoft.com/fwlink/?linkid=2126950. Valid values 0 to 2147483647
    *
    * @param int $val The value of the operatingSystemProductType
    *
    * @return HardwareInformation
    */
    public function setOperatingSystemProductType($val)
    {
        $this->_propDict["operatingSystemProductType"] = $val;
        return $this;
    }
    /**
    * Gets the osBuildNumber
    * Operating System Build Number on Android device
    *
    * @return string|null The osBuildNumber
    */
    public function getOsBuildNumber()
    {
        if (array_key_exists("osBuildNumber", $this->_propDict)) {
            return $this->_propDict["osBuildNumber"];
        } else {
            return null;
        }
    }

    /**
    * Sets the osBuildNumber
    * Operating System Build Number on Android device
    *
    * @param string $val The value of the osBuildNumber
    *
    * @return HardwareInformation
    */
    public function setOsBuildNumber($val)
    {
        $this->_propDict["osBuildNumber"] = $val;
        return $this;
    }
    /**
    * Gets the phoneNumber
    * Phone number of the device
    *
    * @return string|null The phoneNumber
    */
    public function getPhoneNumber()
    {
        if (array_key_exists("phoneNumber", $this->_propDict)) {
            return $this->_propDict["phoneNumber"];
        } else {
            return null;
        }
    }

    /**
    * Sets the phoneNumber
    * Phone number of the device
    *
    * @param string $val The value of the phoneNumber
    *
    * @return HardwareInformation
    */
    public function setPhoneNumber($val)
    {
        $this->_propDict["phoneNumber"] = $val;
        return $this;
    }
    /**
    * Gets the productName
    * The product name, e.g. iPad8,12 etc. The update frequency of this property is weekly. Note this property is currently supported only on iOS/MacOS devices, and is available only when Device Information access right is obtained.
    *
    * @return string|null The productName
    */
    public function getProductName()
    {
        if (array_key_exists("productName", $this->_propDict)) {
            return $this->_propDict["productName"];
        } else {
            return null;
        }
    }

    /**
    * Sets the productName
    * The product name, e.g. iPad8,12 etc. The update frequency of this property is weekly. Note this property is currently supported only on iOS/MacOS devices, and is available only when Device Information access right is obtained.
    *
    * @param string $val The value of the productName
    *
    * @return HardwareInformation
    */
    public function setProductName($val)
    {
        $this->_propDict["productName"] = $val;
        return $this;
    }
    /**
    * Gets the residentUsersCount
    * The number of users currently on this device, or null (default) if the value of this property cannot be determined. The update frequency of this property is per-checkin. Note this property is currently supported only on devices running iOS 13.4 and later, and is available only when Device Information access right is obtained. Valid values 0 to 2147483647
    *
    * @return int|null The residentUsersCount
    */
    public function getResidentUsersCount()
    {
        if (array_key_exists("residentUsersCount", $this->_propDict)) {
            return $this->_propDict["residentUsersCount"];
        } else {
            return null;
        }
    }

    /**
    * Sets the residentUsersCount
    * The number of users currently on this device, or null (default) if the value of this property cannot be determined. The update frequency of this property is per-checkin. Note this property is currently supported only on devices running iOS 13.4 and later, and is available only when Device Information access right is obtained. Valid values 0 to 2147483647
    *
    * @param int $val The value of the residentUsersCount
    *
    * @return HardwareInformation
    */
    public function setResidentUsersCount($val)
    {
        $this->_propDict["residentUsersCount"] = $val;
        return $this;
    }
    /**
    * Gets the serialNumber
    * Serial number.
    *
    * @return string|null The serialNumber
    */
    public function getSerialNumber()
    {
        if (array_key_exists("serialNumber", $this->_propDict)) {
            return $this->_propDict["serialNumber"];
        } else {
            return null;
        }
    }

    /**
    * Sets the serialNumber
    * Serial number.
    *
    * @param string $val The value of the serialNumber
    *
    * @return HardwareInformation
    */
    public function setSerialNumber($val)
    {
        $this->_propDict["serialNumber"] = $val;
        return $this;
    }

    /**
    * Gets the sharedDeviceCachedUsers
    * All users on the shared Apple device
    *
    * @return SharedAppleDeviceUser|null The sharedDeviceCachedUsers
    */
    public function getSharedDeviceCachedUsers()
    {
        if (array_key_exists("sharedDeviceCachedUsers", $this->_propDict)) {
            if (is_a($this->_propDict["sharedDeviceCachedUsers"], "\Beta\Microsoft\Graph\Model\SharedAppleDeviceUser") || is_null($this->_propDict["sharedDeviceCachedUsers"])) {
                return $this->_propDict["sharedDeviceCachedUsers"];
            } else {
                $this->_propDict["sharedDeviceCachedUsers"] = new SharedAppleDeviceUser($this->_propDict["sharedDeviceCachedUsers"]);
                return $this->_propDict["sharedDeviceCachedUsers"];
            }
        }
        return null;
    }

    /**
    * Sets the sharedDeviceCachedUsers
    * All users on the shared Apple device
    *
    * @param SharedAppleDeviceUser $val The value to assign to the sharedDeviceCachedUsers
    *
    * @return HardwareInformation The HardwareInformation
    */
    public function setSharedDeviceCachedUsers($val)
    {
        $this->_propDict["sharedDeviceCachedUsers"] = $val;
         return $this;
    }
    /**
    * Gets the subnetAddress
    * SubnetAddress
    *
    * @return string|null The subnetAddress
    */
    public function getSubnetAddress()
    {
        if (array_key_exists("subnetAddress", $this->_propDict)) {
            return $this->_propDict["subnetAddress"];
        } else {
            return null;
        }
    }

    /**
    * Sets the subnetAddress
    * SubnetAddress
    *
    * @param string $val The value of the subnetAddress
    *
    * @return HardwareInformation
    */
    public function setSubnetAddress($val)
    {
        $this->_propDict["subnetAddress"] = $val;
        return $this;
    }
    /**
    * Gets the subscriberCarrier
    * Subscriber carrier of the device
    *
    * @return string|null The subscriberCarrier
    */
    public function getSubscriberCarrier()
    {
        if (array_key_exists("subscriberCarrier", $this->_propDict)) {
            return $this->_propDict["subscriberCarrier"];
        } else {
            return null;
        }
    }

    /**
    * Sets the subscriberCarrier
    * Subscriber carrier of the device
    *
    * @param string $val The value of the subscriberCarrier
    *
    * @return HardwareInformation
    */
    public function setSubscriberCarrier($val)
    {
        $this->_propDict["subscriberCarrier"] = $val;
        return $this;
    }
    /**
    * Gets the systemManagementBIOSVersion
    * BIOS version as reported by SMBIOS
    *
    * @return string|null The systemManagementBIOSVersion
    */
    public function getSystemManagementBIOSVersion()
    {
        if (array_key_exists("systemManagementBIOSVersion", $this->_propDict)) {
            return $this->_propDict["systemManagementBIOSVersion"];
        } else {
            return null;
        }
    }

    /**
    * Sets the systemManagementBIOSVersion
    * BIOS version as reported by SMBIOS
    *
    * @param string $val The value of the systemManagementBIOSVersion
    *
    * @return HardwareInformation
    */
    public function setSystemManagementBIOSVersion($val)
    {
        $this->_propDict["systemManagementBIOSVersion"] = $val;
        return $this;
    }
    /**
    * Gets the totalStorageSpace
    * Total storage space of the device.
    *
    * @return int|null The totalStorageSpace
    */
    public function getTotalStorageSpace()
    {
        if (array_key_exists("totalStorageSpace", $this->_propDict)) {
            return $this->_propDict["totalStorageSpace"];
        } else {
            return null;
        }
    }

    /**
    * Sets the totalStorageSpace
    * Total storage space of the device.
    *
    * @param int $val The value of the totalStorageSpace
    *
    * @return HardwareInformation
    */
    public function setTotalStorageSpace($val)
    {
        $this->_propDict["totalStorageSpace"] = $val;
        return $this;
    }
    /**
    * Gets the tpmManufacturer
    * The identifying information that uniquely names the TPM manufacturer
    *
    * @return string|null The tpmManufacturer
    */
    public function getTpmManufacturer()
    {
        if (array_key_exists("tpmManufacturer", $this->_propDict)) {
            return $this->_propDict["tpmManufacturer"];
        } else {
            return null;
        }
    }

    /**
    * Sets the tpmManufacturer
    * The identifying information that uniquely names the TPM manufacturer
    *
    * @param string $val The value of the tpmManufacturer
    *
    * @return HardwareInformation
    */
    public function setTpmManufacturer($val)
    {
        $this->_propDict["tpmManufacturer"] = $val;
        return $this;
    }
    /**
    * Gets the tpmSpecificationVersion
    * String that specifies the specification version.
    *
    * @return string|null The tpmSpecificationVersion
    */
    public function getTpmSpecificationVersion()
    {
        if (array_key_exists("tpmSpecificationVersion", $this->_propDict)) {
            return $this->_propDict["tpmSpecificationVersion"];
        } else {
            return null;
        }
    }

    /**
    * Sets the tpmSpecificationVersion
    * String that specifies the specification version.
    *
    * @param string $val The value of the tpmSpecificationVersion
    *
    * @return HardwareInformation
    */
    public function setTpmSpecificationVersion($val)
    {
        $this->_propDict["tpmSpecificationVersion"] = $val;
        return $this;
    }
    /**
    * Gets the tpmVersion
    * The version of the TPM, as specified by the manufacturer
    *
    * @return string|null The tpmVersion
    */
    public function getTpmVersion()
    {
        if (array_key_exists("tpmVersion", $this->_propDict)) {
            return $this->_propDict["tpmVersion"];
        } else {
            return null;
        }
    }

    /**
    * Sets the tpmVersion
    * The version of the TPM, as specified by the manufacturer
    *
    * @param string $val The value of the tpmVersion
    *
    * @return HardwareInformation
    */
    public function setTpmVersion($val)
    {
        $this->_propDict["tpmVersion"] = $val;
        return $this;
    }
    /**
    * Gets the wifiMac
    * WiFi MAC address of the device
    *
    * @return string|null The wifiMac
    */
    public function getWifiMac()
    {
        if (array_key_exists("wifiMac", $this->_propDict)) {
            return $this->_propDict["wifiMac"];
        } else {
            return null;
        }
    }

    /**
    * Sets the wifiMac
    * WiFi MAC address of the device
    *
    * @param string $val The value of the wifiMac
    *
    * @return HardwareInformation
    */
    public function setWifiMac($val)
    {
        $this->_propDict["wifiMac"] = $val;
        return $this;
    }
    /**
    * Gets the wiredIPv4Addresses
    * A list of wired IPv4 addresses. The update frequency (the maximum delay for the change of property value to be synchronized from the device to the cloud storage) of this property is daily. Note this property is currently supported only on devices running on Windows.
    *
    * @return string|null The wiredIPv4Addresses
    */
    public function getWiredIPv4Addresses()
    {
        if (array_key_exists("wiredIPv4Addresses", $this->_propDict)) {
            return $this->_propDict["wiredIPv4Addresses"];
        } else {
            return null;
        }
    }

    /**
    * Sets the wiredIPv4Addresses
    * A list of wired IPv4 addresses. The update frequency (the maximum delay for the change of property value to be synchronized from the device to the cloud storage) of this property is daily. Note this property is currently supported only on devices running on Windows.
    *
    * @param string $val The value of the wiredIPv4Addresses
    *
    * @return HardwareInformation
    */
    public function setWiredIPv4Addresses($val)
    {
        $this->_propDict["wiredIPv4Addresses"] = $val;
        return $this;
    }
}
