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

namespace Google\Service\Bigquery;

class PrincipalComponentInfo extends \Google\Model
{
  public $cumulativeExplainedVarianceRatio;
  public $explainedVariance;
  public $explainedVarianceRatio;
  /**
   * @var string
   */
  public $principalComponentId;

  public function setCumulativeExplainedVarianceRatio($cumulativeExplainedVarianceRatio)
  {
    $this->cumulativeExplainedVarianceRatio = $cumulativeExplainedVarianceRatio;
  }
  public function getCumulativeExplainedVarianceRatio()
  {
    return $this->cumulativeExplainedVarianceRatio;
  }
  public function setExplainedVariance($explainedVariance)
  {
    $this->explainedVariance = $explainedVariance;
  }
  public function getExplainedVariance()
  {
    return $this->explainedVariance;
  }
  public function setExplainedVarianceRatio($explainedVarianceRatio)
  {
    $this->explainedVarianceRatio = $explainedVarianceRatio;
  }
  public function getExplainedVarianceRatio()
  {
    return $this->explainedVarianceRatio;
  }
  /**
   * @param string
   */
  public function setPrincipalComponentId($principalComponentId)
  {
    $this->principalComponentId = $principalComponentId;
  }
  /**
   * @return string
   */
  public function getPrincipalComponentId()
  {
    return $this->principalComponentId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(PrincipalComponentInfo::class, 'Google_Service_Bigquery_PrincipalComponentInfo');
