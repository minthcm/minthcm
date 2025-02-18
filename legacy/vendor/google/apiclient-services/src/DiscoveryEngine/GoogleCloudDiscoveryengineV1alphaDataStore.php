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

namespace Google\Service\DiscoveryEngine;

class GoogleCloudDiscoveryengineV1alphaDataStore extends \Google\Collection
{
  protected $collection_key = 'solutionTypes';
  /**
   * @var bool
   */
  public $aclEnabled;
  /**
   * @var string
   */
  public $contentConfig;
  /**
   * @var string
   */
  public $createTime;
  /**
   * @var string
   */
  public $defaultSchemaId;
  /**
   * @var string
   */
  public $displayName;
  protected $documentProcessingConfigType = GoogleCloudDiscoveryengineV1alphaDocumentProcessingConfig::class;
  protected $documentProcessingConfigDataType = '';
  protected $idpConfigType = GoogleCloudDiscoveryengineV1alphaIdpConfig::class;
  protected $idpConfigDataType = '';
  /**
   * @var string
   */
  public $industryVertical;
  protected $languageInfoType = GoogleCloudDiscoveryengineV1alphaLanguageInfo::class;
  protected $languageInfoDataType = '';
  /**
   * @var string
   */
  public $name;
  /**
   * @var string[]
   */
  public $solutionTypes;
  protected $startingSchemaType = GoogleCloudDiscoveryengineV1alphaSchema::class;
  protected $startingSchemaDataType = '';

  /**
   * @param bool
   */
  public function setAclEnabled($aclEnabled)
  {
    $this->aclEnabled = $aclEnabled;
  }
  /**
   * @return bool
   */
  public function getAclEnabled()
  {
    return $this->aclEnabled;
  }
  /**
   * @param string
   */
  public function setContentConfig($contentConfig)
  {
    $this->contentConfig = $contentConfig;
  }
  /**
   * @return string
   */
  public function getContentConfig()
  {
    return $this->contentConfig;
  }
  /**
   * @param string
   */
  public function setCreateTime($createTime)
  {
    $this->createTime = $createTime;
  }
  /**
   * @return string
   */
  public function getCreateTime()
  {
    return $this->createTime;
  }
  /**
   * @param string
   */
  public function setDefaultSchemaId($defaultSchemaId)
  {
    $this->defaultSchemaId = $defaultSchemaId;
  }
  /**
   * @return string
   */
  public function getDefaultSchemaId()
  {
    return $this->defaultSchemaId;
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
   * @param GoogleCloudDiscoveryengineV1alphaDocumentProcessingConfig
   */
  public function setDocumentProcessingConfig(GoogleCloudDiscoveryengineV1alphaDocumentProcessingConfig $documentProcessingConfig)
  {
    $this->documentProcessingConfig = $documentProcessingConfig;
  }
  /**
   * @return GoogleCloudDiscoveryengineV1alphaDocumentProcessingConfig
   */
  public function getDocumentProcessingConfig()
  {
    return $this->documentProcessingConfig;
  }
  /**
   * @param GoogleCloudDiscoveryengineV1alphaIdpConfig
   */
  public function setIdpConfig(GoogleCloudDiscoveryengineV1alphaIdpConfig $idpConfig)
  {
    $this->idpConfig = $idpConfig;
  }
  /**
   * @return GoogleCloudDiscoveryengineV1alphaIdpConfig
   */
  public function getIdpConfig()
  {
    return $this->idpConfig;
  }
  /**
   * @param string
   */
  public function setIndustryVertical($industryVertical)
  {
    $this->industryVertical = $industryVertical;
  }
  /**
   * @return string
   */
  public function getIndustryVertical()
  {
    return $this->industryVertical;
  }
  /**
   * @param GoogleCloudDiscoveryengineV1alphaLanguageInfo
   */
  public function setLanguageInfo(GoogleCloudDiscoveryengineV1alphaLanguageInfo $languageInfo)
  {
    $this->languageInfo = $languageInfo;
  }
  /**
   * @return GoogleCloudDiscoveryengineV1alphaLanguageInfo
   */
  public function getLanguageInfo()
  {
    return $this->languageInfo;
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
   * @param string[]
   */
  public function setSolutionTypes($solutionTypes)
  {
    $this->solutionTypes = $solutionTypes;
  }
  /**
   * @return string[]
   */
  public function getSolutionTypes()
  {
    return $this->solutionTypes;
  }
  /**
   * @param GoogleCloudDiscoveryengineV1alphaSchema
   */
  public function setStartingSchema(GoogleCloudDiscoveryengineV1alphaSchema $startingSchema)
  {
    $this->startingSchema = $startingSchema;
  }
  /**
   * @return GoogleCloudDiscoveryengineV1alphaSchema
   */
  public function getStartingSchema()
  {
    return $this->startingSchema;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDiscoveryengineV1alphaDataStore::class, 'Google_Service_DiscoveryEngine_GoogleCloudDiscoveryengineV1alphaDataStore');
