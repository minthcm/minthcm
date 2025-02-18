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

namespace Google\Service\Slides;

class CreateParagraphBulletsRequest extends \Google\Model
{
  /**
   * @var string
   */
  public $bulletPreset;
  protected $cellLocationType = TableCellLocation::class;
  protected $cellLocationDataType = '';
  /**
   * @var string
   */
  public $objectId;
  protected $textRangeType = Range::class;
  protected $textRangeDataType = '';

  /**
   * @param string
   */
  public function setBulletPreset($bulletPreset)
  {
    $this->bulletPreset = $bulletPreset;
  }
  /**
   * @return string
   */
  public function getBulletPreset()
  {
    return $this->bulletPreset;
  }
  /**
   * @param TableCellLocation
   */
  public function setCellLocation(TableCellLocation $cellLocation)
  {
    $this->cellLocation = $cellLocation;
  }
  /**
   * @return TableCellLocation
   */
  public function getCellLocation()
  {
    return $this->cellLocation;
  }
  /**
   * @param string
   */
  public function setObjectId($objectId)
  {
    $this->objectId = $objectId;
  }
  /**
   * @return string
   */
  public function getObjectId()
  {
    return $this->objectId;
  }
  /**
   * @param Range
   */
  public function setTextRange(Range $textRange)
  {
    $this->textRange = $textRange;
  }
  /**
   * @return Range
   */
  public function getTextRange()
  {
    return $this->textRange;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CreateParagraphBulletsRequest::class, 'Google_Service_Slides_CreateParagraphBulletsRequest');
