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

namespace Google\Service\CloudBuild;

class RunBuildTriggerRequest extends \Google\Model
{
  /**
   * @var string
   */
  public $projectId;
  protected $sourceType = RepoSource::class;
  protected $sourceDataType = '';
  /**
   * @var string
   */
  public $triggerId;

  /**
   * @param string
   */
  public function setProjectId($projectId)
  {
    $this->projectId = $projectId;
  }
  /**
   * @return string
   */
  public function getProjectId()
  {
    return $this->projectId;
  }
  /**
   * @param RepoSource
   */
  public function setSource(RepoSource $source)
  {
    $this->source = $source;
  }
  /**
   * @return RepoSource
   */
  public function getSource()
  {
    return $this->source;
  }
  /**
   * @param string
   */
  public function setTriggerId($triggerId)
  {
    $this->triggerId = $triggerId;
  }
  /**
   * @return string
   */
  public function getTriggerId()
  {
    return $this->triggerId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(RunBuildTriggerRequest::class, 'Google_Service_CloudBuild_RunBuildTriggerRequest');
