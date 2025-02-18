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

namespace Google\Service\Compute;

class SecurityPolicy extends \Google\Collection
{
  protected $collection_key = 'userDefinedFields';
  protected $adaptiveProtectionConfigType = SecurityPolicyAdaptiveProtectionConfig::class;
  protected $adaptiveProtectionConfigDataType = '';
  protected $advancedOptionsConfigType = SecurityPolicyAdvancedOptionsConfig::class;
  protected $advancedOptionsConfigDataType = '';
  /**
   * @var string
   */
  public $creationTimestamp;
  protected $ddosProtectionConfigType = SecurityPolicyDdosProtectionConfig::class;
  protected $ddosProtectionConfigDataType = '';
  /**
   * @var string
   */
  public $description;
  /**
   * @var string
   */
  public $fingerprint;
  /**
   * @var string
   */
  public $id;
  /**
   * @var string
   */
  public $kind;
  /**
   * @var string
   */
  public $labelFingerprint;
  /**
   * @var string[]
   */
  public $labels;
  /**
   * @var string
   */
  public $name;
  protected $recaptchaOptionsConfigType = SecurityPolicyRecaptchaOptionsConfig::class;
  protected $recaptchaOptionsConfigDataType = '';
  /**
   * @var string
   */
  public $region;
  protected $rulesType = SecurityPolicyRule::class;
  protected $rulesDataType = 'array';
  /**
   * @var string
   */
  public $selfLink;
  /**
   * @var string
   */
  public $type;
  protected $userDefinedFieldsType = SecurityPolicyUserDefinedField::class;
  protected $userDefinedFieldsDataType = 'array';

  /**
   * @param SecurityPolicyAdaptiveProtectionConfig
   */
  public function setAdaptiveProtectionConfig(SecurityPolicyAdaptiveProtectionConfig $adaptiveProtectionConfig)
  {
    $this->adaptiveProtectionConfig = $adaptiveProtectionConfig;
  }
  /**
   * @return SecurityPolicyAdaptiveProtectionConfig
   */
  public function getAdaptiveProtectionConfig()
  {
    return $this->adaptiveProtectionConfig;
  }
  /**
   * @param SecurityPolicyAdvancedOptionsConfig
   */
  public function setAdvancedOptionsConfig(SecurityPolicyAdvancedOptionsConfig $advancedOptionsConfig)
  {
    $this->advancedOptionsConfig = $advancedOptionsConfig;
  }
  /**
   * @return SecurityPolicyAdvancedOptionsConfig
   */
  public function getAdvancedOptionsConfig()
  {
    return $this->advancedOptionsConfig;
  }
  /**
   * @param string
   */
  public function setCreationTimestamp($creationTimestamp)
  {
    $this->creationTimestamp = $creationTimestamp;
  }
  /**
   * @return string
   */
  public function getCreationTimestamp()
  {
    return $this->creationTimestamp;
  }
  /**
   * @param SecurityPolicyDdosProtectionConfig
   */
  public function setDdosProtectionConfig(SecurityPolicyDdosProtectionConfig $ddosProtectionConfig)
  {
    $this->ddosProtectionConfig = $ddosProtectionConfig;
  }
  /**
   * @return SecurityPolicyDdosProtectionConfig
   */
  public function getDdosProtectionConfig()
  {
    return $this->ddosProtectionConfig;
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
  public function setFingerprint($fingerprint)
  {
    $this->fingerprint = $fingerprint;
  }
  /**
   * @return string
   */
  public function getFingerprint()
  {
    return $this->fingerprint;
  }
  /**
   * @param string
   */
  public function setId($id)
  {
    $this->id = $id;
  }
  /**
   * @return string
   */
  public function getId()
  {
    return $this->id;
  }
  /**
   * @param string
   */
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  /**
   * @return string
   */
  public function getKind()
  {
    return $this->kind;
  }
  /**
   * @param string
   */
  public function setLabelFingerprint($labelFingerprint)
  {
    $this->labelFingerprint = $labelFingerprint;
  }
  /**
   * @return string
   */
  public function getLabelFingerprint()
  {
    return $this->labelFingerprint;
  }
  /**
   * @param string[]
   */
  public function setLabels($labels)
  {
    $this->labels = $labels;
  }
  /**
   * @return string[]
   */
  public function getLabels()
  {
    return $this->labels;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
  /**
   * @param SecurityPolicyRecaptchaOptionsConfig
   */
  public function setRecaptchaOptionsConfig(SecurityPolicyRecaptchaOptionsConfig $recaptchaOptionsConfig)
  {
    $this->recaptchaOptionsConfig = $recaptchaOptionsConfig;
  }
  /**
   * @return SecurityPolicyRecaptchaOptionsConfig
   */
  public function getRecaptchaOptionsConfig()
  {
    return $this->recaptchaOptionsConfig;
  }
  /**
   * @param string
   */
  public function setRegion($region)
  {
    $this->region = $region;
  }
  /**
   * @return string
   */
  public function getRegion()
  {
    return $this->region;
  }
  /**
   * @param SecurityPolicyRule[]
   */
  public function setRules($rules)
  {
    $this->rules = $rules;
  }
  /**
   * @return SecurityPolicyRule[]
   */
  public function getRules()
  {
    return $this->rules;
  }
  /**
   * @param string
   */
  public function setSelfLink($selfLink)
  {
    $this->selfLink = $selfLink;
  }
  /**
   * @return string
   */
  public function getSelfLink()
  {
    return $this->selfLink;
  }
  /**
   * @param string
   */
  public function setType($type)
  {
    $this->type = $type;
  }
  /**
   * @return string
   */
  public function getType()
  {
    return $this->type;
  }
  /**
   * @param SecurityPolicyUserDefinedField[]
   */
  public function setUserDefinedFields($userDefinedFields)
  {
    $this->userDefinedFields = $userDefinedFields;
  }
  /**
   * @return SecurityPolicyUserDefinedField[]
   */
  public function getUserDefinedFields()
  {
    return $this->userDefinedFields;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SecurityPolicy::class, 'Google_Service_Compute_SecurityPolicy');
