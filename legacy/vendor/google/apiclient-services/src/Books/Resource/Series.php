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

namespace Google\Service\Books\Resource;

use Google\Service\Books\Series as SeriesModel;

/**
 * The "series" collection of methods.
 * Typical usage is:
 *  <code>
 *   $booksService = new Google\Service\Books(...);
 *   $series = $booksService->series;
 *  </code>
 */
class Series extends \Google\Service\Resource
{
  /**
   * Returns Series metadata for the given series ids. (series.get)
   *
   * @param string|array $seriesId String that identifies the series
   * @param array $optParams Optional parameters.
   * @return SeriesModel
   * @throws \Google\Service\Exception
   */
  public function get($seriesId, $optParams = [])
  {
    $params = ['series_id' => $seriesId];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], SeriesModel::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Series::class, 'Google_Service_Books_Resource_Series');
