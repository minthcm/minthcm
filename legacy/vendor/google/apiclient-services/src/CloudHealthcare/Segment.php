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

namespace Google\Service\CloudHealthcare;

class Segment extends \Google\Model
{
  /**
   * @var string[]
   */
  public $fields;
  /**
   * @var string
   */
  public $segmentId;
  /**
   * @var string
   */
  public $setId;

  /**
   * @param string[]
   */
  public function setFields($fields)
  {
    $this->fields = $fields;
  }
  /**
   * @return string[]
   */
  public function getFields()
  {
    return $this->fields;
  }
  /**
   * @param string
   */
  public function setSegmentId($segmentId)
  {
    $this->segmentId = $segmentId;
  }
  /**
   * @return string
   */
  public function getSegmentId()
  {
    return $this->segmentId;
  }
  /**
   * @param string
   */
  public function setSetId($setId)
  {
    $this->setId = $setId;
  }
  /**
   * @return string
   */
  public function getSetId()
  {
    return $this->setId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Segment::class, 'Google_Service_CloudHealthcare_Segment');
