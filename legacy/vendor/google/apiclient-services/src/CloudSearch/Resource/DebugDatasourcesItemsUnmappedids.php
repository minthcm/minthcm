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

use Google\Service\CloudSearch\ListUnmappedIdentitiesResponse;

/**
 * The "unmappedids" collection of methods.
 * Typical usage is:
 *  <code>
 *   $cloudsearchService = new Google\Service\CloudSearch(...);
 *   $unmappedids = $cloudsearchService->debug_datasources_items_unmappedids;
 *  </code>
 */
class DebugDatasourcesItemsUnmappedids extends \Google\Service\Resource
{
  /**
   * List all unmapped identities for a specific item. **Note:** This API requires
   * an admin account to execute.
   * (unmappedids.listDebugDatasourcesItemsUnmappedids)
   *
   * @param string $parent The name of the item, in the following format:
   * datasources/{source_id}/items/{ID}
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool debugOptions.enableDebugging If you are asked by Google to
   * help with debugging, set this field. Otherwise, ignore this field.
   * @opt_param int pageSize Maximum number of items to fetch in a request.
   * Defaults to 100.
   * @opt_param string pageToken The next_page_token value returned from a
   * previous List request, if any.
   * @return ListUnmappedIdentitiesResponse
   * @throws \Google\Service\Exception
   */
  public function listDebugDatasourcesItemsUnmappedids($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListUnmappedIdentitiesResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DebugDatasourcesItemsUnmappedids::class, 'Google_Service_CloudSearch_Resource_DebugDatasourcesItemsUnmappedids');
