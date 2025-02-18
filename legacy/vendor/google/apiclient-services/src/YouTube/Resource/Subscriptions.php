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

use Google\Service\YouTube\Subscription;
use Google\Service\YouTube\SubscriptionListResponse;

/**
 * The "subscriptions" collection of methods.
 * Typical usage is:
 *  <code>
 *   $youtubeService = new Google\Service\YouTube(...);
 *   $subscriptions = $youtubeService->subscriptions;
 *  </code>
 */
class Subscriptions extends \Google\Service\Resource
{
  /**
   * Deletes a resource. (subscriptions.delete)
   *
   * @param string $id
   * @param array $optParams Optional parameters.
   * @throws \Google\Service\Exception
   */
  public function delete($id, $optParams = [])
  {
    $params = ['id' => $id];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params]);
  }
  /**
   * Inserts a new resource into this collection. (subscriptions.insert)
   *
   * @param string|array $part The *part* parameter serves two purposes in this
   * operation. It identifies the properties that the write operation will set as
   * well as the properties that the API response will include.
   * @param Subscription $postBody
   * @param array $optParams Optional parameters.
   * @return Subscription
   * @throws \Google\Service\Exception
   */
  public function insert($part, Subscription $postBody, $optParams = [])
  {
    $params = ['part' => $part, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('insert', [$params], Subscription::class);
  }
  /**
   * Retrieves a list of resources, possibly filtered.
   * (subscriptions.listSubscriptions)
   *
   * @param string|array $part The *part* parameter specifies a comma-separated
   * list of one or more subscription resource properties that the API response
   * will include. If the parameter identifies a property that contains child
   * properties, the child properties will be included in the response. For
   * example, in a subscription resource, the snippet property contains other
   * properties, such as a display title for the subscription. If you set
   * *part=snippet*, the API response will also contain all of those nested
   * properties.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string channelId Return the subscriptions of the given channel
   * owner.
   * @opt_param string forChannelId Return the subscriptions to the subset of
   * these channels that the authenticated user is subscribed to.
   * @opt_param string id Return the subscriptions with the given IDs for Stubby
   * or Apiary.
   * @opt_param string maxResults The *maxResults* parameter specifies the maximum
   * number of items that should be returned in the result set.
   * @opt_param bool mine Flag for returning the subscriptions of the
   * authenticated user.
   * @opt_param bool myRecentSubscribers
   * @opt_param bool mySubscribers Return the subscribers of the given channel
   * owner.
   * @opt_param string onBehalfOfContentOwner *Note:* This parameter is intended
   * exclusively for YouTube content partners. The *onBehalfOfContentOwner*
   * parameter indicates that the request's authorization credentials identify a
   * YouTube CMS user who is acting on behalf of the content owner specified in
   * the parameter value. This parameter is intended for YouTube content partners
   * that own and manage many different YouTube channels. It allows content owners
   * to authenticate once and get access to all their video and channel data,
   * without having to provide authentication credentials for each individual
   * channel. The CMS account that the user authenticates with must be linked to
   * the specified YouTube content owner.
   * @opt_param string onBehalfOfContentOwnerChannel This parameter can only be
   * used in a properly authorized request. *Note:* This parameter is intended
   * exclusively for YouTube content partners. The *onBehalfOfContentOwnerChannel*
   * parameter specifies the YouTube channel ID of the channel to which a video is
   * being added. This parameter is required when a request specifies a value for
   * the onBehalfOfContentOwner parameter, and it can only be used in conjunction
   * with that parameter. In addition, the request must be authorized using a CMS
   * account that is linked to the content owner that the onBehalfOfContentOwner
   * parameter specifies. Finally, the channel that the
   * onBehalfOfContentOwnerChannel parameter value specifies must be linked to the
   * content owner that the onBehalfOfContentOwner parameter specifies. This
   * parameter is intended for YouTube content partners that own and manage many
   * different YouTube channels. It allows content owners to authenticate once and
   * perform actions on behalf of the channel specified in the parameter value,
   * without having to provide authentication credentials for each separate
   * channel.
   * @opt_param string order The order of the returned subscriptions
   * @opt_param string pageToken The *pageToken* parameter identifies a specific
   * page in the result set that should be returned. In an API response, the
   * nextPageToken and prevPageToken properties identify other pages that could be
   * retrieved.
   * @return SubscriptionListResponse
   * @throws \Google\Service\Exception
   */
  public function listSubscriptions($part, $optParams = [])
  {
    $params = ['part' => $part];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], SubscriptionListResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Subscriptions::class, 'Google_Service_YouTube_Resource_Subscriptions');
