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

namespace Google\Service\Compute;

class SecurityPolicyAdaptiveProtectionConfigLayer7DdosDefenseConfig extends \Google\Collection
{
  protected $collection_key = 'thresholdConfigs';
  /**
   * @var bool
   */
  public $enable;
  /**
   * @var string
   */
  public $ruleVisibility;
  protected $thresholdConfigsType = SecurityPolicyAdaptiveProtectionConfigLayer7DdosDefenseConfigThresholdConfig::class;
  protected $thresholdConfigsDataType = 'array';

  /**
   * @param bool
   */
  public function setEnable($enable)
  {
    $this->enable = $enable;
  }
  /**
   * @return bool
   */
  public function getEnable()
  {
    return $this->enable;
  }
  /**
   * @param string
   */
  public function setRuleVisibility($ruleVisibility)
  {
    $this->ruleVisibility = $ruleVisibility;
  }
  /**
   * @return string
   */
  public function getRuleVisibility()
  {
    return $this->ruleVisibility;
  }
  /**
   * @param SecurityPolicyAdaptiveProtectionConfigLayer7DdosDefenseConfigThresholdConfig[]
   */
  public function setThresholdConfigs($thresholdConfigs)
  {
    $this->thresholdConfigs = $thresholdConfigs;
  }
  /**
   * @return SecurityPolicyAdaptiveProtectionConfigLayer7DdosDefenseConfigThresholdConfig[]
   */
  public function getThresholdConfigs()
  {
    return $this->thresholdConfigs;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SecurityPolicyAdaptiveProtectionConfigLayer7DdosDefenseConfig::class, 'Google_Service_Compute_SecurityPolicyAdaptiveProtectionConfigLayer7DdosDefenseConfig');
