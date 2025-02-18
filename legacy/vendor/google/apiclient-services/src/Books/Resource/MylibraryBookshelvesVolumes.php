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

use Google\Service\Books\Volumes as VolumesModel;

/**
 * The "volumes" collection of methods.
 * Typical usage is:
 *  <code>
 *   $booksService = new Google\Service\Books(...);
 *   $volumes = $booksService->mylibrary_bookshelves_volumes;
 *  </code>
 */
class MylibraryBookshelvesVolumes extends \Google\Service\Resource
{
  /**
   * Gets volume information for volumes on a bookshelf.
   * (volumes.listMylibraryBookshelvesVolumes)
   *
   * @param string $shelf The bookshelf ID or name retrieve volumes for.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string country ISO-3166-1 code to override the IP-based location.
   * @opt_param string maxResults Maximum number of results to return
   * @opt_param string projection Restrict information returned to a set of
   * selected fields.
   * @opt_param string q Full-text search query string in this bookshelf.
   * @opt_param bool showPreorders Set to true to show pre-ordered books. Defaults
   * to false.
   * @opt_param string source String to identify the originator of this request.
   * @opt_param string startIndex Index of the first element to return (starts at
   * 0)
   * @return VolumesModel
   * @throws \Google\Service\Exception
   */
  public function listMylibraryBookshelvesVolumes($shelf, $optParams = [])
  {
    $params = ['shelf' => $shelf];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], VolumesModel::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(MylibraryBookshelvesVolumes::class, 'Google_Service_Books_Resource_MylibraryBookshelvesVolumes');
