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

namespace Google\Service\Bigquery;

class TableDataInsertAllRequest extends \Google\Collection
{
  protected $collection_key = 'rows';
  /**
   * @var bool
   */
  public $ignoreUnknownValues;
  /**
   * @var string
   */
  public $kind;
  protected $rowsType = TableDataInsertAllRequestRows::class;
  protected $rowsDataType = 'array';
  /**
   * @var bool
   */
  public $skipInvalidRows;
  /**
   * @var string
   */
  public $templateSuffix;
  /**
   * @var string
   */
  public $traceId;

  /**
   * @param bool
   */
  public function setIgnoreUnknownValues($ignoreUnknownValues)
  {
    $this->ignoreUnknownValues = $ignoreUnknownValues;
  }
  /**
   * @return bool
   */
  public function getIgnoreUnknownValues()
  {
    return $this->ignoreUnknownValues;
  }
  /**
   * @param string
   */
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  /**
   * @return string
   */
  public function getKind()
  {
    return $this->kind;
  }
  /**
   * @param TableDataInsertAllRequestRows[]
   */
  public function setRows($rows)
  {
    $this->rows = $rows;
  }
  /**
   * @return TableDataInsertAllRequestRows[]
   */
  public function getRows()
  {
    return $this->rows;
  }
  /**
   * @param bool
   */
  public function setSkipInvalidRows($skipInvalidRows)
  {
    $this->skipInvalidRows = $skipInvalidRows;
  }
  /**
   * @return bool
   */
  public function getSkipInvalidRows()
  {
    return $this->skipInvalidRows;
  }
  /**
   * @param string
   */
  public function setTemplateSuffix($templateSuffix)
  {
    $this->templateSuffix = $templateSuffix;
  }
  /**
   * @return string
   */
  public function getTemplateSuffix()
  {
    return $this->templateSuffix;
  }
  /**
   * @param string
   */
  public function setTraceId($traceId)
  {
    $this->traceId = $traceId;
  }
  /**
   * @return string
   */
  public function getTraceId()
  {
    return $this->traceId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(TableDataInsertAllRequest::class, 'Google_Service_Bigquery_TableDataInsertAllRequest');
