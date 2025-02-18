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

namespace Google\Service\BigtableAdmin;

class TableProgress extends \Google\Model
{
  /**
   * @var string
   */
  public $estimatedCopiedBytes;
  /**
   * @var string
   */
  public $estimatedSizeBytes;
  /**
   * @var string
   */
  public $state;

  /**
   * @param string
   */
  public function setEstimatedCopiedBytes($estimatedCopiedBytes)
  {
    $this->estimatedCopiedBytes = $estimatedCopiedBytes;
  }
  /**
   * @return string
   */
  public function getEstimatedCopiedBytes()
  {
    return $this->estimatedCopiedBytes;
  }
  /**
   * @param string
   */
  public function setEstimatedSizeBytes($estimatedSizeBytes)
  {
    $this->estimatedSizeBytes = $estimatedSizeBytes;
  }
  /**
   * @return string
   */
  public function getEstimatedSizeBytes()
  {
    return $this->estimatedSizeBytes;
  }
  /**
   * @param string
   */
  public function setState($state)
  {
    $this->state = $state;
  }
  /**
   * @return string
   */
  public function getState()
  {
    return $this->state;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(TableProgress::class, 'Google_Service_BigtableAdmin_TableProgress');
