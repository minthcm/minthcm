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

namespace Google\Service\Compute;

class HttpHeaderOption extends \Google\Model
{
  /**
   * @var string
   */
  public $headerName;
  /**
   * @var string
   */
  public $headerValue;
  /**
   * @var bool
   */
  public $replace;

  /**
   * @param string
   */
  public function setHeaderName($headerName)
  {
    $this->headerName = $headerName;
  }
  /**
   * @return string
   */
  public function getHeaderName()
  {
    return $this->headerName;
  }
  /**
   * @param string
   */
  public function setHeaderValue($headerValue)
  {
    $this->headerValue = $headerValue;
  }
  /**
   * @return string
   */
  public function getHeaderValue()
  {
    return $this->headerValue;
  }
  /**
   * @param bool
   */
  public function setReplace($replace)
  {
    $this->replace = $replace;
  }
  /**
   * @return bool
   */
  public function getReplace()
  {
    return $this->replace;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(HttpHeaderOption::class, 'Google_Service_Compute_HttpHeaderOption');
