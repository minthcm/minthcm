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

namespace Google\Service\Area120Tables;

class LookupDetails extends \Google\Model
{
  /**
   * @var string
   */
  public $relationshipColumn;
  /**
   * @var string
   */
  public $relationshipColumnId;

  /**
   * @param string
   */
  public function setRelationshipColumn($relationshipColumn)
  {
    $this->relationshipColumn = $relationshipColumn;
  }
  /**
   * @return string
   */
  public function getRelationshipColumn()
  {
    return $this->relationshipColumn;
  }
  /**
   * @param string
   */
  public function setRelationshipColumnId($relationshipColumnId)
  {
    $this->relationshipColumnId = $relationshipColumnId;
  }
  /**
   * @return string
   */
  public function getRelationshipColumnId()
  {
    return $this->relationshipColumnId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(LookupDetails::class, 'Google_Service_Area120Tables_LookupDetails');
