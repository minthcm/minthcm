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

namespace Google\Service\Document;

class GoogleCloudDocumentaiV1EvaluationReference extends \Google\Model
{
  protected $aggregateMetricsType = GoogleCloudDocumentaiV1EvaluationMetrics::class;
  protected $aggregateMetricsDataType = '';
  protected $aggregateMetricsExactType = GoogleCloudDocumentaiV1EvaluationMetrics::class;
  protected $aggregateMetricsExactDataType = '';
  /**
   * @var string
   */
  public $evaluation;
  /**
   * @var string
   */
  public $operation;

  /**
   * @param GoogleCloudDocumentaiV1EvaluationMetrics
   */
  public function setAggregateMetrics(GoogleCloudDocumentaiV1EvaluationMetrics $aggregateMetrics)
  {
    $this->aggregateMetrics = $aggregateMetrics;
  }
  /**
   * @return GoogleCloudDocumentaiV1EvaluationMetrics
   */
  public function getAggregateMetrics()
  {
    return $this->aggregateMetrics;
  }
  /**
   * @param GoogleCloudDocumentaiV1EvaluationMetrics
   */
  public function setAggregateMetricsExact(GoogleCloudDocumentaiV1EvaluationMetrics $aggregateMetricsExact)
  {
    $this->aggregateMetricsExact = $aggregateMetricsExact;
  }
  /**
   * @return GoogleCloudDocumentaiV1EvaluationMetrics
   */
  public function getAggregateMetricsExact()
  {
    return $this->aggregateMetricsExact;
  }
  /**
   * @param string
   */
  public function setEvaluation($evaluation)
  {
    $this->evaluation = $evaluation;
  }
  /**
   * @return string
   */
  public function getEvaluation()
  {
    return $this->evaluation;
  }
  /**
   * @param string
   */
  public function setOperation($operation)
  {
    $this->operation = $operation;
  }
  /**
   * @return string
   */
  public function getOperation()
  {
    return $this->operation;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDocumentaiV1EvaluationReference::class, 'Google_Service_Document_GoogleCloudDocumentaiV1EvaluationReference');
