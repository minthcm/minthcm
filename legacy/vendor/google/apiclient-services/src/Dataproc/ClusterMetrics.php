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

namespace Google\Service\Dataproc;

class ClusterMetrics extends \Google\Model
{
  /**
   * @var string[]
   */
  public $hdfsMetrics;
  /**
   * @var string[]
   */
  public $yarnMetrics;

  /**
   * @param string[]
   */
  public function setHdfsMetrics($hdfsMetrics)
  {
    $this->hdfsMetrics = $hdfsMetrics;
  }
  /**
   * @return string[]
   */
  public function getHdfsMetrics()
  {
    return $this->hdfsMetrics;
  }
  /**
   * @param string[]
   */
  public function setYarnMetrics($yarnMetrics)
  {
    $this->yarnMetrics = $yarnMetrics;
  }
  /**
   * @return string[]
   */
  public function getYarnMetrics()
  {
    return $this->yarnMetrics;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ClusterMetrics::class, 'Google_Service_Dataproc_ClusterMetrics');
