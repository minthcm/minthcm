<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* DeviceHealthAttestationState File
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
* DeviceHealthAttestationState class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class DeviceHealthAttestationState extends Entity
{
    /**
    * Gets the attestationIdentityKey
    * TWhen an Attestation Identity Key (AIK) is present on a device, it indicates that the device has an endorsement key (EK) certificate.
    *
    * @return string|null The attestationIdentityKey
    */
    public function getAttestationIdentityKey()
    {
        if (array_key_exists("attestationIdentityKey", $this->_propDict)) {
            return $this->_propDict["attestationIdentityKey"];
        } else {
            return null;
        }
    }

    /**
    * Sets the attestationIdentityKey
    * TWhen an Attestation Identity Key (AIK) is present on a device, it indicates that the device has an endorsement key (EK) certificate.
    *
    * @param string $val The value of the attestationIdentityKey
    *
    * @return DeviceHealthAttestationState
    */
    public function setAttestationIdentityKey($val)
    {
        $this->_propDict["attestationIdentityKey"] = $val;
        return $this;
    }
    /**
    * Gets the bitLockerStatus
    * On or Off of BitLocker Drive Encryption
    *
    * @return string|null The bitLockerStatus
    */
    public function getBitLockerStatus()
    {
        if (array_key_exists("bitLockerStatus", $this->_propDict)) {
            return $this->_propDict["bitLockerStatus"];
        } else {
            return null;
        }
    }

    /**
    * Sets the bitLockerStatus
    * On or Off of BitLocker Drive Encryption
    *
    * @param string $val The value of the bitLockerStatus
    *
    * @return DeviceHealthAttestationState
    */
    public function setBitLockerStatus($val)
    {
        $this->_propDict["bitLockerStatus"] = $val;
        return $this;
    }
    /**
    * Gets the bootAppSecurityVersion
    * The security version number of the Boot Application
    *
    * @return string|null The bootAppSecurityVersion
    */
    public function getBootAppSecurityVersion()
    {
        if (array_key_exists("bootAppSecurityVersion", $this->_propDict)) {
            return $this->_propDict["bootAppSecurityVersion"];
        } else {
            return null;
        }
    }

    /**
    * Sets the bootAppSecurityVersion
    * The security version number of the Boot Application
    *
    * @param string $val The value of the bootAppSecurityVersion
    *
    * @return DeviceHealthAttestationState
    */
    public function setBootAppSecurityVersion($val)
    {
        $this->_propDict["bootAppSecurityVersion"] = $val;
        return $this;
    }
    /**
    * Gets the bootDebugging
    * When bootDebugging is enabled, the device is used in development and testing
    *
    * @return string|null The bootDebugging
    */
    public function getBootDebugging()
    {
        if (array_key_exists("bootDebugging", $this->_propDict)) {
            return $this->_propDict["bootDebugging"];
        } else {
            return null;
        }
    }

    /**
    * Sets the bootDebugging
    * When bootDebugging is enabled, the device is used in development and testing
    *
    * @param string $val The value of the bootDebugging
    *
    * @return DeviceHealthAttestationState
    */
    public function setBootDebugging($val)
    {
        $this->_propDict["bootDebugging"] = $val;
        return $this;
    }
    /**
    * Gets the bootManagerSecurityVersion
    * The security version number of the Boot Application
    *
    * @return string|null The bootManagerSecurityVersion
    */
    public function getBootManagerSecurityVersion()
    {
        if (array_key_exists("bootManagerSecurityVersion", $this->_propDict)) {
            return $this->_propDict["bootManagerSecurityVersion"];
        } else {
            return null;
        }
    }

    /**
    * Sets the bootManagerSecurityVersion
    * The security version number of the Boot Application
    *
    * @param string $val The value of the bootManagerSecurityVersion
    *
    * @return DeviceHealthAttestationState
    */
    public function setBootManagerSecurityVersion($val)
    {
        $this->_propDict["bootManagerSecurityVersion"] = $val;
        return $this;
    }
    /**
    * Gets the bootManagerVersion
    * The version of the Boot Manager
    *
    * @return string|null The bootManagerVersion
    */
    public function getBootManagerVersion()
    {
        if (array_key_exists("bootManagerVersion", $this->_propDict)) {
            return $this->_propDict["bootManagerVersion"];
        } else {
            return null;
        }
    }

    /**
    * Sets the bootManagerVersion
    * The version of the Boot Manager
    *
    * @param string $val The value of the bootManagerVersion
    *
    * @return DeviceHealthAttestationState
    */
    public function setBootManagerVersion($val)
    {
        $this->_propDict["bootManagerVersion"] = $val;
        return $this;
    }
    /**
    * Gets the bootRevisionListInfo
    * The Boot Revision List that was loaded during initial boot on the attested device
    *
    * @return string|null The bootRevisionListInfo
    */
    public function getBootRevisionListInfo()
    {
        if (array_key_exists("bootRevisionListInfo", $this->_propDict)) {
            return $this->_propDict["bootRevisionListInfo"];
        } else {
            return null;
        }
    }

    /**
    * Sets the bootRevisionListInfo
    * The Boot Revision List that was loaded during initial boot on the attested device
    *
    * @param string $val The value of the bootRevisionListInfo
    *
    * @return DeviceHealthAttestationState
    */
    public function setBootRevisionListInfo($val)
    {
        $this->_propDict["bootRevisionListInfo"] = $val;
        return $this;
    }
    /**
    * Gets the codeIntegrity
    * When code integrity is enabled, code execution is restricted to integrity verified code
    *
    * @return string|null The codeIntegrity
    */
    public function getCodeIntegrity()
    {
        if (array_key_exists("codeIntegrity", $this->_propDict)) {
            return $this->_propDict["codeIntegrity"];
        } else {
            return null;
        }
    }

    /**
    * Sets the codeIntegrity
    * When code integrity is enabled, code execution is restricted to integrity verified code
    *
    * @param string $val The value of the codeIntegrity
    *
    * @return DeviceHealthAttestationState
    */
    public function setCodeIntegrity($val)
    {
        $this->_propDict["codeIntegrity"] = $val;
        return $this;
    }
    /**
    * Gets the codeIntegrityCheckVersion
    * The version of the Boot Manager
    *
    * @return string|null The codeIntegrityCheckVersion
    */
    public function getCodeIntegrityCheckVersion()
    {
        if (array_key_exists("codeIntegrityCheckVersion", $this->_propDict)) {
            return $this->_propDict["codeIntegrityCheckVersion"];
        } else {
            return null;
        }
    }

    /**
    * Sets the codeIntegrityCheckVersion
    * The version of the Boot Manager
    *
    * @param string $val The value of the codeIntegrityCheckVersion
    *
    * @return DeviceHealthAttestationState
    */
    public function setCodeIntegrityCheckVersion($val)
    {
        $this->_propDict["codeIntegrityCheckVersion"] = $val;
        return $this;
    }
    /**
    * Gets the codeIntegrityPolicy
    * The Code Integrity policy that is controlling the security of the boot environment
    *
    * @return string|null The codeIntegrityPolicy
    */
    public function getCodeIntegrityPolicy()
    {
        if (array_key_exists("codeIntegrityPolicy", $this->_propDict)) {
            return $this->_propDict["codeIntegrityPolicy"];
        } else {
            return null;
        }
    }

    /**
    * Sets the codeIntegrityPolicy
    * The Code Integrity policy that is controlling the security of the boot environment
    *
    * @param string $val The value of the codeIntegrityPolicy
    *
    * @return DeviceHealthAttestationState
    */
    public function setCodeIntegrityPolicy($val)
    {
        $this->_propDict["codeIntegrityPolicy"] = $val;
        return $this;
    }
    /**
    * Gets the contentNamespaceUrl
    * The DHA report version. (Namespace version)
    *
    * @return string|null The contentNamespaceUrl
    */
    public function getContentNamespaceUrl()
    {
        if (array_key_exists("contentNamespaceUrl", $this->_propDict)) {
            return $this->_propDict["contentNamespaceUrl"];
        } else {
            return null;
        }
    }

    /**
    * Sets the contentNamespaceUrl
    * The DHA report version. (Namespace version)
    *
    * @param string $val The value of the contentNamespaceUrl
    *
    * @return DeviceHealthAttestationState
    */
    public function setContentNamespaceUrl($val)
    {
        $this->_propDict["contentNamespaceUrl"] = $val;
        return $this;
    }
    /**
    * Gets the contentVersion
    * The HealthAttestation state schema version
    *
    * @return string|null The contentVersion
    */
    public function getContentVersion()
    {
        if (array_key_exists("contentVersion", $this->_propDict)) {
            return $this->_propDict["contentVersion"];
        } else {
            return null;
        }
    }

    /**
    * Sets the contentVersion
    * The HealthAttestation state schema version
    *
    * @param string $val The value of the contentVersion
    *
    * @return DeviceHealthAttestationState
    */
    public function setContentVersion($val)
    {
        $this->_propDict["contentVersion"] = $val;
        return $this;
    }
    /**
    * Gets the dataExcutionPolicy
    * DEP Policy defines a set of hardware and software technologies that perform additional checks on memory
    *
    * @return string|null The dataExcutionPolicy
    */
    public function getDataExcutionPolicy()
    {
        if (array_key_exists("dataExcutionPolicy", $this->_propDict)) {
            return $this->_propDict["dataExcutionPolicy"];
        } else {
            return null;
        }
    }

    /**
    * Sets the dataExcutionPolicy
    * DEP Policy defines a set of hardware and software technologies that perform additional checks on memory
    *
    * @param string $val The value of the dataExcutionPolicy
    *
    * @return DeviceHealthAttestationState
    */
    public function setDataExcutionPolicy($val)
    {
        $this->_propDict["dataExcutionPolicy"] = $val;
        return $this;
    }
    /**
    * Gets the deviceHealthAttestationStatus
    * The DHA report version. (Namespace version)
    *
    * @return string|null The deviceHealthAttestationStatus
    */
    public function getDeviceHealthAttestationStatus()
    {
        if (array_key_exists("deviceHealthAttestationStatus", $this->_propDict)) {
            return $this->_propDict["deviceHealthAttestationStatus"];
        } else {
            return null;
        }
    }

    /**
    * Sets the deviceHealthAttestationStatus
    * The DHA report version. (Namespace version)
    *
    * @param string $val The value of the deviceHealthAttestationStatus
    *
    * @return DeviceHealthAttestationState
    */
    public function setDeviceHealthAttestationStatus($val)
    {
        $this->_propDict["deviceHealthAttestationStatus"] = $val;
        return $this;
    }
    /**
    * Gets the earlyLaunchAntiMalwareDriverProtection
    * ELAM provides protection for the computers in your network when they start up
    *
    * @return string|null The earlyLaunchAntiMalwareDriverProtection
    */
    public function getEarlyLaunchAntiMalwareDriverProtection()
    {
        if (array_key_exists("earlyLaunchAntiMalwareDriverProtection", $this->_propDict)) {
            return $this->_propDict["earlyLaunchAntiMalwareDriverProtection"];
        } else {
            return null;
        }
    }

    /**
    * Sets the earlyLaunchAntiMalwareDriverProtection
    * ELAM provides protection for the computers in your network when they start up
    *
    * @param string $val The value of the earlyLaunchAntiMalwareDriverProtection
    *
    * @return DeviceHealthAttestationState
    */
    public function setEarlyLaunchAntiMalwareDriverProtection($val)
    {
        $this->_propDict["earlyLaunchAntiMalwareDriverProtection"] = $val;
        return $this;
    }

    /**
    * Gets the firmwareProtection
    * Indicates whether the device has Firmware protection enabled. Firmware protection is a set of features that helps to ensure attackers can't get your device to start with untrusted or malicious firmware. Possible values are "systemGuardSecureLaunch", "firmwareAttackSurfaceReduction", "disabled" and "notApplicable". "systemGuardSecureLaunch" indicates System Guard Secure Launch is enabled for Firmware protection. "firmwareAttackSurfaceReduction" indicates Firmware Attack Surface Reduction is enabled for Firmware protection. "disabled" indicates Firmware protection is disabled. "notApplicable" indicates the device is not a Windows 11 device. Default value is "notApplicable".
    *
    * @return FirmwareProtectionType|null The firmwareProtection
    */
    public function getFirmwareProtection()
    {
        if (array_key_exists("firmwareProtection", $this->_propDict)) {
            if (is_a($this->_propDict["firmwareProtection"], "\Beta\Microsoft\Graph\Model\FirmwareProtectionType") || is_null($this->_propDict["firmwareProtection"])) {
                return $this->_propDict["firmwareProtection"];
            } else {
                $this->_propDict["firmwareProtection"] = new FirmwareProtectionType($this->_propDict["firmwareProtection"]);
                return $this->_propDict["firmwareProtection"];
            }
        }
        return null;
    }

    /**
    * Sets the firmwareProtection
    * Indicates whether the device has Firmware protection enabled. Firmware protection is a set of features that helps to ensure attackers can't get your device to start with untrusted or malicious firmware. Possible values are "systemGuardSecureLaunch", "firmwareAttackSurfaceReduction", "disabled" and "notApplicable". "systemGuardSecureLaunch" indicates System Guard Secure Launch is enabled for Firmware protection. "firmwareAttackSurfaceReduction" indicates Firmware Attack Surface Reduction is enabled for Firmware protection. "disabled" indicates Firmware protection is disabled. "notApplicable" indicates the device is not a Windows 11 device. Default value is "notApplicable".
    *
    * @param FirmwareProtectionType $val The value to assign to the firmwareProtection
    *
    * @return DeviceHealthAttestationState The DeviceHealthAttestationState
    */
    public function setFirmwareProtection($val)
    {
        $this->_propDict["firmwareProtection"] = $val;
         return $this;
    }
    /**
    * Gets the healthAttestationSupportedStatus
    * This attribute indicates if DHA is supported for the device
    *
    * @return string|null The healthAttestationSupportedStatus
    */
    public function getHealthAttestationSupportedStatus()
    {
        if (array_key_exists("healthAttestationSupportedStatus", $this->_propDict)) {
            return $this->_propDict["healthAttestationSupportedStatus"];
        } else {
            return null;
        }
    }

    /**
    * Sets the healthAttestationSupportedStatus
    * This attribute indicates if DHA is supported for the device
    *
    * @param string $val The value of the healthAttestationSupportedStatus
    *
    * @return DeviceHealthAttestationState
    */
    public function setHealthAttestationSupportedStatus($val)
    {
        $this->_propDict["healthAttestationSupportedStatus"] = $val;
        return $this;
    }
    /**
    * Gets the healthStatusMismatchInfo
    * This attribute appears if DHA-Service detects an integrity issue
    *
    * @return string|null The healthStatusMismatchInfo
    */
    public function getHealthStatusMismatchInfo()
    {
        if (array_key_exists("healthStatusMismatchInfo", $this->_propDict)) {
            return $this->_propDict["healthStatusMismatchInfo"];
        } else {
            return null;
        }
    }

    /**
    * Sets the healthStatusMismatchInfo
    * This attribute appears if DHA-Service detects an integrity issue
    *
    * @param string $val The value of the healthStatusMismatchInfo
    *
    * @return DeviceHealthAttestationState
    */
    public function setHealthStatusMismatchInfo($val)
    {
        $this->_propDict["healthStatusMismatchInfo"] = $val;
        return $this;
    }

    /**
    * Gets the issuedDateTime
    * The DateTime when device was evaluated or issued to MDM
    *
    * @return \DateTime|null The issuedDateTime
    */
    public function getIssuedDateTime()
    {
        if (array_key_exists("issuedDateTime", $this->_propDict)) {
            if (is_a($this->_propDict["issuedDateTime"], "\DateTime") || is_null($this->_propDict["issuedDateTime"])) {
                return $this->_propDict["issuedDateTime"];
            } else {
                $this->_propDict["issuedDateTime"] = new \DateTime($this->_propDict["issuedDateTime"]);
                return $this->_propDict["issuedDateTime"];
            }
        }
        return null;
    }

    /**
    * Sets the issuedDateTime
    * The DateTime when device was evaluated or issued to MDM
    *
    * @param \DateTime $val The value to assign to the issuedDateTime
    *
    * @return DeviceHealthAttestationState The DeviceHealthAttestationState
    */
    public function setIssuedDateTime($val)
    {
        $this->_propDict["issuedDateTime"] = $val;
         return $this;
    }
    /**
    * Gets the lastUpdateDateTime
    * The Timestamp of the last update.
    *
    * @return string|null The lastUpdateDateTime
    */
    public function getLastUpdateDateTime()
    {
        if (array_key_exists("lastUpdateDateTime", $this->_propDict)) {
            return $this->_propDict["lastUpdateDateTime"];
        } else {
            return null;
        }
    }

    /**
    * Sets the lastUpdateDateTime
    * The Timestamp of the last update.
    *
    * @param string $val The value of the lastUpdateDateTime
    *
    * @return DeviceHealthAttestationState
    */
    public function setLastUpdateDateTime($val)
    {
        $this->_propDict["lastUpdateDateTime"] = $val;
        return $this;
    }

    /**
    * Gets the memoryAccessProtection
    * Indicates whether the device has Memory access protection enabled. A Windows security feature that protects against external peripherals from gaining unauthorized access to memory. Possible values are "enabled", "disabled" and "notApplicable". "enabled" indicates Memory access protection is enabled. "disabled" indicates Memory access protection is disabled. "notApplicable" indicates the device is not a Windows 11 device. Default value is "notApplicable".
    *
    * @return AzureAttestationSettingStatus|null The memoryAccessProtection
    */
    public function getMemoryAccessProtection()
    {
        if (array_key_exists("memoryAccessProtection", $this->_propDict)) {
            if (is_a($this->_propDict["memoryAccessProtection"], "\Beta\Microsoft\Graph\Model\AzureAttestationSettingStatus") || is_null($this->_propDict["memoryAccessProtection"])) {
                return $this->_propDict["memoryAccessProtection"];
            } else {
                $this->_propDict["memoryAccessProtection"] = new AzureAttestationSettingStatus($this->_propDict["memoryAccessProtection"]);
                return $this->_propDict["memoryAccessProtection"];
            }
        }
        return null;
    }

    /**
    * Sets the memoryAccessProtection
    * Indicates whether the device has Memory access protection enabled. A Windows security feature that protects against external peripherals from gaining unauthorized access to memory. Possible values are "enabled", "disabled" and "notApplicable". "enabled" indicates Memory access protection is enabled. "disabled" indicates Memory access protection is disabled. "notApplicable" indicates the device is not a Windows 11 device. Default value is "notApplicable".
    *
    * @param AzureAttestationSettingStatus $val The value to assign to the memoryAccessProtection
    *
    * @return DeviceHealthAttestationState The DeviceHealthAttestationState
    */
    public function setMemoryAccessProtection($val)
    {
        $this->_propDict["memoryAccessProtection"] = $val;
         return $this;
    }

    /**
    * Gets the memoryIntegrityProtection
    * Indicates whether the device has Memory Integrity protection enabled. Memory Integrity is a feature of Virtualization-based security, also known as Hypervisor-protected code integrity (HVCI). It improves the threat mode of Windows and provides stronger protections against malware trying to exploit the Windows kernel. Possible values are "enabled", "disabled" and "notApplicable". "enabled" indicates Memory Integrity protection is enabled. "disabled" indicates Memory Integrity protection is disabled. "notApplicable" indicates the device is not a Windows 11 device. Default value is "notApplicable".
    *
    * @return AzureAttestationSettingStatus|null The memoryIntegrityProtection
    */
    public function getMemoryIntegrityProtection()
    {
        if (array_key_exists("memoryIntegrityProtection", $this->_propDict)) {
            if (is_a($this->_propDict["memoryIntegrityProtection"], "\Beta\Microsoft\Graph\Model\AzureAttestationSettingStatus") || is_null($this->_propDict["memoryIntegrityProtection"])) {
                return $this->_propDict["memoryIntegrityProtection"];
            } else {
                $this->_propDict["memoryIntegrityProtection"] = new AzureAttestationSettingStatus($this->_propDict["memoryIntegrityProtection"]);
                return $this->_propDict["memoryIntegrityProtection"];
            }
        }
        return null;
    }

    /**
    * Sets the memoryIntegrityProtection
    * Indicates whether the device has Memory Integrity protection enabled. Memory Integrity is a feature of Virtualization-based security, also known as Hypervisor-protected code integrity (HVCI). It improves the threat mode of Windows and provides stronger protections against malware trying to exploit the Windows kernel. Possible values are "enabled", "disabled" and "notApplicable". "enabled" indicates Memory Integrity protection is enabled. "disabled" indicates Memory Integrity protection is disabled. "notApplicable" indicates the device is not a Windows 11 device. Default value is "notApplicable".
    *
    * @param AzureAttestationSettingStatus $val The value to assign to the memoryIntegrityProtection
    *
    * @return DeviceHealthAttestationState The DeviceHealthAttestationState
    */
    public function setMemoryIntegrityProtection($val)
    {
        $this->_propDict["memoryIntegrityProtection"] = $val;
         return $this;
    }
    /**
    * Gets the operatingSystemKernelDebugging
    * When operatingSystemKernelDebugging is enabled, the device is used in development and testing
    *
    * @return string|null The operatingSystemKernelDebugging
    */
    public function getOperatingSystemKernelDebugging()
    {
        if (array_key_exists("operatingSystemKernelDebugging", $this->_propDict)) {
            return $this->_propDict["operatingSystemKernelDebugging"];
        } else {
            return null;
        }
    }

    /**
    * Sets the operatingSystemKernelDebugging
    * When operatingSystemKernelDebugging is enabled, the device is used in development and testing
    *
    * @param string $val The value of the operatingSystemKernelDebugging
    *
    * @return DeviceHealthAttestationState
    */
    public function setOperatingSystemKernelDebugging($val)
    {
        $this->_propDict["operatingSystemKernelDebugging"] = $val;
        return $this;
    }
    /**
    * Gets the operatingSystemRevListInfo
    * The Operating System Revision List that was loaded during initial boot on the attested device
    *
    * @return string|null The operatingSystemRevListInfo
    */
    public function getOperatingSystemRevListInfo()
    {
        if (array_key_exists("operatingSystemRevListInfo", $this->_propDict)) {
            return $this->_propDict["operatingSystemRevListInfo"];
        } else {
            return null;
        }
    }

    /**
    * Sets the operatingSystemRevListInfo
    * The Operating System Revision List that was loaded during initial boot on the attested device
    *
    * @param string $val The value of the operatingSystemRevListInfo
    *
    * @return DeviceHealthAttestationState
    */
    public function setOperatingSystemRevListInfo($val)
    {
        $this->_propDict["operatingSystemRevListInfo"] = $val;
        return $this;
    }
    /**
    * Gets the pcr0
    * The measurement that is captured in PCR[0]
    *
    * @return string|null The pcr0
    */
    public function getPcr0()
    {
        if (array_key_exists("pcr0", $this->_propDict)) {
            return $this->_propDict["pcr0"];
        } else {
            return null;
        }
    }

    /**
    * Sets the pcr0
    * The measurement that is captured in PCR[0]
    *
    * @param string $val The value of the pcr0
    *
    * @return DeviceHealthAttestationState
    */
    public function setPcr0($val)
    {
        $this->_propDict["pcr0"] = $val;
        return $this;
    }
    /**
    * Gets the pcrHashAlgorithm
    * Informational attribute that identifies the HASH algorithm that was used by TPM
    *
    * @return string|null The pcrHashAlgorithm
    */
    public function getPcrHashAlgorithm()
    {
        if (array_key_exists("pcrHashAlgorithm", $this->_propDict)) {
            return $this->_propDict["pcrHashAlgorithm"];
        } else {
            return null;
        }
    }

    /**
    * Sets the pcrHashAlgorithm
    * Informational attribute that identifies the HASH algorithm that was used by TPM
    *
    * @param string $val The value of the pcrHashAlgorithm
    *
    * @return DeviceHealthAttestationState
    */
    public function setPcrHashAlgorithm($val)
    {
        $this->_propDict["pcrHashAlgorithm"] = $val;
        return $this;
    }
    /**
    * Gets the resetCount
    * The number of times a PC device has hibernated or resumed
    *
    * @return int|null The resetCount
    */
    public function getResetCount()
    {
        if (array_key_exists("resetCount", $this->_propDict)) {
            return $this->_propDict["resetCount"];
        } else {
            return null;
        }
    }

    /**
    * Sets the resetCount
    * The number of times a PC device has hibernated or resumed
    *
    * @param int $val The value of the resetCount
    *
    * @return DeviceHealthAttestationState
    */
    public function setResetCount($val)
    {
        $this->_propDict["resetCount"] = $val;
        return $this;
    }
    /**
    * Gets the restartCount
    * The number of times a PC device has rebooted
    *
    * @return int|null The restartCount
    */
    public function getRestartCount()
    {
        if (array_key_exists("restartCount", $this->_propDict)) {
            return $this->_propDict["restartCount"];
        } else {
            return null;
        }
    }

    /**
    * Sets the restartCount
    * The number of times a PC device has rebooted
    *
    * @param int $val The value of the restartCount
    *
    * @return DeviceHealthAttestationState
    */
    public function setRestartCount($val)
    {
        $this->_propDict["restartCount"] = $val;
        return $this;
    }
    /**
    * Gets the safeMode
    * Safe mode is a troubleshooting option for Windows that starts your computer in a limited state
    *
    * @return string|null The safeMode
    */
    public function getSafeMode()
    {
        if (array_key_exists("safeMode", $this->_propDict)) {
            return $this->_propDict["safeMode"];
        } else {
            return null;
        }
    }

    /**
    * Sets the safeMode
    * Safe mode is a troubleshooting option for Windows that starts your computer in a limited state
    *
    * @param string $val The value of the safeMode
    *
    * @return DeviceHealthAttestationState
    */
    public function setSafeMode($val)
    {
        $this->_propDict["safeMode"] = $val;
        return $this;
    }
    /**
    * Gets the secureBoot
    * When Secure Boot is enabled, the core components must have the correct cryptographic signatures
    *
    * @return string|null The secureBoot
    */
    public function getSecureBoot()
    {
        if (array_key_exists("secureBoot", $this->_propDict)) {
            return $this->_propDict["secureBoot"];
        } else {
            return null;
        }
    }

    /**
    * Sets the secureBoot
    * When Secure Boot is enabled, the core components must have the correct cryptographic signatures
    *
    * @param string $val The value of the secureBoot
    *
    * @return DeviceHealthAttestationState
    */
    public function setSecureBoot($val)
    {
        $this->_propDict["secureBoot"] = $val;
        return $this;
    }
    /**
    * Gets the secureBootConfigurationPolicyFingerPrint
    * Fingerprint of the Custom Secure Boot Configuration Policy
    *
    * @return string|null The secureBootConfigurationPolicyFingerPrint
    */
    public function getSecureBootConfigurationPolicyFingerPrint()
    {
        if (array_key_exists("secureBootConfigurationPolicyFingerPrint", $this->_propDict)) {
            return $this->_propDict["secureBootConfigurationPolicyFingerPrint"];
        } else {
            return null;
        }
    }

    /**
    * Sets the secureBootConfigurationPolicyFingerPrint
    * Fingerprint of the Custom Secure Boot Configuration Policy
    *
    * @param string $val The value of the secureBootConfigurationPolicyFingerPrint
    *
    * @return DeviceHealthAttestationState
    */
    public function setSecureBootConfigurationPolicyFingerPrint($val)
    {
        $this->_propDict["secureBootConfigurationPolicyFingerPrint"] = $val;
        return $this;
    }

    /**
    * Gets the securedCorePC
    * Indicates whether the device has Secured-core PC enabled. Secured-core PCs provide protections that are useful against sophisticated attacks and provide increased assurance when handling mission-critical data. Possible values are "enabled", "disabled" and "notApplicable". "enabled" indicates Secured-core PC is enabled. "disabled" indicates Secured-core PC is disabled. "notApplicable" indicates the device is not a Windows 11 device. Default value is "notApplicable".
    *
    * @return AzureAttestationSettingStatus|null The securedCorePC
    */
    public function getSecuredCorePC()
    {
        if (array_key_exists("securedCorePC", $this->_propDict)) {
            if (is_a($this->_propDict["securedCorePC"], "\Beta\Microsoft\Graph\Model\AzureAttestationSettingStatus") || is_null($this->_propDict["securedCorePC"])) {
                return $this->_propDict["securedCorePC"];
            } else {
                $this->_propDict["securedCorePC"] = new AzureAttestationSettingStatus($this->_propDict["securedCorePC"]);
                return $this->_propDict["securedCorePC"];
            }
        }
        return null;
    }

    /**
    * Sets the securedCorePC
    * Indicates whether the device has Secured-core PC enabled. Secured-core PCs provide protections that are useful against sophisticated attacks and provide increased assurance when handling mission-critical data. Possible values are "enabled", "disabled" and "notApplicable". "enabled" indicates Secured-core PC is enabled. "disabled" indicates Secured-core PC is disabled. "notApplicable" indicates the device is not a Windows 11 device. Default value is "notApplicable".
    *
    * @param AzureAttestationSettingStatus $val The value to assign to the securedCorePC
    *
    * @return DeviceHealthAttestationState The DeviceHealthAttestationState
    */
    public function setSecuredCorePC($val)
    {
        $this->_propDict["securedCorePC"] = $val;
         return $this;
    }

    /**
    * Gets the systemManagementMode
    * Indicates the device meets enhanced hardware security. Possible values are "level1", "level2", "level3" and "notApplicable". "level1" indicates that deny System Management Mode (SMM) read/write access to OS and Virtualization-based security (VBS) memory. "level2" indicates that in addition to the System Management Mode (SMM) Level 1 protections, this level prevents System Management Mode (SMM) from tampering with Input-Output Memory Management Unit (IOMMU) config. "level3" indicates that in addition to the System Management Mode (SMM) Level 2 protections, this level reduces System Management Mode (SMM) save state capabilities. "notApplicable" indicates that the device does not have Firmware protection (System Management Mode) enabled. Default value is "notApplicable".
    *
    * @return SystemManagementModeLevel|null The systemManagementMode
    */
    public function getSystemManagementMode()
    {
        if (array_key_exists("systemManagementMode", $this->_propDict)) {
            if (is_a($this->_propDict["systemManagementMode"], "\Beta\Microsoft\Graph\Model\SystemManagementModeLevel") || is_null($this->_propDict["systemManagementMode"])) {
                return $this->_propDict["systemManagementMode"];
            } else {
                $this->_propDict["systemManagementMode"] = new SystemManagementModeLevel($this->_propDict["systemManagementMode"]);
                return $this->_propDict["systemManagementMode"];
            }
        }
        return null;
    }

    /**
    * Sets the systemManagementMode
    * Indicates the device meets enhanced hardware security. Possible values are "level1", "level2", "level3" and "notApplicable". "level1" indicates that deny System Management Mode (SMM) read/write access to OS and Virtualization-based security (VBS) memory. "level2" indicates that in addition to the System Management Mode (SMM) Level 1 protections, this level prevents System Management Mode (SMM) from tampering with Input-Output Memory Management Unit (IOMMU) config. "level3" indicates that in addition to the System Management Mode (SMM) Level 2 protections, this level reduces System Management Mode (SMM) save state capabilities. "notApplicable" indicates that the device does not have Firmware protection (System Management Mode) enabled. Default value is "notApplicable".
    *
    * @param SystemManagementModeLevel $val The value to assign to the systemManagementMode
    *
    * @return DeviceHealthAttestationState The DeviceHealthAttestationState
    */
    public function setSystemManagementMode($val)
    {
        $this->_propDict["systemManagementMode"] = $val;
         return $this;
    }
    /**
    * Gets the testSigning
    * When test signing is allowed, the device does not enforce signature validation during boot
    *
    * @return string|null The testSigning
    */
    public function getTestSigning()
    {
        if (array_key_exists("testSigning", $this->_propDict)) {
            return $this->_propDict["testSigning"];
        } else {
            return null;
        }
    }

    /**
    * Sets the testSigning
    * When test signing is allowed, the device does not enforce signature validation during boot
    *
    * @param string $val The value of the testSigning
    *
    * @return DeviceHealthAttestationState
    */
    public function setTestSigning($val)
    {
        $this->_propDict["testSigning"] = $val;
        return $this;
    }
    /**
    * Gets the tpmVersion
    * The security version number of the Boot Application
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
    * The security version number of the Boot Application
    *
    * @param string $val The value of the tpmVersion
    *
    * @return DeviceHealthAttestationState
    */
    public function setTpmVersion($val)
    {
        $this->_propDict["tpmVersion"] = $val;
        return $this;
    }

    /**
    * Gets the virtualizationBasedSecurity
    * Indicates whether the device has Virtualization-based security (VBS) enabled. Virtualization-based security (VBS) uses hardware virtualization and the Windows hypervisor to create an isolated virtual environment that becomes the root of trust of the OS that assumes the kernel can be compromised. Possible values are "enabled", "disabled" and "notApplicable". "enabled" indicates Virtualization-based security (VBS) is enabled. "disabled" indicates Virtualization-based security (VBS) is disabled. "notApplicable" indicates the device is not a Windows 11 device. Default value is "notApplicable".
    *
    * @return AzureAttestationSettingStatus|null The virtualizationBasedSecurity
    */
    public function getVirtualizationBasedSecurity()
    {
        if (array_key_exists("virtualizationBasedSecurity", $this->_propDict)) {
            if (is_a($this->_propDict["virtualizationBasedSecurity"], "\Beta\Microsoft\Graph\Model\AzureAttestationSettingStatus") || is_null($this->_propDict["virtualizationBasedSecurity"])) {
                return $this->_propDict["virtualizationBasedSecurity"];
            } else {
                $this->_propDict["virtualizationBasedSecurity"] = new AzureAttestationSettingStatus($this->_propDict["virtualizationBasedSecurity"]);
                return $this->_propDict["virtualizationBasedSecurity"];
            }
        }
        return null;
    }

    /**
    * Sets the virtualizationBasedSecurity
    * Indicates whether the device has Virtualization-based security (VBS) enabled. Virtualization-based security (VBS) uses hardware virtualization and the Windows hypervisor to create an isolated virtual environment that becomes the root of trust of the OS that assumes the kernel can be compromised. Possible values are "enabled", "disabled" and "notApplicable". "enabled" indicates Virtualization-based security (VBS) is enabled. "disabled" indicates Virtualization-based security (VBS) is disabled. "notApplicable" indicates the device is not a Windows 11 device. Default value is "notApplicable".
    *
    * @param AzureAttestationSettingStatus $val The value to assign to the virtualizationBasedSecurity
    *
    * @return DeviceHealthAttestationState The DeviceHealthAttestationState
    */
    public function setVirtualizationBasedSecurity($val)
    {
        $this->_propDict["virtualizationBasedSecurity"] = $val;
         return $this;
    }
    /**
    * Gets the virtualSecureMode
    * VSM is a container that protects high value assets from a compromised kernel
    *
    * @return string|null The virtualSecureMode
    */
    public function getVirtualSecureMode()
    {
        if (array_key_exists("virtualSecureMode", $this->_propDict)) {
            return $this->_propDict["virtualSecureMode"];
        } else {
            return null;
        }
    }

    /**
    * Sets the virtualSecureMode
    * VSM is a container that protects high value assets from a compromised kernel
    *
    * @param string $val The value of the virtualSecureMode
    *
    * @return DeviceHealthAttestationState
    */
    public function setVirtualSecureMode($val)
    {
        $this->_propDict["virtualSecureMode"] = $val;
        return $this;
    }
    /**
    * Gets the windowsPE
    * Operating system running with limited services that is used to prepare a computer for Windows
    *
    * @return string|null The windowsPE
    */
    public function getWindowsPE()
    {
        if (array_key_exists("windowsPE", $this->_propDict)) {
            return $this->_propDict["windowsPE"];
        } else {
            return null;
        }
    }

    /**
    * Sets the windowsPE
    * Operating system running with limited services that is used to prepare a computer for Windows
    *
    * @param string $val The value of the windowsPE
    *
    * @return DeviceHealthAttestationState
    */
    public function setWindowsPE($val)
    {
        $this->_propDict["windowsPE"] = $val;
        return $this;
    }
}
