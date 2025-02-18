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

namespace Google\Service\PubsubLite\Resource;

use Google\Service\PubsubLite\CommitCursorRequest;
use Google\Service\PubsubLite\CommitCursorResponse;

/**
 * The "subscriptions" collection of methods.
 * Typical usage is:
 *  <code>
 *   $pubsubliteService = new Google\Service\PubsubLite(...);
 *   $subscriptions = $pubsubliteService->cursor_projects_locations_subscriptions;
 *  </code>
 */
class CursorProjectsLocationsSubscriptions extends \Google\Service\Resource
{
  /**
   * Updates the committed cursor. (subscriptions.commitCursor)
   *
   * @param string $subscription The subscription for which to update the cursor.
   * @param CommitCursorRequest $postBody
   * @param array $optParams Optional parameters.
   * @return CommitCursorResponse
   * @throws \Google\Service\Exception
   */
  public function commitCursor($subscription, CommitCursorRequest $postBody, $optParams = [])
  {
    $params = ['subscription' => $subscription, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('commitCursor', [$params], CommitCursorResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CursorProjectsLocationsSubscriptions::class, 'Google_Service_PubsubLite_Resource_CursorProjectsLocationsSubscriptions');
