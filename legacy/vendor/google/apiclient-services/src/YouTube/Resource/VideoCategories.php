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

namespace Google\Service\YouTube\Resource;

use Google\Service\YouTube\VideoCategoryListResponse;

/**
 * The "videoCategories" collection of methods.
 * Typical usage is:
 *  <code>
 *   $youtubeService = new Google\Service\YouTube(...);
 *   $videoCategories = $youtubeService->videoCategories;
 *  </code>
 */
class VideoCategories extends \Google\Service\Resource
{
  /**
   * Retrieves a list of resources, possibly filtered.
   * (videoCategories.listVideoCategories)
   *
   * @param string|array $part The *part* parameter specifies the videoCategory
   * resource properties that the API response will include. Set the parameter
   * value to snippet.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string hl
   * @opt_param string id Returns the video categories with the given IDs for
   * Stubby or Apiary.
   * @opt_param string regionCode
   * @return VideoCategoryListResponse
   * @throws \Google\Service\Exception
   */
  public function listVideoCategories($part, $optParams = [])
  {
    $params = ['part' => $part];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], VideoCategoryListResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(VideoCategories::class, 'Google_Service_YouTube_Resource_VideoCategories');
