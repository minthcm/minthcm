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

namespace Google\Service\CloudHealthcare;

class DicomStore extends \Google\Collection
{
  protected $collection_key = 'streamConfigs';
  /**
   * @var string[]
   */
  public $labels;
  /**
   * @var string
   */
  public $name;
  protected $notificationConfigType = NotificationConfig::class;
  protected $notificationConfigDataType = '';
  protected $streamConfigsType = GoogleCloudHealthcareV1DicomStreamConfig::class;
  protected $streamConfigsDataType = 'array';

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
   * @param NotificationConfig
   */
  public function setNotificationConfig(NotificationConfig $notificationConfig)
  {
    $this->notificationConfig = $notificationConfig;
  }
  /**
   * @return NotificationConfig
   */
  public function getNotificationConfig()
  {
    return $this->notificationConfig;
  }
  /**
   * @param GoogleCloudHealthcareV1DicomStreamConfig[]
   */
  public function setStreamConfigs($streamConfigs)
  {
    $this->streamConfigs = $streamConfigs;
  }
  /**
   * @return GoogleCloudHealthcareV1DicomStreamConfig[]
   */
  public function getStreamConfigs()
  {
    return $this->streamConfigs;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DicomStore::class, 'Google_Service_CloudHealthcare_DicomStore');
