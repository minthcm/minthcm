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

namespace Google\Service\AdExperienceReport\Resource;

use Google\Service\AdExperienceReport\SiteSummaryResponse;

/**
 * The "sites" collection of methods.
 * Typical usage is:
 *  <code>
 *   $adexperiencereportService = new Google\Service\AdExperienceReport(...);
 *   $sites = $adexperiencereportService->sites;
 *  </code>
 */
class Sites extends \Google\Service\Resource
{
  /**
   * Gets a site's Ad Experience Report summary. (sites.get)
   *
   * @param string $name Required. The name of the site whose summary to get, e.g.
   * `sites/http%3A%2F%2Fwww.google.com%2F`. Format: `sites/{site}`
   * @param array $optParams Optional parameters.
   * @return SiteSummaryResponse
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], SiteSummaryResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Sites::class, 'Google_Service_AdExperienceReport_Resource_Sites');
