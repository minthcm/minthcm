<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* JournalLine File
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
* JournalLine class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class JournalLine implements \JsonSerializable
{
    /**
    * The array of properties available
    * to the model
    *
    * @var array $_propDict
    */
    protected $_propDict;

    /**
    * Construct a new JournalLine
    *
    * @param array $propDict A list of properties to set
    */
    function __construct($propDict = array())
    {
        if (!is_array($propDict)) {
           $propDict = array();
        }
        $this->_propDict = $propDict;
    }

    /**
    * Gets the property dictionary of the JournalLine
    *
    * @return array The list of properties
    */
    public function getProperties()
    {
        return $this->_propDict;
    }

    /**
    * Gets the accountId
    *
    * @return string|null The accountId
    */
    public function getAccountId()
    {
        if (array_key_exists("accountId", $this->_propDict)) {
            return $this->_propDict["accountId"];
        } else {
            return null;
        }
    }

    /**
    * Sets the accountId
    *
    * @param string $val The accountId
    *
    * @return JournalLine
    */
    public function setAccountId($val)
    {
        $this->_propDict["accountId"] = $val;
        return $this;
    }

    /**
    * Gets the accountNumber
    *
    * @return string|null The accountNumber
    */
    public function getAccountNumber()
    {
        if (array_key_exists("accountNumber", $this->_propDict)) {
            return $this->_propDict["accountNumber"];
        } else {
            return null;
        }
    }

    /**
    * Sets the accountNumber
    *
    * @param string $val The accountNumber
    *
    * @return JournalLine
    */
    public function setAccountNumber($val)
    {
        $this->_propDict["accountNumber"] = $val;
        return $this;
    }

    /**
    * Gets the amount
    *
    * @return Decimal|null The amount
    */
    public function getAmount()
    {
        if (array_key_exists("amount", $this->_propDict)) {
            if (is_a($this->_propDict["amount"], "\Beta\Microsoft\Graph\Model\Decimal") || is_null($this->_propDict["amount"])) {
                return $this->_propDict["amount"];
            } else {
                $this->_propDict["amount"] = new Decimal($this->_propDict["amount"]);
                return $this->_propDict["amount"];
            }
        }
        return null;
    }

    /**
    * Sets the amount
    *
    * @param Decimal $val The amount
    *
    * @return JournalLine
    */
    public function setAmount($val)
    {
        $this->_propDict["amount"] = $val;
        return $this;
    }

    /**
    * Gets the comment
    *
    * @return string|null The comment
    */
    public function getComment()
    {
        if (array_key_exists("comment", $this->_propDict)) {
            return $this->_propDict["comment"];
        } else {
            return null;
        }
    }

    /**
    * Sets the comment
    *
    * @param string $val The comment
    *
    * @return JournalLine
    */
    public function setComment($val)
    {
        $this->_propDict["comment"] = $val;
        return $this;
    }

    /**
    * Gets the description
    *
    * @return string|null The description
    */
    public function getDescription()
    {
        if (array_key_exists("description", $this->_propDict)) {
            return $this->_propDict["description"];
        } else {
            return null;
        }
    }

    /**
    * Sets the description
    *
    * @param string $val The description
    *
    * @return JournalLine
    */
    public function setDescription($val)
    {
        $this->_propDict["description"] = $val;
        return $this;
    }

    /**
    * Gets the documentNumber
    *
    * @return string|null The documentNumber
    */
    public function getDocumentNumber()
    {
        if (array_key_exists("documentNumber", $this->_propDict)) {
            return $this->_propDict["documentNumber"];
        } else {
            return null;
        }
    }

    /**
    * Sets the documentNumber
    *
    * @param string $val The documentNumber
    *
    * @return JournalLine
    */
    public function setDocumentNumber($val)
    {
        $this->_propDict["documentNumber"] = $val;
        return $this;
    }

    /**
    * Gets the externalDocumentNumber
    *
    * @return string|null The externalDocumentNumber
    */
    public function getExternalDocumentNumber()
    {
        if (array_key_exists("externalDocumentNumber", $this->_propDict)) {
            return $this->_propDict["externalDocumentNumber"];
        } else {
            return null;
        }
    }

    /**
    * Sets the externalDocumentNumber
    *
    * @param string $val The externalDocumentNumber
    *
    * @return JournalLine
    */
    public function setExternalDocumentNumber($val)
    {
        $this->_propDict["externalDocumentNumber"] = $val;
        return $this;
    }

    /**
    * Gets the id
    *
    * @return string|null The id
    */
    public function getId()
    {
        if (array_key_exists("id", $this->_propDict)) {
            return $this->_propDict["id"];
        } else {
            return null;
        }
    }

    /**
    * Sets the id
    *
    * @param string $val The id
    *
    * @return JournalLine
    */
    public function setId($val)
    {
        $this->_propDict["id"] = $val;
        return $this;
    }

    /**
    * Gets the journalDisplayName
    *
    * @return string|null The journalDisplayName
    */
    public function getJournalDisplayName()
    {
        if (array_key_exists("journalDisplayName", $this->_propDict)) {
            return $this->_propDict["journalDisplayName"];
        } else {
            return null;
        }
    }

    /**
    * Sets the journalDisplayName
    *
    * @param string $val The journalDisplayName
    *
    * @return JournalLine
    */
    public function setJournalDisplayName($val)
    {
        $this->_propDict["journalDisplayName"] = $val;
        return $this;
    }

    /**
    * Gets the lastModifiedDateTime
    *
    * @return \DateTime|null The lastModifiedDateTime
    */
    public function getLastModifiedDateTime()
    {
        if (array_key_exists("lastModifiedDateTime", $this->_propDict)) {
            if (is_a($this->_propDict["lastModifiedDateTime"], "\DateTime") || is_null($this->_propDict["lastModifiedDateTime"])) {
                return $this->_propDict["lastModifiedDateTime"];
            } else {
                $this->_propDict["lastModifiedDateTime"] = new \DateTime($this->_propDict["lastModifiedDateTime"]);
                return $this->_propDict["lastModifiedDateTime"];
            }
        }
        return null;
    }

    /**
    * Sets the lastModifiedDateTime
    *
    * @param \DateTime $val The lastModifiedDateTime
    *
    * @return JournalLine
    */
    public function setLastModifiedDateTime($val)
    {
        $this->_propDict["lastModifiedDateTime"] = $val;
        return $this;
    }

    /**
    * Gets the lineNumber
    *
    * @return int|null The lineNumber
    */
    public function getLineNumber()
    {
        if (array_key_exists("lineNumber", $this->_propDict)) {
            return $this->_propDict["lineNumber"];
        } else {
            return null;
        }
    }

    /**
    * Sets the lineNumber
    *
    * @param int $val The lineNumber
    *
    * @return JournalLine
    */
    public function setLineNumber($val)
    {
        $this->_propDict["lineNumber"] = intval($val);
        return $this;
    }

    /**
    * Gets the postingDate
    *
    * @return \DateTime|null The postingDate
    */
    public function getPostingDate()
    {
        if (array_key_exists("postingDate", $this->_propDict)) {
            if (is_a($this->_propDict["postingDate"], "\DateTime") || is_null($this->_propDict["postingDate"])) {
                return $this->_propDict["postingDate"];
            } else {
                $this->_propDict["postingDate"] = new \DateTime($this->_propDict["postingDate"]);
                return $this->_propDict["postingDate"];
            }
        }
        return null;
    }

    /**
    * Sets the postingDate
    *
    * @param \DateTime $val The postingDate
    *
    * @return JournalLine
    */
    public function setPostingDate($val)
    {
        $this->_propDict["postingDate"] = $val;
        return $this;
    }

    /**
    * Gets the account
    *
    * @return Account|null The account
    */
    public function getAccount()
    {
        if (array_key_exists("account", $this->_propDict)) {
            if (is_a($this->_propDict["account"], "\Beta\Microsoft\Graph\Model\Account") || is_null($this->_propDict["account"])) {
                return $this->_propDict["account"];
            } else {
                $this->_propDict["account"] = new Account($this->_propDict["account"]);
                return $this->_propDict["account"];
            }
        }
        return null;
    }

    /**
    * Sets the account
    *
    * @param Account $val The account
    *
    * @return JournalLine
    */
    public function setAccount($val)
    {
        $this->_propDict["account"] = $val;
        return $this;
    }

    /**
    * Gets the ODataType
    *
    * @return string|null The ODataType
    */
    public function getODataType()
    {
        if (array_key_exists('@odata.type', $this->_propDict)) {
            return $this->_propDict["@odata.type"];
        }
        return null;
    }

    /**
    * Sets the ODataType
    *
    * @param string $val The ODataType
    *
    * @return JournalLine
    */
    public function setODataType($val)
    {
        $this->_propDict["@odata.type"] = $val;
        return $this;
    }

    /**
    * Serializes the object by property array
    * Manually serialize DateTime into RFC3339 format
    *
    * @return array The list of properties
    */
    #[\ReturnTypeWillChange]
    public function jsonSerialize()
    {
        $serializableProperties = $this->getProperties();
        foreach ($serializableProperties as $property => $val) {
            if (is_a($val, "\DateTime")) {
                $serializableProperties[$property] = $val->format(\DateTime::RFC3339);
            } else if (is_a($val, "\Microsoft\Graph\Core\Enum")) {
                $serializableProperties[$property] = $val->value();
            } else if (is_a($val, "\Entity")) {
                $serializableProperties[$property] = $val->jsonSerialize();
            } else if (is_a($val, "\GuzzleHttp\Psr7\Stream")) {
                $serializableProperties[$property] = (string) $val;
            }
        }
        return $serializableProperties;
    }
}
