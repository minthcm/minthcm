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

namespace Google\Service\DataLabeling;

class GoogleCloudDatalabelingV1beta1ExampleComparison extends \Google\Collection
{
  protected $collection_key = 'modelCreatedExamples';
  protected $groundTruthExampleType = GoogleCloudDatalabelingV1beta1Example::class;
  protected $groundTruthExampleDataType = '';
  protected $modelCreatedExamplesType = GoogleCloudDatalabelingV1beta1Example::class;
  protected $modelCreatedExamplesDataType = 'array';

  /**
   * @param GoogleCloudDatalabelingV1beta1Example
   */
  public function setGroundTruthExample(GoogleCloudDatalabelingV1beta1Example $groundTruthExample)
  {
    $this->groundTruthExample = $groundTruthExample;
  }
  /**
   * @return GoogleCloudDatalabelingV1beta1Example
   */
  public function getGroundTruthExample()
  {
    return $this->groundTruthExample;
  }
  /**
   * @param GoogleCloudDatalabelingV1beta1Example[]
   */
  public function setModelCreatedExamples($modelCreatedExamples)
  {
    $this->modelCreatedExamples = $modelCreatedExamples;
  }
  /**
   * @return GoogleCloudDatalabelingV1beta1Example[]
   */
  public function getModelCreatedExamples()
  {
    return $this->modelCreatedExamples;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDatalabelingV1beta1ExampleComparison::class, 'Google_Service_DataLabeling_GoogleCloudDatalabelingV1beta1ExampleComparison');
