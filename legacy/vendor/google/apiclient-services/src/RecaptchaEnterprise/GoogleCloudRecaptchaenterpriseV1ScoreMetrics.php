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

namespace Google\Service\RecaptchaEnterprise;

class GoogleCloudRecaptchaenterpriseV1ScoreMetrics extends \Google\Model
{
  protected $actionMetricsType = GoogleCloudRecaptchaenterpriseV1ScoreDistribution::class;
  protected $actionMetricsDataType = 'map';
  protected $overallMetricsType = GoogleCloudRecaptchaenterpriseV1ScoreDistribution::class;
  protected $overallMetricsDataType = '';

  /**
   * @param GoogleCloudRecaptchaenterpriseV1ScoreDistribution[]
   */
  public function setActionMetrics($actionMetrics)
  {
    $this->actionMetrics = $actionMetrics;
  }
  /**
   * @return GoogleCloudRecaptchaenterpriseV1ScoreDistribution[]
   */
  public function getActionMetrics()
  {
    return $this->actionMetrics;
  }
  /**
   * @param GoogleCloudRecaptchaenterpriseV1ScoreDistribution
   */
  public function setOverallMetrics(GoogleCloudRecaptchaenterpriseV1ScoreDistribution $overallMetrics)
  {
    $this->overallMetrics = $overallMetrics;
  }
  /**
   * @return GoogleCloudRecaptchaenterpriseV1ScoreDistribution
   */
  public function getOverallMetrics()
  {
    return $this->overallMetrics;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudRecaptchaenterpriseV1ScoreMetrics::class, 'Google_Service_RecaptchaEnterprise_GoogleCloudRecaptchaenterpriseV1ScoreMetrics');
