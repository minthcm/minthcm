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

class TextOperatorOptions extends \Google\Model
{
  /**
   * @var bool
   */
  public $exactMatchWithOperator;
  /**
   * @var string
   */
  public $operatorName;

  /**
   * @param bool
   */
  public function setExactMatchWithOperator($exactMatchWithOperator)
  {
    $this->exactMatchWithOperator = $exactMatchWithOperator;
  }
  /**
   * @return bool
   */
  public function getExactMatchWithOperator()
  {
    return $this->exactMatchWithOperator;
  }
  /**
   * @param string
   */
  public function setOperatorName($operatorName)
  {
    $this->operatorName = $operatorName;
  }
  /**
   * @return string
   */
  public function getOperatorName()
  {
    return $this->operatorName;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(TextOperatorOptions::class, 'Google_Service_CloudSearch_TextOperatorOptions');
