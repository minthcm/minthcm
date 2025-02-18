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

namespace Google\Service\BinaryAuthorization;

class Policy extends \Google\Collection
{
  protected $collection_key = 'admissionWhitelistPatterns';
  protected $admissionWhitelistPatternsType = AdmissionWhitelistPattern::class;
  protected $admissionWhitelistPatternsDataType = 'array';
  protected $clusterAdmissionRulesType = AdmissionRule::class;
  protected $clusterAdmissionRulesDataType = 'map';
  protected $defaultAdmissionRuleType = AdmissionRule::class;
  protected $defaultAdmissionRuleDataType = '';
  /**
   * @var string
   */
  public $description;
  /**
   * @var string
   */
  public $etag;
  /**
   * @var string
   */
  public $globalPolicyEvaluationMode;
  protected $istioServiceIdentityAdmissionRulesType = AdmissionRule::class;
  protected $istioServiceIdentityAdmissionRulesDataType = 'map';
  protected $kubernetesNamespaceAdmissionRulesType = AdmissionRule::class;
  protected $kubernetesNamespaceAdmissionRulesDataType = 'map';
  protected $kubernetesServiceAccountAdmissionRulesType = AdmissionRule::class;
  protected $kubernetesServiceAccountAdmissionRulesDataType = 'map';
  /**
   * @var string
   */
  public $name;
  /**
   * @var string
   */
  public $updateTime;

  /**
   * @param AdmissionWhitelistPattern[]
   */
  public function setAdmissionWhitelistPatterns($admissionWhitelistPatterns)
  {
    $this->admissionWhitelistPatterns = $admissionWhitelistPatterns;
  }
  /**
   * @return AdmissionWhitelistPattern[]
   */
  public function getAdmissionWhitelistPatterns()
  {
    return $this->admissionWhitelistPatterns;
  }
  /**
   * @param AdmissionRule[]
   */
  public function setClusterAdmissionRules($clusterAdmissionRules)
  {
    $this->clusterAdmissionRules = $clusterAdmissionRules;
  }
  /**
   * @return AdmissionRule[]
   */
  public function getClusterAdmissionRules()
  {
    return $this->clusterAdmissionRules;
  }
  /**
   * @param AdmissionRule
   */
  public function setDefaultAdmissionRule(AdmissionRule $defaultAdmissionRule)
  {
    $this->defaultAdmissionRule = $defaultAdmissionRule;
  }
  /**
   * @return AdmissionRule
   */
  public function getDefaultAdmissionRule()
  {
    return $this->defaultAdmissionRule;
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
   * @param string
   */
  public function setGlobalPolicyEvaluationMode($globalPolicyEvaluationMode)
  {
    $this->globalPolicyEvaluationMode = $globalPolicyEvaluationMode;
  }
  /**
   * @return string
   */
  public function getGlobalPolicyEvaluationMode()
  {
    return $this->globalPolicyEvaluationMode;
  }
  /**
   * @param AdmissionRule[]
   */
  public function setIstioServiceIdentityAdmissionRules($istioServiceIdentityAdmissionRules)
  {
    $this->istioServiceIdentityAdmissionRules = $istioServiceIdentityAdmissionRules;
  }
  /**
   * @return AdmissionRule[]
   */
  public function getIstioServiceIdentityAdmissionRules()
  {
    return $this->istioServiceIdentityAdmissionRules;
  }
  /**
   * @param AdmissionRule[]
   */
  public function setKubernetesNamespaceAdmissionRules($kubernetesNamespaceAdmissionRules)
  {
    $this->kubernetesNamespaceAdmissionRules = $kubernetesNamespaceAdmissionRules;
  }
  /**
   * @return AdmissionRule[]
   */
  public function getKubernetesNamespaceAdmissionRules()
  {
    return $this->kubernetesNamespaceAdmissionRules;
  }
  /**
   * @param AdmissionRule[]
   */
  public function setKubernetesServiceAccountAdmissionRules($kubernetesServiceAccountAdmissionRules)
  {
    $this->kubernetesServiceAccountAdmissionRules = $kubernetesServiceAccountAdmissionRules;
  }
  /**
   * @return AdmissionRule[]
   */
  public function getKubernetesServiceAccountAdmissionRules()
  {
    return $this->kubernetesServiceAccountAdmissionRules;
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
class_alias(Policy::class, 'Google_Service_BinaryAuthorization_Policy');
