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

namespace Google\Service\CloudComposer;

class DataRetentionConfig extends \Google\Model
{
  protected $airflowMetadataRetentionConfigType = AirflowMetadataRetentionPolicyConfig::class;
  protected $airflowMetadataRetentionConfigDataType = '';
  protected $taskLogsRetentionConfigType = TaskLogsRetentionConfig::class;
  protected $taskLogsRetentionConfigDataType = '';

  /**
   * @param AirflowMetadataRetentionPolicyConfig
   */
  public function setAirflowMetadataRetentionConfig(AirflowMetadataRetentionPolicyConfig $airflowMetadataRetentionConfig)
  {
    $this->airflowMetadataRetentionConfig = $airflowMetadataRetentionConfig;
  }
  /**
   * @return AirflowMetadataRetentionPolicyConfig
   */
  public function getAirflowMetadataRetentionConfig()
  {
    return $this->airflowMetadataRetentionConfig;
  }
  /**
   * @param TaskLogsRetentionConfig
   */
  public function setTaskLogsRetentionConfig(TaskLogsRetentionConfig $taskLogsRetentionConfig)
  {
    $this->taskLogsRetentionConfig = $taskLogsRetentionConfig;
  }
  /**
   * @return TaskLogsRetentionConfig
   */
  public function getTaskLogsRetentionConfig()
  {
    return $this->taskLogsRetentionConfig;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DataRetentionConfig::class, 'Google_Service_CloudComposer_DataRetentionConfig');
