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

namespace Google\Service\ChromePolicy;

class GoogleChromePolicyVersionsV1PolicySchema extends \Google\Collection
{
  protected $collection_key = 'validTargetResources';
  /**
   * @var string[]
   */
  public $accessRestrictions;
  protected $additionalTargetKeyNamesType = GoogleChromePolicyVersionsV1AdditionalTargetKeyName::class;
  protected $additionalTargetKeyNamesDataType = 'array';
  /**
   * @var string
   */
  public $categoryTitle;
  protected $definitionType = Proto2FileDescriptorProto::class;
  protected $definitionDataType = '';
  protected $fieldDescriptionsType = GoogleChromePolicyVersionsV1PolicySchemaFieldDescription::class;
  protected $fieldDescriptionsDataType = 'array';
  /**
   * @var string
   */
  public $name;
  protected $noticesType = GoogleChromePolicyVersionsV1PolicySchemaNoticeDescription::class;
  protected $noticesDataType = 'array';
  protected $policyApiLifecycleType = GoogleChromePolicyVersionsV1PolicyApiLifecycle::class;
  protected $policyApiLifecycleDataType = '';
  /**
   * @var string
   */
  public $policyDescription;
  /**
   * @var string
   */
  public $schemaName;
  /**
   * @var string
   */
  public $supportUri;
  /**
   * @var string[]
   */
  public $supportedPlatforms;
  /**
   * @var string[]
   */
  public $validTargetResources;

  /**
   * @param string[]
   */
  public function setAccessRestrictions($accessRestrictions)
  {
    $this->accessRestrictions = $accessRestrictions;
  }
  /**
   * @return string[]
   */
  public function getAccessRestrictions()
  {
    return $this->accessRestrictions;
  }
  /**
   * @param GoogleChromePolicyVersionsV1AdditionalTargetKeyName[]
   */
  public function setAdditionalTargetKeyNames($additionalTargetKeyNames)
  {
    $this->additionalTargetKeyNames = $additionalTargetKeyNames;
  }
  /**
   * @return GoogleChromePolicyVersionsV1AdditionalTargetKeyName[]
   */
  public function getAdditionalTargetKeyNames()
  {
    return $this->additionalTargetKeyNames;
  }
  /**
   * @param string
   */
  public function setCategoryTitle($categoryTitle)
  {
    $this->categoryTitle = $categoryTitle;
  }
  /**
   * @return string
   */
  public function getCategoryTitle()
  {
    return $this->categoryTitle;
  }
  /**
   * @param Proto2FileDescriptorProto
   */
  public function setDefinition(Proto2FileDescriptorProto $definition)
  {
    $this->definition = $definition;
  }
  /**
   * @return Proto2FileDescriptorProto
   */
  public function getDefinition()
  {
    return $this->definition;
  }
  /**
   * @param GoogleChromePolicyVersionsV1PolicySchemaFieldDescription[]
   */
  public function setFieldDescriptions($fieldDescriptions)
  {
    $this->fieldDescriptions = $fieldDescriptions;
  }
  /**
   * @return GoogleChromePolicyVersionsV1PolicySchemaFieldDescription[]
   */
  public function getFieldDescriptions()
  {
    return $this->fieldDescriptions;
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
   * @param GoogleChromePolicyVersionsV1PolicySchemaNoticeDescription[]
   */
  public function setNotices($notices)
  {
    $this->notices = $notices;
  }
  /**
   * @return GoogleChromePolicyVersionsV1PolicySchemaNoticeDescription[]
   */
  public function getNotices()
  {
    return $this->notices;
  }
  /**
   * @param GoogleChromePolicyVersionsV1PolicyApiLifecycle
   */
  public function setPolicyApiLifecycle(GoogleChromePolicyVersionsV1PolicyApiLifecycle $policyApiLifecycle)
  {
    $this->policyApiLifecycle = $policyApiLifecycle;
  }
  /**
   * @return GoogleChromePolicyVersionsV1PolicyApiLifecycle
   */
  public function getPolicyApiLifecycle()
  {
    return $this->policyApiLifecycle;
  }
  /**
   * @param string
   */
  public function setPolicyDescription($policyDescription)
  {
    $this->policyDescription = $policyDescription;
  }
  /**
   * @return string
   */
  public function getPolicyDescription()
  {
    return $this->policyDescription;
  }
  /**
   * @param string
   */
  public function setSchemaName($schemaName)
  {
    $this->schemaName = $schemaName;
  }
  /**
   * @return string
   */
  public function getSchemaName()
  {
    return $this->schemaName;
  }
  /**
   * @param string
   */
  public function setSupportUri($supportUri)
  {
    $this->supportUri = $supportUri;
  }
  /**
   * @return string
   */
  public function getSupportUri()
  {
    return $this->supportUri;
  }
  /**
   * @param string[]
   */
  public function setSupportedPlatforms($supportedPlatforms)
  {
    $this->supportedPlatforms = $supportedPlatforms;
  }
  /**
   * @return string[]
   */
  public function getSupportedPlatforms()
  {
    return $this->supportedPlatforms;
  }
  /**
   * @param string[]
   */
  public function setValidTargetResources($validTargetResources)
  {
    $this->validTargetResources = $validTargetResources;
  }
  /**
   * @return string[]
   */
  public function getValidTargetResources()
  {
    return $this->validTargetResources;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleChromePolicyVersionsV1PolicySchema::class, 'Google_Service_ChromePolicy_GoogleChromePolicyVersionsV1PolicySchema');
