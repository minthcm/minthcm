<?php
/**
* Copyright (c) Microsoft Corporation.  All Rights Reserved.  Licensed under the MIT License.  See License in the project root for license information.
* 
* StaleSignInAlertIncident File
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
* StaleSignInAlertIncident class
*
* @category  Model
* @package   Microsoft.Graph
* @copyright (c) Microsoft Corporation. All rights reserved.
* @license   https://opensource.org/licenses/MIT MIT License
* @link      https://graph.microsoft.com
*/
class StaleSignInAlertIncident extends UnifiedRoleManagementAlertIncident
{
    /**
    * Gets the assigneeDisplayName
    * Display name of the subject that the incident applies to.
    *
    * @return string|null The assigneeDisplayName
    */
    public function getAssigneeDisplayName()
    {
        if (array_key_exists("assigneeDisplayName", $this->_propDict)) {
            return $this->_propDict["assigneeDisplayName"];
        } else {
            return null;
        }
    }

    /**
    * Sets the assigneeDisplayName
    * Display name of the subject that the incident applies to.
    *
    * @param string $val The assigneeDisplayName
    *
    * @return StaleSignInAlertIncident
    */
    public function setAssigneeDisplayName($val)
    {
        $this->_propDict["assigneeDisplayName"] = $val;
        return $this;
    }

    /**
    * Gets the assigneeId
    * The identifier of the subject that the incident applies to.
    *
    * @return string|null The assigneeId
    */
    public function getAssigneeId()
    {
        if (array_key_exists("assigneeId", $this->_propDict)) {
            return $this->_propDict["assigneeId"];
        } else {
            return null;
        }
    }

    /**
    * Sets the assigneeId
    * The identifier of the subject that the incident applies to.
    *
    * @param string $val The assigneeId
    *
    * @return StaleSignInAlertIncident
    */
    public function setAssigneeId($val)
    {
        $this->_propDict["assigneeId"] = $val;
        return $this;
    }

    /**
    * Gets the assigneeUserPrincipalName
    * User principal name of the subject that the incident applies to. Applies to user principals.
    *
    * @return string|null The assigneeUserPrincipalName
    */
    public function getAssigneeUserPrincipalName()
    {
        if (array_key_exists("assigneeUserPrincipalName", $this->_propDict)) {
            return $this->_propDict["assigneeUserPrincipalName"];
        } else {
            return null;
        }
    }

    /**
    * Sets the assigneeUserPrincipalName
    * User principal name of the subject that the incident applies to. Applies to user principals.
    *
    * @param string $val The assigneeUserPrincipalName
    *
    * @return StaleSignInAlertIncident
    */
    public function setAssigneeUserPrincipalName($val)
    {
        $this->_propDict["assigneeUserPrincipalName"] = $val;
        return $this;
    }

    /**
    * Gets the assignmentCreatedDateTime
    * Date and time of assignment creation.
    *
    * @return \DateTime|null The assignmentCreatedDateTime
    */
    public function getAssignmentCreatedDateTime()
    {
        if (array_key_exists("assignmentCreatedDateTime", $this->_propDict)) {
            if (is_a($this->_propDict["assignmentCreatedDateTime"], "\DateTime") || is_null($this->_propDict["assignmentCreatedDateTime"])) {
                return $this->_propDict["assignmentCreatedDateTime"];
            } else {
                $this->_propDict["assignmentCreatedDateTime"] = new \DateTime($this->_propDict["assignmentCreatedDateTime"]);
                return $this->_propDict["assignmentCreatedDateTime"];
            }
        }
        return null;
    }

    /**
    * Sets the assignmentCreatedDateTime
    * Date and time of assignment creation.
    *
    * @param \DateTime $val The assignmentCreatedDateTime
    *
    * @return StaleSignInAlertIncident
    */
    public function setAssignmentCreatedDateTime($val)
    {
        $this->_propDict["assignmentCreatedDateTime"] = $val;
        return $this;
    }

    /**
    * Gets the lastSignInDateTime
    * Date and time of last sign in.
    *
    * @return \DateTime|null The lastSignInDateTime
    */
    public function getLastSignInDateTime()
    {
        if (array_key_exists("lastSignInDateTime", $this->_propDict)) {
            if (is_a($this->_propDict["lastSignInDateTime"], "\DateTime") || is_null($this->_propDict["lastSignInDateTime"])) {
                return $this->_propDict["lastSignInDateTime"];
            } else {
                $this->_propDict["lastSignInDateTime"] = new \DateTime($this->_propDict["lastSignInDateTime"]);
                return $this->_propDict["lastSignInDateTime"];
            }
        }
        return null;
    }

    /**
    * Sets the lastSignInDateTime
    * Date and time of last sign in.
    *
    * @param \DateTime $val The lastSignInDateTime
    *
    * @return StaleSignInAlertIncident
    */
    public function setLastSignInDateTime($val)
    {
        $this->_propDict["lastSignInDateTime"] = $val;
        return $this;
    }

    /**
    * Gets the roleDefinitionId
    * The identifier for the directory role definition that's in scope of this incident.
    *
    * @return string|null The roleDefinitionId
    */
    public function getRoleDefinitionId()
    {
        if (array_key_exists("roleDefinitionId", $this->_propDict)) {
            return $this->_propDict["roleDefinitionId"];
        } else {
            return null;
        }
    }

    /**
    * Sets the roleDefinitionId
    * The identifier for the directory role definition that's in scope of this incident.
    *
    * @param string $val The roleDefinitionId
    *
    * @return StaleSignInAlertIncident
    */
    public function setRoleDefinitionId($val)
    {
        $this->_propDict["roleDefinitionId"] = $val;
        return $this;
    }

    /**
    * Gets the roleDisplayName
    * The display name for the directory role.
    *
    * @return string|null The roleDisplayName
    */
    public function getRoleDisplayName()
    {
        if (array_key_exists("roleDisplayName", $this->_propDict)) {
            return $this->_propDict["roleDisplayName"];
        } else {
            return null;
        }
    }

    /**
    * Sets the roleDisplayName
    * The display name for the directory role.
    *
    * @param string $val The roleDisplayName
    *
    * @return StaleSignInAlertIncident
    */
    public function setRoleDisplayName($val)
    {
        $this->_propDict["roleDisplayName"] = $val;
        return $this;
    }

    /**
    * Gets the roleTemplateId
    * The globally unique identifier for the directory role.
    *
    * @return string|null The roleTemplateId
    */
    public function getRoleTemplateId()
    {
        if (array_key_exists("roleTemplateId", $this->_propDict)) {
            return $this->_propDict["roleTemplateId"];
        } else {
            return null;
        }
    }

    /**
    * Sets the roleTemplateId
    * The globally unique identifier for the directory role.
    *
    * @param string $val The roleTemplateId
    *
    * @return StaleSignInAlertIncident
    */
    public function setRoleTemplateId($val)
    {
        $this->_propDict["roleTemplateId"] = $val;
        return $this;
    }

}
