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

namespace Google\Service\Webfonts;

class Axis extends \Google\Model
{
  /**
   * @var float
   */
  public $end;
  /**
   * @var float
   */
  public $start;
  /**
   * @var string
   */
  public $tag;

  /**
   * @param float
   */
  public function setEnd($end)
  {
    $this->end = $end;
  }
  /**
   * @return float
   */
  public function getEnd()
  {
    return $this->end;
  }
  /**
   * @param float
   */
  public function setStart($start)
  {
    $this->start = $start;
  }
  /**
   * @return float
   */
  public function getStart()
  {
    return $this->start;
  }
  /**
   * @param string
   */
  public function setTag($tag)
  {
    $this->tag = $tag;
  }
  /**
   * @return string
   */
  public function getTag()
  {
    return $this->tag;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Axis::class, 'Google_Service_Webfonts_Axis');
