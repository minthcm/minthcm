<?php
/*
 * Copyright 2014 Google Inc.
 *
 * Licensed under the Apache License, Version 2.0 (the "License"); you may not
 * use this file except in compliance with the License. You may obtain a copy of
 * the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied. See the
 * License for the specific language governing permissions and limitations under
 * the License.
 */

namespace Google\Service\Connectors;

class ConfigVariableTemplate extends \Google\Collection
{
  protected $collection_key = 'enumOptions';
  protected $authorizationCodeLinkType = AuthorizationCodeLink::class;
  protected $authorizationCodeLinkDataType = '';
  /**
   * @var string
   */
  public $description;
  /**
   * @var string
   */
  public $displayName;
  protected $enumOptionsType = EnumOption::class;
  protected $enumOptionsDataType = 'array';
  /**
   * @var string
   */
  public $key;
  /**
   * @var bool
   */
  public $required;
  protected $roleGrantType = RoleGrant::class;
  protected $roleGrantDataType = '';
  /**
   * @var string
   */
  public $state;
  /**
   * @var string
   */
  public $validationRegex;
  /**
   * @var string
   */
  public $valueType;

  /**
   * @param AuthorizationCodeLink
   */
  public function setAuthorizationCodeLink(AuthorizationCodeLink $authorizationCodeLink)
  {
    $this->authorizationCodeLink = $authorizationCodeLink;
  }
  /**
   * @return AuthorizationCodeLink
   */
  public function getAuthorizationCodeLink()
  {
    return $this->authorizationCodeLink;
  }
  /**
   * @param string
   */
  public function setDescription($description)
  {
    $this->description = $description;
  }
  /**
   * @return string
   */
  public function getDescription()
  {
    return $this->description;
  }
  /**
   * @param string
   */
  public function setDisplayName($displayName)
  {
    $this->displayName = $displayName;
  }
  /**
   * @return string
   */
  public function getDisplayName()
  {
    return $this->displayName;
  }
  /**
   * @param EnumOption[]
   */
  public function setEnumOptions($enumOptions)
  {
    $this->enumOptions = $enumOptions;
  }
  /**
   * @return EnumOption[]
   */
  public function getEnumOptions()
  {
    return $this->enumOptions;
  }
  /**
   * @param string
   */
  public function setKey($key)
  {
    $this->key = $key;
  }
  /**
   * @return string
   */
  public function getKey()
  {
    return $this->key;
  }
  /**
   * @param bool
   */
  public function setRequired($required)
  {
    $this->required = $required;
  }
  /**
   * @return bool
   */
  public function getRequired()
  {
    return $this->required;
  }
  /**
   * @param RoleGrant
   */
  public function setRoleGrant(RoleGrant $roleGrant)
  {
    $this->roleGrant = $roleGrant;
  }
  /**
   * @return RoleGrant
   */
  public function getRoleGrant()
  {
    return $this->roleGrant;
  }
  /**
   * @param string
   */
  public function setState($state)
  {
    $this->state = $state;
  }
  /**
   * @return string
   */
  public function getState()
  {
    return $this->state;
  }
  /**
   * @param string
   */
  public function setValidationRegex($validationRegex)
  {
    $this->validationRegex = $validationRegex;
  }
  /**
   * @return string
   */
  public function getValidationRegex()
  {
    return $this->validationRegex;
  }
  /**
   * @param string
   */
  public function setValueType($valueType)
  {
    $this->valueType = $valueType;
  }
  /**
   * @return string
   */
  public function getValueType()
  {
    return $this->valueType;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ConfigVariableTemplate::class, 'Google_Service_Connectors_ConfigVariableTemplate');
