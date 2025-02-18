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

namespace Google\Service\Integrations;

class EnterpriseCrmCardsTemplatesAplosSeriesListData extends \Google\Collection
{
  protected $collection_key = 'series';
  protected $seriesType = EnterpriseCrmCardsTemplatesAplosSeriesListDataSeries::class;
  protected $seriesDataType = 'array';
  public $series;

  /**
   * @param EnterpriseCrmCardsTemplatesAplosSeriesListDataSeries[]
   */
  public function setSeries($series)
  {
    $this->series = $series;
  }
  /**
   * @return EnterpriseCrmCardsTemplatesAplosSeriesListDataSeries[]
   */
  public function getSeries()
  {
    return $this->series;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(EnterpriseCrmCardsTemplatesAplosSeriesListData::class, 'Google_Service_Integrations_EnterpriseCrmCardsTemplatesAplosSeriesListData');
