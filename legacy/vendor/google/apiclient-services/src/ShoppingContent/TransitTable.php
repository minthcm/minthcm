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

namespace Google\Service\ShoppingContent;

class TransitTable extends \Google\Collection
{
  protected $collection_key = 'transitTimeLabels';
  /**
   * @var string[]
   */
  public $postalCodeGroupNames;
  protected $rowsType = TransitTableTransitTimeRow::class;
  protected $rowsDataType = 'array';
  /**
   * @var string[]
   */
  public $transitTimeLabels;

  /**
   * @param string[]
   */
  public function setPostalCodeGroupNames($postalCodeGroupNames)
  {
    $this->postalCodeGroupNames = $postalCodeGroupNames;
  }
  /**
   * @return string[]
   */
  public function getPostalCodeGroupNames()
  {
    return $this->postalCodeGroupNames;
  }
  /**
   * @param TransitTableTransitTimeRow[]
   */
  public function setRows($rows)
  {
    $this->rows = $rows;
  }
  /**
   * @return TransitTableTransitTimeRow[]
   */
  public function getRows()
  {
    return $this->rows;
  }
  /**
   * @param string[]
   */
  public function setTransitTimeLabels($transitTimeLabels)
  {
    $this->transitTimeLabels = $transitTimeLabels;
  }
  /**
   * @return string[]
   */
  public function getTransitTimeLabels()
  {
    return $this->transitTimeLabels;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(TransitTable::class, 'Google_Service_ShoppingContent_TransitTable');
