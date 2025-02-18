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

namespace Google\Service\PolicyTroubleshooter;

class GoogleCloudPolicytroubleshooterV1ExplainedPolicy extends \Google\Collection
{
  protected $collection_key = 'bindingExplanations';
  /**
   * @var string
   */
  public $access;
  protected $bindingExplanationsType = GoogleCloudPolicytroubleshooterV1BindingExplanation::class;
  protected $bindingExplanationsDataType = 'array';
  /**
   * @var string
   */
  public $fullResourceName;
  protected $policyType = GoogleIamV1Policy::class;
  protected $policyDataType = '';
  /**
   * @var string
   */
  public $relevance;

  /**
   * @param string
   */
  public function setAccess($access)
  {
    $this->access = $access;
  }
  /**
   * @return string
   */
  public function getAccess()
  {
    return $this->access;
  }
  /**
   * @param GoogleCloudPolicytroubleshooterV1BindingExplanation[]
   */
  public function setBindingExplanations($bindingExplanations)
  {
    $this->bindingExplanations = $bindingExplanations;
  }
  /**
   * @return GoogleCloudPolicytroubleshooterV1BindingExplanation[]
   */
  public function getBindingExplanations()
  {
    return $this->bindingExplanations;
  }
  /**
   * @param string
   */
  public function setFullResourceName($fullResourceName)
  {
    $this->fullResourceName = $fullResourceName;
  }
  /**
   * @return string
   */
  public function getFullResourceName()
  {
    return $this->fullResourceName;
  }
  /**
   * @param GoogleIamV1Policy
   */
  public function setPolicy(GoogleIamV1Policy $policy)
  {
    $this->policy = $policy;
  }
  /**
   * @return GoogleIamV1Policy
   */
  public function getPolicy()
  {
    return $this->policy;
  }
  /**
   * @param string
   */
  public function setRelevance($relevance)
  {
    $this->relevance = $relevance;
  }
  /**
   * @return string
   */
  public function getRelevance()
  {
    return $this->relevance;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudPolicytroubleshooterV1ExplainedPolicy::class, 'Google_Service_PolicyTroubleshooter_GoogleCloudPolicytroubleshooterV1ExplainedPolicy');
