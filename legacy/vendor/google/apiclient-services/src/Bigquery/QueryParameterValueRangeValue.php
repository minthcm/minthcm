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

class QueryParameterValueRangeValue extends \Google\Model
{
  protected $endType = QueryParameterValue::class;
  protected $endDataType = '';
  protected $startType = QueryParameterValue::class;
  protected $startDataType = '';

  /**
   * @param QueryParameterValue
   */
  public function setEnd(QueryParameterValue $end)
  {
    $this->end = $end;
  }
  /**
   * @return QueryParameterValue
   */
  public function getEnd()
  {
    return $this->end;
  }
  /**
   * @param QueryParameterValue
   */
  public function setStart(QueryParameterValue $start)
  {
    $this->start = $start;
  }
  /**
   * @return QueryParameterValue
   */
  public function getStart()
  {
    return $this->start;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(QueryParameterValueRangeValue::class, 'Google_Service_Bigquery_QueryParameterValueRangeValue');
