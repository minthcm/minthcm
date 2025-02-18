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

namespace Google\Service\Dfareporting\Resource;

use Google\Service\Dfareporting\PlacementGroup;
use Google\Service\Dfareporting\PlacementGroupsListResponse;

/**
 * The "placementGroups" collection of methods.
 * Typical usage is:
 *  <code>
 *   $dfareportingService = new Google\Service\Dfareporting(...);
 *   $placementGroups = $dfareportingService->placementGroups;
 *  </code>
 */
class PlacementGroups extends \Google\Service\Resource
{
  /**
   * Gets one placement group by ID. (placementGroups.get)
   *
   * @param string $profileId User profile ID associated with this request.
   * @param string $id Placement group ID.
   * @param array $optParams Optional parameters.
   * @return PlacementGroup
   * @throws \Google\Service\Exception
   */
  public function get($profileId, $id, $optParams = [])
  {
    $params = ['profileId' => $profileId, 'id' => $id];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], PlacementGroup::class);
  }
  /**
   * Inserts a new placement group. (placementGroups.insert)
   *
   * @param string $profileId User profile ID associated with this request.
   * @param PlacementGroup $postBody
   * @param array $optParams Optional parameters.
   * @return PlacementGroup
   * @throws \Google\Service\Exception
   */
  public function insert($profileId, PlacementGroup $postBody, $optParams = [])
  {
    $params = ['profileId' => $profileId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('insert', [$params], PlacementGroup::class);
  }
  /**
   * Retrieves a list of placement groups, possibly filtered. This method supports
   * paging. (placementGroups.listPlacementGroups)
   *
   * @param string $profileId User profile ID associated with this request.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string activeStatus Select only placements with these active
   * statuses.
   * @opt_param string advertiserIds Select only placement groups that belong to
   * these advertisers.
   * @opt_param string campaignIds Select only placement groups that belong to
   * these campaigns.
   * @opt_param string contentCategoryIds Select only placement groups that are
   * associated with these content categories.
   * @opt_param string directorySiteIds Select only placement groups that are
   * associated with these directory sites.
   * @opt_param string ids Select only placement groups with these IDs.
   * @opt_param string maxEndDate Select only placements or placement groups whose
   * end date is on or before the specified maxEndDate. The date should be
   * formatted as "yyyy-MM-dd".
   * @opt_param int maxResults Maximum number of results to return.
   * @opt_param string maxStartDate Select only placements or placement groups
   * whose start date is on or before the specified maxStartDate. The date should
   * be formatted as "yyyy-MM-dd".
   * @opt_param string minEndDate Select only placements or placement groups whose
   * end date is on or after the specified minEndDate. The date should be
   * formatted as "yyyy-MM-dd".
   * @opt_param string minStartDate Select only placements or placement groups
   * whose start date is on or after the specified minStartDate. The date should
   * be formatted as "yyyy-MM-dd".
   * @opt_param string pageToken Value of the nextPageToken from the previous
   * result page.
   * @opt_param string placementGroupType Select only placement groups belonging
   * with this group type. A package is a simple group of placements that acts as
   * a single pricing point for a group of tags. A roadblock is a group of
   * placements that not only acts as a single pricing point but also assumes that
   * all the tags in it will be served at the same time. A roadblock requires one
   * of its assigned placements to be marked as primary for reporting.
   * @opt_param string placementStrategyIds Select only placement groups that are
   * associated with these placement strategies.
   * @opt_param string pricingTypes Select only placement groups with these
   * pricing types.
   * @opt_param string searchString Allows searching for placement groups by name
   * or ID. Wildcards (*) are allowed. For example, "placement*2015" will return
   * placement groups with names like "placement group June 2015", "placement
   * group May 2015", or simply "placements 2015". Most of the searches also add
   * wildcards implicitly at the start and the end of the search string. For
   * example, a search string of "placementgroup" will match placement groups with
   * name "my placementgroup", "placementgroup 2015", or simply "placementgroup".
   * @opt_param string siteIds Select only placement groups that are associated
   * with these sites.
   * @opt_param string sortField Field by which to sort the list.
   * @opt_param string sortOrder Order of sorted results.
   * @return PlacementGroupsListResponse
   * @throws \Google\Service\Exception
   */
  public function listPlacementGroups($profileId, $optParams = [])
  {
    $params = ['profileId' => $profileId];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], PlacementGroupsListResponse::class);
  }
  /**
   * Updates an existing placement group. This method supports patch semantics.
   * (placementGroups.patch)
   *
   * @param string $profileId User profile ID associated with this request.
   * @param string $id Required. Placement ID.
   * @param PlacementGroup $postBody
   * @param array $optParams Optional parameters.
   * @return PlacementGroup
   * @throws \Google\Service\Exception
   */
  public function patch($profileId, $id, PlacementGroup $postBody, $optParams = [])
  {
    $params = ['profileId' => $profileId, 'id' => $id, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], PlacementGroup::class);
  }
  /**
   * Updates an existing placement group. (placementGroups.update)
   *
   * @param string $profileId User profile ID associated with this request.
   * @param PlacementGroup $postBody
   * @param array $optParams Optional parameters.
   * @return PlacementGroup
   * @throws \Google\Service\Exception
   */
  public function update($profileId, PlacementGroup $postBody, $optParams = [])
  {
    $params = ['profileId' => $profileId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('update', [$params], PlacementGroup::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(PlacementGroups::class, 'Google_Service_Dfareporting_Resource_PlacementGroups');
