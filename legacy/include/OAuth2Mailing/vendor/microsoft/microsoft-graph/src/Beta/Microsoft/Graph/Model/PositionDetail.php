<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* PositionDetail File
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
* PositionDetail class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class PositionDetail extends Entity
{

    /**
    * Gets the company
    * Detail about the company or employer.
    *
    * @return CompanyDetail|null The company
    */
    public function getCompany()
    {
        if (array_key_exists("company", $this->_propDict)) {
            if (is_a($this->_propDict["company"], "\Beta\Microsoft\Graph\Model\CompanyDetail") || is_null($this->_propDict["company"])) {
                return $this->_propDict["company"];
            } else {
                $this->_propDict["company"] = new CompanyDetail($this->_propDict["company"]);
                return $this->_propDict["company"];
            }
        }
        return null;
    }

    /**
    * Sets the company
    * Detail about the company or employer.
    *
    * @param CompanyDetail $val The value to assign to the company
    *
    * @return PositionDetail The PositionDetail
    */
    public function setCompany($val)
    {
        $this->_propDict["company"] = $val;
         return $this;
    }
    /**
    * Gets the description
    * Description of the position in question.
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
    * Description of the position in question.
    *
    * @param string $val The value of the description
    *
    * @return PositionDetail
    */
    public function setDescription($val)
    {
        $this->_propDict["description"] = $val;
        return $this;
    }

    /**
    * Gets the endMonthYear
    * When the position ended.
    *
    * @return \DateTime|null The endMonthYear
    */
    public function getEndMonthYear()
    {
        if (array_key_exists("endMonthYear", $this->_propDict)) {
            if (is_a($this->_propDict["endMonthYear"], "\DateTime") || is_null($this->_propDict["endMonthYear"])) {
                return $this->_propDict["endMonthYear"];
            } else {
                $this->_propDict["endMonthYear"] = new \DateTime($this->_propDict["endMonthYear"]);
                return $this->_propDict["endMonthYear"];
            }
        }
        return null;
    }

    /**
    * Sets the endMonthYear
    * When the position ended.
    *
    * @param \DateTime $val The value to assign to the endMonthYear
    *
    * @return PositionDetail The PositionDetail
    */
    public function setEndMonthYear($val)
    {
        $this->_propDict["endMonthYear"] = $val;
         return $this;
    }
    /**
    * Gets the jobTitle
    * The title held when in that position.
    *
    * @return string|null The jobTitle
    */
    public function getJobTitle()
    {
        if (array_key_exists("jobTitle", $this->_propDict)) {
            return $this->_propDict["jobTitle"];
        } else {
            return null;
        }
    }

    /**
    * Sets the jobTitle
    * The title held when in that position.
    *
    * @param string $val The value of the jobTitle
    *
    * @return PositionDetail
    */
    public function setJobTitle($val)
    {
        $this->_propDict["jobTitle"] = $val;
        return $this;
    }
    /**
    * Gets the layer
    *
    * @return int|null The layer
    */
    public function getLayer()
    {
        if (array_key_exists("layer", $this->_propDict)) {
            return $this->_propDict["layer"];
        } else {
            return null;
        }
    }

    /**
    * Sets the layer
    *
    * @param int $val The value of the layer
    *
    * @return PositionDetail
    */
    public function setLayer($val)
    {
        $this->_propDict["layer"] = $val;
        return $this;
    }
    /**
    * Gets the level
    *
    * @return string|null The level
    */
    public function getLevel()
    {
        if (array_key_exists("level", $this->_propDict)) {
            return $this->_propDict["level"];
        } else {
            return null;
        }
    }

    /**
    * Sets the level
    *
    * @param string $val The value of the level
    *
    * @return PositionDetail
    */
    public function setLevel($val)
    {
        $this->_propDict["level"] = $val;
        return $this;
    }
    /**
    * Gets the role
    * The role the position entailed.
    *
    * @return string|null The role
    */
    public function getRole()
    {
        if (array_key_exists("role", $this->_propDict)) {
            return $this->_propDict["role"];
        } else {
            return null;
        }
    }

    /**
    * Sets the role
    * The role the position entailed.
    *
    * @param string $val The value of the role
    *
    * @return PositionDetail
    */
    public function setRole($val)
    {
        $this->_propDict["role"] = $val;
        return $this;
    }

    /**
    * Gets the startMonthYear
    * The start month and year of the position.
    *
    * @return \DateTime|null The startMonthYear
    */
    public function getStartMonthYear()
    {
        if (array_key_exists("startMonthYear", $this->_propDict)) {
            if (is_a($this->_propDict["startMonthYear"], "\DateTime") || is_null($this->_propDict["startMonthYear"])) {
                return $this->_propDict["startMonthYear"];
            } else {
                $this->_propDict["startMonthYear"] = new \DateTime($this->_propDict["startMonthYear"]);
                return $this->_propDict["startMonthYear"];
            }
        }
        return null;
    }

    /**
    * Sets the startMonthYear
    * The start month and year of the position.
    *
    * @param \DateTime $val The value to assign to the startMonthYear
    *
    * @return PositionDetail The PositionDetail
    */
    public function setStartMonthYear($val)
    {
        $this->_propDict["startMonthYear"] = $val;
         return $this;
    }
    /**
    * Gets the summary
    * Short summary of the position.
    *
    * @return string|null The summary
    */
    public function getSummary()
    {
        if (array_key_exists("summary", $this->_propDict)) {
            return $this->_propDict["summary"];
        } else {
            return null;
        }
    }

    /**
    * Sets the summary
    * Short summary of the position.
    *
    * @param string $val The value of the summary
    *
    * @return PositionDetail
    */
    public function setSummary($val)
    {
        $this->_propDict["summary"] = $val;
        return $this;
    }
}
