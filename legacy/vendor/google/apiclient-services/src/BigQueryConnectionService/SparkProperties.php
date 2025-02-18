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

namespace Google\Service\BigQueryConnectionService;

class SparkProperties extends \Google\Model
{
  protected $metastoreServiceConfigType = MetastoreServiceConfig::class;
  protected $metastoreServiceConfigDataType = '';
  /**
   * @var string
   */
  public $serviceAccountId;
  protected $sparkHistoryServerConfigType = SparkHistoryServerConfig::class;
  protected $sparkHistoryServerConfigDataType = '';

  /**
   * @param MetastoreServiceConfig
   */
  public function setMetastoreServiceConfig(MetastoreServiceConfig $metastoreServiceConfig)
  {
    $this->metastoreServiceConfig = $metastoreServiceConfig;
  }
  /**
   * @return MetastoreServiceConfig
   */
  public function getMetastoreServiceConfig()
  {
    return $this->metastoreServiceConfig;
  }
  /**
   * @param string
   */
  public function setServiceAccountId($serviceAccountId)
  {
    $this->serviceAccountId = $serviceAccountId;
  }
  /**
   * @return string
   */
  public function getServiceAccountId()
  {
    return $this->serviceAccountId;
  }
  /**
   * @param SparkHistoryServerConfig
   */
  public function setSparkHistoryServerConfig(SparkHistoryServerConfig $sparkHistoryServerConfig)
  {
    $this->sparkHistoryServerConfig = $sparkHistoryServerConfig;
  }
  /**
   * @return SparkHistoryServerConfig
   */
  public function getSparkHistoryServerConfig()
  {
    return $this->sparkHistoryServerConfig;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SparkProperties::class, 'Google_Service_BigQueryConnectionService_SparkProperties');
