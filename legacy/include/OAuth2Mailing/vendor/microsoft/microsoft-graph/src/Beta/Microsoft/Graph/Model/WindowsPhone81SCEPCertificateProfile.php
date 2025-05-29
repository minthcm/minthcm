<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* WindowsPhone81SCEPCertificateProfile File
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
* WindowsPhone81SCEPCertificateProfile class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class WindowsPhone81SCEPCertificateProfile extends WindowsPhone81CertificateProfileBase
{
    /**
    * Gets the hashAlgorithm
    * SCEP Hash Algorithm. Possible values are: sha1, sha2.
    *
    * @return HashAlgorithms|null The hashAlgorithm
    */
    public function getHashAlgorithm()
    {
        if (array_key_exists("hashAlgorithm", $this->_propDict)) {
            if (is_a($this->_propDict["hashAlgorithm"], "\Beta\Microsoft\Graph\Model\HashAlgorithms") || is_null($this->_propDict["hashAlgorithm"])) {
                return $this->_propDict["hashAlgorithm"];
            } else {
                $this->_propDict["hashAlgorithm"] = new HashAlgorithms($this->_propDict["hashAlgorithm"]);
                return $this->_propDict["hashAlgorithm"];
            }
        }
        return null;
    }

    /**
    * Sets the hashAlgorithm
    * SCEP Hash Algorithm. Possible values are: sha1, sha2.
    *
    * @param HashAlgorithms $val The hashAlgorithm
    *
    * @return WindowsPhone81SCEPCertificateProfile
    */
    public function setHashAlgorithm($val)
    {
        $this->_propDict["hashAlgorithm"] = $val;
        return $this;
    }

    /**
    * Gets the keySize
    * SCEP Key Size. Possible values are: size1024, size2048, size4096.
    *
    * @return KeySize|null The keySize
    */
    public function getKeySize()
    {
        if (array_key_exists("keySize", $this->_propDict)) {
            if (is_a($this->_propDict["keySize"], "\Beta\Microsoft\Graph\Model\KeySize") || is_null($this->_propDict["keySize"])) {
                return $this->_propDict["keySize"];
            } else {
                $this->_propDict["keySize"] = new KeySize($this->_propDict["keySize"]);
                return $this->_propDict["keySize"];
            }
        }
        return null;
    }

    /**
    * Sets the keySize
    * SCEP Key Size. Possible values are: size1024, size2048, size4096.
    *
    * @param KeySize $val The keySize
    *
    * @return WindowsPhone81SCEPCertificateProfile
    */
    public function setKeySize($val)
    {
        $this->_propDict["keySize"] = $val;
        return $this;
    }

    /**
    * Gets the keyUsage
    * SCEP Key Usage. Possible values are: keyEncipherment, digitalSignature.
    *
    * @return KeyUsages|null The keyUsage
    */
    public function getKeyUsage()
    {
        if (array_key_exists("keyUsage", $this->_propDict)) {
            if (is_a($this->_propDict["keyUsage"], "\Beta\Microsoft\Graph\Model\KeyUsages") || is_null($this->_propDict["keyUsage"])) {
                return $this->_propDict["keyUsage"];
            } else {
                $this->_propDict["keyUsage"] = new KeyUsages($this->_propDict["keyUsage"]);
                return $this->_propDict["keyUsage"];
            }
        }
        return null;
    }

    /**
    * Sets the keyUsage
    * SCEP Key Usage. Possible values are: keyEncipherment, digitalSignature.
    *
    * @param KeyUsages $val The keyUsage
    *
    * @return WindowsPhone81SCEPCertificateProfile
    */
    public function setKeyUsage($val)
    {
        $this->_propDict["keyUsage"] = $val;
        return $this;
    }

    /**
    * Gets the scepServerUrls
    * SCEP Server Url(s).
    *
    * @return array|null The scepServerUrls
    */
    public function getScepServerUrls()
    {
        if (array_key_exists("scepServerUrls", $this->_propDict)) {
            return $this->_propDict["scepServerUrls"];
        } else {
            return null;
        }
    }

    /**
    * Sets the scepServerUrls
    * SCEP Server Url(s).
    *
    * @param string[] $val The scepServerUrls
    *
    * @return WindowsPhone81SCEPCertificateProfile
    */
    public function setScepServerUrls($val)
    {
        $this->_propDict["scepServerUrls"] = $val;
        return $this;
    }

    /**
    * Gets the subjectAlternativeNameFormatString
    * Custom String that defines the AAD Attribute.
    *
    * @return string|null The subjectAlternativeNameFormatString
    */
    public function getSubjectAlternativeNameFormatString()
    {
        if (array_key_exists("subjectAlternativeNameFormatString", $this->_propDict)) {
            return $this->_propDict["subjectAlternativeNameFormatString"];
        } else {
            return null;
        }
    }

    /**
    * Sets the subjectAlternativeNameFormatString
    * Custom String that defines the AAD Attribute.
    *
    * @param string $val The subjectAlternativeNameFormatString
    *
    * @return WindowsPhone81SCEPCertificateProfile
    */
    public function setSubjectAlternativeNameFormatString($val)
    {
        $this->_propDict["subjectAlternativeNameFormatString"] = $val;
        return $this;
    }

    /**
    * Gets the subjectNameFormatString
    * Custom format to use with SubjectNameFormat = Custom. Example: CN={{EmailAddress}},E={{EmailAddress}},OU=Enterprise Users,O=Contoso Corporation,L=Redmond,ST=WA,C=US
    *
    * @return string|null The subjectNameFormatString
    */
    public function getSubjectNameFormatString()
    {
        if (array_key_exists("subjectNameFormatString", $this->_propDict)) {
            return $this->_propDict["subjectNameFormatString"];
        } else {
            return null;
        }
    }

    /**
    * Sets the subjectNameFormatString
    * Custom format to use with SubjectNameFormat = Custom. Example: CN={{EmailAddress}},E={{EmailAddress}},OU=Enterprise Users,O=Contoso Corporation,L=Redmond,ST=WA,C=US
    *
    * @param string $val The subjectNameFormatString
    *
    * @return WindowsPhone81SCEPCertificateProfile
    */
    public function setSubjectNameFormatString($val)
    {
        $this->_propDict["subjectNameFormatString"] = $val;
        return $this;
    }


     /**
     * Gets the managedDeviceCertificateStates
    * Certificate state for devices. This collection can contain a maximum of 2147483647 elements.
     *
     * @return array|null The managedDeviceCertificateStates
     */
    public function getManagedDeviceCertificateStates()
    {
        if (array_key_exists("managedDeviceCertificateStates", $this->_propDict)) {
           return $this->_propDict["managedDeviceCertificateStates"];
        } else {
            return null;
        }
    }

    /**
    * Sets the managedDeviceCertificateStates
    * Certificate state for devices. This collection can contain a maximum of 2147483647 elements.
    *
    * @param ManagedDeviceCertificateState[] $val The managedDeviceCertificateStates
    *
    * @return WindowsPhone81SCEPCertificateProfile
    */
    public function setManagedDeviceCertificateStates($val)
    {
        $this->_propDict["managedDeviceCertificateStates"] = $val;
        return $this;
    }

    /**
    * Gets the rootCertificate
    * Trusted Root Certificate.
    *
    * @return WindowsPhone81TrustedRootCertificate|null The rootCertificate
    */
    public function getRootCertificate()
    {
        if (array_key_exists("rootCertificate", $this->_propDict)) {
            if (is_a($this->_propDict["rootCertificate"], "\Beta\Microsoft\Graph\Model\WindowsPhone81TrustedRootCertificate") || is_null($this->_propDict["rootCertificate"])) {
                return $this->_propDict["rootCertificate"];
            } else {
                $this->_propDict["rootCertificate"] = new WindowsPhone81TrustedRootCertificate($this->_propDict["rootCertificate"]);
                return $this->_propDict["rootCertificate"];
            }
        }
        return null;
    }

    /**
    * Sets the rootCertificate
    * Trusted Root Certificate.
    *
    * @param WindowsPhone81TrustedRootCertificate $val The rootCertificate
    *
    * @return WindowsPhone81SCEPCertificateProfile
    */
    public function setRootCertificate($val)
    {
        $this->_propDict["rootCertificate"] = $val;
        return $this;
    }

}
