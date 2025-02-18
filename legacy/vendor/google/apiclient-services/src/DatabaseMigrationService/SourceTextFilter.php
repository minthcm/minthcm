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

namespace Google\Service\DatabaseMigrationService;

class SourceTextFilter extends \Google\Model
{
  /**
   * @var string
   */
  public $sourceMaxLengthFilter;
  /**
   * @var string
   */
  public $sourceMinLengthFilter;

  /**
   * @param string
   */
  public function setSourceMaxLengthFilter($sourceMaxLengthFilter)
  {
    $this->sourceMaxLengthFilter = $sourceMaxLengthFilter;
  }
  /**
   * @return string
   */
  public function getSourceMaxLengthFilter()
  {
    return $this->sourceMaxLengthFilter;
  }
  /**
   * @param string
   */
  public function setSourceMinLengthFilter($sourceMinLengthFilter)
  {
    $this->sourceMinLengthFilter = $sourceMinLengthFilter;
  }
  /**
   * @return string
   */
  public function getSourceMinLengthFilter()
  {
    return $this->sourceMinLengthFilter;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SourceTextFilter::class, 'Google_Service_DatabaseMigrationService_SourceTextFilter');
