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

namespace Google\Service\Datastore;

class Aggregation extends \Google\Model
{
  /**
   * @var string
   */
  public $alias;
  protected $avgType = Avg::class;
  protected $avgDataType = '';
  protected $countType = Count::class;
  protected $countDataType = '';
  protected $sumType = Sum::class;
  protected $sumDataType = '';

  /**
   * @param string
   */
  public function setAlias($alias)
  {
    $this->alias = $alias;
  }
  /**
   * @return string
   */
  public function getAlias()
  {
    return $this->alias;
  }
  /**
   * @param Avg
   */
  public function setAvg(Avg $avg)
  {
    $this->avg = $avg;
  }
  /**
   * @return Avg
   */
  public function getAvg()
  {
    return $this->avg;
  }
  /**
   * @param Count
   */
  public function setCount(Count $count)
  {
    $this->count = $count;
  }
  /**
   * @return Count
   */
  public function getCount()
  {
    return $this->count;
  }
  /**
   * @param Sum
   */
  public function setSum(Sum $sum)
  {
    $this->sum = $sum;
  }
  /**
   * @return Sum
   */
  public function getSum()
  {
    return $this->sum;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Aggregation::class, 'Google_Service_Datastore_Aggregation');
