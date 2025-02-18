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

namespace Google\Service\CloudSearch;

class QueryInterpretation extends \Google\Model
{
  /**
   * @var string
   */
  public $interpretationType;
  /**
   * @var string
   */
  public $interpretedQuery;
  /**
   * @var string
   */
  public $reason;

  /**
   * @param string
   */
  public function setInterpretationType($interpretationType)
  {
    $this->interpretationType = $interpretationType;
  }
  /**
   * @return string
   */
  public function getInterpretationType()
  {
    return $this->interpretationType;
  }
  /**
   * @param string
   */
  public function setInterpretedQuery($interpretedQuery)
  {
    $this->interpretedQuery = $interpretedQuery;
  }
  /**
   * @return string
   */
  public function getInterpretedQuery()
  {
    return $this->interpretedQuery;
  }
  /**
   * @param string
   */
  public function setReason($reason)
  {
    $this->reason = $reason;
  }
  /**
   * @return string
   */
  public function getReason()
  {
    return $this->reason;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(QueryInterpretation::class, 'Google_Service_CloudSearch_QueryInterpretation');
