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

use Google\Service\Dfareporting\Placement;
use Google\Service\Dfareporting\PlacementsGenerateTagsResponse;
use Google\Service\Dfareporting\PlacementsListResponse;

/**
 * The "placements" collection of methods.
 * Typical usage is:
 *  <code>
 *   $dfareportingService = new Google\Service\Dfareporting(...);
 *   $placements = $dfareportingService->placements;
 *  </code>
 */
class Placements extends \Google\Service\Resource
{
  /**
   * Generates tags for a placement. (placements.generatetags)
   *
   * @param string $profileId User profile ID associated with this request.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string campaignId Generate placements belonging to this campaign.
   * This is a required field.
   * @opt_param string placementIds Generate tags for these placements.
   * @opt_param string tagFormats Tag formats to generate for these placements.
   * *Note:* PLACEMENT_TAG_STANDARD can only be generated for 1x1 placements.
   * @return PlacementsGenerateTagsResponse
   * @throws \Google\Service\Exception
   */
  public function generatetags($profileId, $optParams = [])
  {
    $params = ['profileId' => $profileId];
    $params = array_merge($params, $optParams);
    return $this->call('generatetags', [$params], PlacementsGenerateTagsResponse::class);
  }
  /**
   * Gets one placement by ID. (placements.get)
   *
   * @param string $profileId User profile ID associated with this request.
   * @param string $id Placement ID.
   * @param array $optParams Optional parameters.
   * @return Placement
   * @throws \Google\Service\Exception
   */
  public function get($profileId, $id, $optParams = [])
  {
    $params = ['profileId' => $profileId, 'id' => $id];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Placement::class);
  }
  /**
   * Inserts a new placement. (placements.insert)
   *
   * @param string $profileId User profile ID associated with this request.
   * @param Placement $postBody
   * @param array $optParams Optional parameters.
   * @return Placement
   * @throws \Google\Service\Exception
   */
  public function insert($profileId, Placement $postBody, $optParams = [])
  {
    $params = ['profileId' => $profileId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('insert', [$params], Placement::class);
  }
  /**
   * Retrieves a list of placements, possibly filtered. This method supports
   * paging. (placements.listPlacements)
   *
   * @param string $profileId User profile ID associated with this request.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string activeStatus Select only placements with these active
   * statuses.
   * @opt_param string advertiserIds Select only placements that belong to these
   * advertisers.
   * @opt_param string campaignIds Select only placements that belong to these
   * campaigns.
   * @opt_param string compatibilities Select only placements that are associated
   * with these compatibilities. DISPLAY and DISPLAY_INTERSTITIAL refer to
   * rendering either on desktop or on mobile devices for regular or interstitial
   * ads respectively. APP and APP_INTERSTITIAL are for rendering in mobile apps.
   * IN_STREAM_VIDEO refers to rendering in in-stream video ads developed with the
   * VAST standard.
   * @opt_param string contentCategoryIds Select only placements that are
   * associated with these content categories.
   * @opt_param string directorySiteIds Select only placements that are associated
   * with these directory sites.
   * @opt_param string groupIds Select only placements that belong to these
   * placement groups.
   * @opt_param string ids Select only placements with these IDs.
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
   * @opt_param string paymentSource Select only placements with this payment
   * source.
   * @opt_param string placementStrategyIds Select only placements that are
   * associated with these placement strategies.
   * @opt_param string pricingTypes Select only placements with these pricing
   * types.
   * @opt_param string searchString Allows searching for placements by name or ID.
   * Wildcards (*) are allowed. For example, "placement*2015" will return
   * placements with names like "placement June 2015", "placement May 2015", or
   * simply "placements 2015". Most of the searches also add wildcards implicitly
   * at the start and the end of the search string. For example, a search string
   * of "placement" will match placements with name "my placement", "placement
   * 2015", or simply "placement" .
   * @opt_param string siteIds Select only placements that are associated with
   * these sites.
   * @opt_param string sizeIds Select only placements that are associated with
   * these sizes.
   * @opt_param string sortField Field by which to sort the list.
   * @opt_param string sortOrder Order of sorted results.
   * @return PlacementsListResponse
   * @throws \Google\Service\Exception
   */
  public function listPlacements($profileId, $optParams = [])
  {
    $params = ['profileId' => $profileId];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], PlacementsListResponse::class);
  }
  /**
   * Updates an existing placement. This method supports patch semantics.
   * (placements.patch)
   *
   * @param string $profileId User profile ID associated with this request.
   * @param string $id Required. Placement ID.
   * @param Placement $postBody
   * @param array $optParams Optional parameters.
   * @return Placement
   * @throws \Google\Service\Exception
   */
  public function patch($profileId, $id, Placement $postBody, $optParams = [])
  {
    $params = ['profileId' => $profileId, 'id' => $id, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Placement::class);
  }
  /**
   * Updates an existing placement. (placements.update)
   *
   * @param string $profileId User profile ID associated with this request.
   * @param Placement $postBody
   * @param array $optParams Optional parameters.
   * @return Placement
   * @throws \Google\Service\Exception
   */
  public function update($profileId, Placement $postBody, $optParams = [])
  {
    $params = ['profileId' => $profileId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('update', [$params], Placement::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Placements::class, 'Google_Service_Dfareporting_Resource_Placements');
