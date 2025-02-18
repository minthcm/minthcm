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

class JobStatistics5 extends \Google\Model
{
  /**
   * @var string
   */
  public $copiedLogicalBytes;
  /**
   * @var string
   */
  public $copiedRows;

  /**
   * @param string
   */
  public function setCopiedLogicalBytes($copiedLogicalBytes)
  {
    $this->copiedLogicalBytes = $copiedLogicalBytes;
  }
  /**
   * @return string
   */
  public function getCopiedLogicalBytes()
  {
    return $this->copiedLogicalBytes;
  }
  /**
   * @param string
   */
  public function setCopiedRows($copiedRows)
  {
    $this->copiedRows = $copiedRows;
  }
  /**
   * @return string
   */
  public function getCopiedRows()
  {
    return $this->copiedRows;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(JobStatistics5::class, 'Google_Service_Bigquery_JobStatistics5');
