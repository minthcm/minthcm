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

namespace Google\Service\CloudSearch\Resource;

use Google\Service\CloudSearch\ListQuerySourcesResponse;

/**
 * The "sources" collection of methods.
 * Typical usage is:
 *  <code>
 *   $cloudsearchService = new Google\Service\CloudSearch(...);
 *   $sources = $cloudsearchService->query_sources;
 *  </code>
 */
class QuerySources extends \Google\Service\Resource
{
  /**
   * Returns list of sources that user can use for Search and Suggest APIs.
   * **Note:** This API requires a standard end user account to execute. A service
   * account can't perform Query API requests directly; to use a service account
   * to perform queries, set up [Google Workspace domain-wide delegation of
   * authority](https://developers.google.com/cloud-
   * search/docs/guides/delegation/). (sources.listQuerySources)
   *
   * @param array $optParams Optional parameters.
   *
   * @opt_param string pageToken Number of sources to return in the response.
   * @opt_param bool requestOptions.debugOptions.enableDebugging If you are asked
   * by Google to help with debugging, set this field. Otherwise, ignore this
   * field.
   * @opt_param string requestOptions.languageCode The BCP-47 language code, such
   * as "en-US" or "sr-Latn". For more information, see
   * http://www.unicode.org/reports/tr35/#Unicode_locale_identifier. For
   * translations. Set this field using the language set in browser or for the
   * page. In the event that the user's language preference is known, set this
   * field to the known user language. When specified, the documents in search
   * results are biased towards the specified language. The Suggest API uses this
   * field as a hint to make better third-party autocomplete predictions.
   * @opt_param string requestOptions.searchApplicationId The ID generated when
   * you create a search application using the [admin
   * console](https://support.google.com/a/answer/9043922).
   * @opt_param string requestOptions.timeZone Current user's time zone id, such
   * as "America/Los_Angeles" or "Australia/Sydney". These IDs are defined by
   * [Unicode Common Locale Data Repository (CLDR)](http://cldr.unicode.org/)
   * project, and currently available in the file [timezone.xml](http://unicode.or
   * g/repos/cldr/trunk/common/bcp47/timezone.xml). This field is used to
   * correctly interpret date and time queries. If this field is not specified,
   * the default time zone (UTC) is used.
   * @return ListQuerySourcesResponse
   * @throws \Google\Service\Exception
   */
  public function listQuerySources($optParams = [])
  {
    $params = [];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListQuerySourcesResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(QuerySources::class, 'Google_Service_CloudSearch_Resource_QuerySources');
