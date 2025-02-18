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

namespace Google\Service\Aiplatform;

class GoogleCloudAiplatformV1FeatureView extends \Google\Model
{
  protected $bigQuerySourceType = GoogleCloudAiplatformV1FeatureViewBigQuerySource::class;
  protected $bigQuerySourceDataType = '';
  /**
   * @var string
   */
  public $createTime;
  /**
   * @var string
   */
  public $etag;
  protected $featureRegistrySourceType = GoogleCloudAiplatformV1FeatureViewFeatureRegistrySource::class;
  protected $featureRegistrySourceDataType = '';
  protected $indexConfigType = GoogleCloudAiplatformV1FeatureViewIndexConfig::class;
  protected $indexConfigDataType = '';
  /**
   * @var string[]
   */
  public $labels;
  /**
   * @var string
   */
  public $name;
  protected $syncConfigType = GoogleCloudAiplatformV1FeatureViewSyncConfig::class;
  protected $syncConfigDataType = '';
  /**
   * @var string
   */
  public $updateTime;

  /**
   * @param GoogleCloudAiplatformV1FeatureViewBigQuerySource
   */
  public function setBigQuerySource(GoogleCloudAiplatformV1FeatureViewBigQuerySource $bigQuerySource)
  {
    $this->bigQuerySource = $bigQuerySource;
  }
  /**
   * @return GoogleCloudAiplatformV1FeatureViewBigQuerySource
   */
  public function getBigQuerySource()
  {
    return $this->bigQuerySource;
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
  public function setEtag($etag)
  {
    $this->etag = $etag;
  }
  /**
   * @return string
   */
  public function getEtag()
  {
    return $this->etag;
  }
  /**
   * @param GoogleCloudAiplatformV1FeatureViewFeatureRegistrySource
   */
  public function setFeatureRegistrySource(GoogleCloudAiplatformV1FeatureViewFeatureRegistrySource $featureRegistrySource)
  {
    $this->featureRegistrySource = $featureRegistrySource;
  }
  /**
   * @return GoogleCloudAiplatformV1FeatureViewFeatureRegistrySource
   */
  public function getFeatureRegistrySource()
  {
    return $this->featureRegistrySource;
  }
  /**
   * @param GoogleCloudAiplatformV1FeatureViewIndexConfig
   */
  public function setIndexConfig(GoogleCloudAiplatformV1FeatureViewIndexConfig $indexConfig)
  {
    $this->indexConfig = $indexConfig;
  }
  /**
   * @return GoogleCloudAiplatformV1FeatureViewIndexConfig
   */
  public function getIndexConfig()
  {
    return $this->indexConfig;
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
   * @param GoogleCloudAiplatformV1FeatureViewSyncConfig
   */
  public function setSyncConfig(GoogleCloudAiplatformV1FeatureViewSyncConfig $syncConfig)
  {
    $this->syncConfig = $syncConfig;
  }
  /**
   * @return GoogleCloudAiplatformV1FeatureViewSyncConfig
   */
  public function getSyncConfig()
  {
    return $this->syncConfig;
  }
  /**
   * @param string
   */
  public function setUpdateTime($updateTime)
  {
    $this->updateTime = $updateTime;
  }
  /**
   * @return string
   */
  public function getUpdateTime()
  {
    return $this->updateTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAiplatformV1FeatureView::class, 'Google_Service_Aiplatform_GoogleCloudAiplatformV1FeatureView');
