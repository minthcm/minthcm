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

namespace Google\Service\Docs;

class TableRowStyle extends \Google\Model
{
  protected $minRowHeightType = Dimension::class;
  protected $minRowHeightDataType = '';
  /**
   * @var bool
   */
  public $preventOverflow;
  /**
   * @var bool
   */
  public $tableHeader;

  /**
   * @param Dimension
   */
  public function setMinRowHeight(Dimension $minRowHeight)
  {
    $this->minRowHeight = $minRowHeight;
  }
  /**
   * @return Dimension
   */
  public function getMinRowHeight()
  {
    return $this->minRowHeight;
  }
  /**
   * @param bool
   */
  public function setPreventOverflow($preventOverflow)
  {
    $this->preventOverflow = $preventOverflow;
  }
  /**
   * @return bool
   */
  public function getPreventOverflow()
  {
    return $this->preventOverflow;
  }
  /**
   * @param bool
   */
  public function setTableHeader($tableHeader)
  {
    $this->tableHeader = $tableHeader;
  }
  /**
   * @return bool
   */
  public function getTableHeader()
  {
    return $this->tableHeader;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(TableRowStyle::class, 'Google_Service_Docs_TableRowStyle');
