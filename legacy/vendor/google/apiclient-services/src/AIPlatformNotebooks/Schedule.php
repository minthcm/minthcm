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

namespace Google\Service\AIPlatformNotebooks;

class Schedule extends \Google\Collection
{
  protected $collection_key = 'recentExecutions';
  /**
   * @var string
   */
  public $createTime;
  /**
   * @var string
   */
  public $cronSchedule;
  /**
   * @var string
   */
  public $description;
  /**
   * @var string
   */
  public $displayName;
  protected $executionTemplateType = ExecutionTemplate::class;
  protected $executionTemplateDataType = '';
  public $executionTemplate;
  /**
   * @var string
   */
  public $name;
  protected $recentExecutionsType = Execution::class;
  protected $recentExecutionsDataType = 'array';
  public $recentExecutions = [];
  /**
   * @var string
   */
  public $state;
  /**
   * @var string
   */
  public $timeZone;
  /**
   * @var string
   */
  public $updateTime;

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
  public function setCronSchedule($cronSchedule)
  {
    $this->cronSchedule = $cronSchedule;
  }
  /**
   * @return string
   */
  public function getCronSchedule()
  {
    return $this->cronSchedule;
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
   * @param ExecutionTemplate
   */
  public function setExecutionTemplate(ExecutionTemplate $executionTemplate)
  {
    $this->executionTemplate = $executionTemplate;
  }
  /**
   * @return ExecutionTemplate
   */
  public function getExecutionTemplate()
  {
    return $this->executionTemplate;
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
   * @param Execution[]
   */
  public function setRecentExecutions($recentExecutions)
  {
    $this->recentExecutions = $recentExecutions;
  }
  /**
   * @return Execution[]
   */
  public function getRecentExecutions()
  {
    return $this->recentExecutions;
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
  public function setTimeZone($timeZone)
  {
    $this->timeZone = $timeZone;
  }
  /**
   * @return string
   */
  public function getTimeZone()
  {
    return $this->timeZone;
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
class_alias(Schedule::class, 'Google_Service_AIPlatformNotebooks_Schedule');
