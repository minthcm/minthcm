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

namespace Google\Service\PolicySimulator;

class GoogleCloudOrgpolicyV2PolicySpec extends \Google\Collection
{
  protected $collection_key = 'rules';
  /**
   * @var string
   */
  public $etag;
  /**
   * @var bool
   */
  public $inheritFromParent;
  /**
   * @var bool
   */
  public $reset;
  protected $rulesType = GoogleCloudOrgpolicyV2PolicySpecPolicyRule::class;
  protected $rulesDataType = 'array';
  /**
   * @var string
   */
  public $updateTime;

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
   * @param bool
   */
  public function setInheritFromParent($inheritFromParent)
  {
    $this->inheritFromParent = $inheritFromParent;
  }
  /**
   * @return bool
   */
  public function getInheritFromParent()
  {
    return $this->inheritFromParent;
  }
  /**
   * @param bool
   */
  public function setReset($reset)
  {
    $this->reset = $reset;
  }
  /**
   * @return bool
   */
  public function getReset()
  {
    return $this->reset;
  }
  /**
   * @param GoogleCloudOrgpolicyV2PolicySpecPolicyRule[]
   */
  public function setRules($rules)
  {
    $this->rules = $rules;
  }
  /**
   * @return GoogleCloudOrgpolicyV2PolicySpecPolicyRule[]
   */
  public function getRules()
  {
    return $this->rules;
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
class_alias(GoogleCloudOrgpolicyV2PolicySpec::class, 'Google_Service_PolicySimulator_GoogleCloudOrgpolicyV2PolicySpec');
