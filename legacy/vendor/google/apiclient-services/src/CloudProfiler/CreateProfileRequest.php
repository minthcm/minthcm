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

namespace Google\Service\CloudProfiler;

class CreateProfileRequest extends \Google\Collection
{
  protected $collection_key = 'profileType';
  protected $deploymentType = Deployment::class;
  protected $deploymentDataType = '';
  /**
   * @var string[]
   */
  public $profileType;

  /**
   * @param Deployment
   */
  public function setDeployment(Deployment $deployment)
  {
    $this->deployment = $deployment;
  }
  /**
   * @return Deployment
   */
  public function getDeployment()
  {
    return $this->deployment;
  }
  /**
   * @param string[]
   */
  public function setProfileType($profileType)
  {
    $this->profileType = $profileType;
  }
  /**
   * @return string[]
   */
  public function getProfileType()
  {
    return $this->profileType;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CreateProfileRequest::class, 'Google_Service_CloudProfiler_CreateProfileRequest');
