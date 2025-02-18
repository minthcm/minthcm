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

namespace Google\Service\DLP;

class GooglePrivacyDlpV2CategoricalStatsHistogramBucket extends \Google\Collection
{
  protected $collection_key = 'bucketValues';
  /**
   * @var string
   */
  public $bucketSize;
  /**
   * @var string
   */
  public $bucketValueCount;
  protected $bucketValuesType = GooglePrivacyDlpV2ValueFrequency::class;
  protected $bucketValuesDataType = 'array';
  /**
   * @var string
   */
  public $valueFrequencyLowerBound;
  /**
   * @var string
   */
  public $valueFrequencyUpperBound;

  /**
   * @param string
   */
  public function setBucketSize($bucketSize)
  {
    $this->bucketSize = $bucketSize;
  }
  /**
   * @return string
   */
  public function getBucketSize()
  {
    return $this->bucketSize;
  }
  /**
   * @param string
   */
  public function setBucketValueCount($bucketValueCount)
  {
    $this->bucketValueCount = $bucketValueCount;
  }
  /**
   * @return string
   */
  public function getBucketValueCount()
  {
    return $this->bucketValueCount;
  }
  /**
   * @param GooglePrivacyDlpV2ValueFrequency[]
   */
  public function setBucketValues($bucketValues)
  {
    $this->bucketValues = $bucketValues;
  }
  /**
   * @return GooglePrivacyDlpV2ValueFrequency[]
   */
  public function getBucketValues()
  {
    return $this->bucketValues;
  }
  /**
   * @param string
   */
  public function setValueFrequencyLowerBound($valueFrequencyLowerBound)
  {
    $this->valueFrequencyLowerBound = $valueFrequencyLowerBound;
  }
  /**
   * @return string
   */
  public function getValueFrequencyLowerBound()
  {
    return $this->valueFrequencyLowerBound;
  }
  /**
   * @param string
   */
  public function setValueFrequencyUpperBound($valueFrequencyUpperBound)
  {
    $this->valueFrequencyUpperBound = $valueFrequencyUpperBound;
  }
  /**
   * @return string
   */
  public function getValueFrequencyUpperBound()
  {
    return $this->valueFrequencyUpperBound;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GooglePrivacyDlpV2CategoricalStatsHistogramBucket::class, 'Google_Service_DLP_GooglePrivacyDlpV2CategoricalStatsHistogramBucket');
