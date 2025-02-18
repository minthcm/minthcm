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

namespace Google\Service\CloudDeploy;

class CloudRunMetadata extends \Google\Collection
{
  protected $collection_key = 'serviceUrls';
  /**
   * @var string
   */
  public $job;
  /**
   * @var string
   */
  public $revision;
  /**
   * @var string
   */
  public $service;
  /**
   * @var string[]
   */
  public $serviceUrls;

  /**
   * @param string
   */
  public function setJob($job)
  {
    $this->job = $job;
  }
  /**
   * @return string
   */
  public function getJob()
  {
    return $this->job;
  }
  /**
   * @param string
   */
  public function setRevision($revision)
  {
    $this->revision = $revision;
  }
  /**
   * @return string
   */
  public function getRevision()
  {
    return $this->revision;
  }
  /**
   * @param string
   */
  public function setService($service)
  {
    $this->service = $service;
  }
  /**
   * @return string
   */
  public function getService()
  {
    return $this->service;
  }
  /**
   * @param string[]
   */
  public function setServiceUrls($serviceUrls)
  {
    $this->serviceUrls = $serviceUrls;
  }
  /**
   * @return string[]
   */
  public function getServiceUrls()
  {
    return $this->serviceUrls;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CloudRunMetadata::class, 'Google_Service_CloudDeploy_CloudRunMetadata');
