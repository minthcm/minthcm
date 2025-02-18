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

namespace Google\Service\AndroidPublisher\Resource;

use Google\Service\AndroidPublisher\Review;
use Google\Service\AndroidPublisher\ReviewsListResponse;
use Google\Service\AndroidPublisher\ReviewsReplyRequest;
use Google\Service\AndroidPublisher\ReviewsReplyResponse;

/**
 * The "reviews" collection of methods.
 * Typical usage is:
 *  <code>
 *   $androidpublisherService = new Google\Service\AndroidPublisher(...);
 *   $reviews = $androidpublisherService->reviews;
 *  </code>
 */
class Reviews extends \Google\Service\Resource
{
  /**
   * Gets a single review. (reviews.get)
   *
   * @param string $packageName Package name of the app.
   * @param string $reviewId Unique identifier for a review.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string translationLanguage Language localization code.
   * @return Review
   * @throws \Google\Service\Exception
   */
  public function get($packageName, $reviewId, $optParams = [])
  {
    $params = ['packageName' => $packageName, 'reviewId' => $reviewId];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Review::class);
  }
  /**
   * Lists all reviews. (reviews.listReviews)
   *
   * @param string $packageName Package name of the app.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string maxResults How many results the list operation should
   * return.
   * @opt_param string startIndex The index of the first element to return.
   * @opt_param string token Pagination token. If empty, list starts at the first
   * review.
   * @opt_param string translationLanguage Language localization code.
   * @return ReviewsListResponse
   * @throws \Google\Service\Exception
   */
  public function listReviews($packageName, $optParams = [])
  {
    $params = ['packageName' => $packageName];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ReviewsListResponse::class);
  }
  /**
   * Replies to a single review, or updates an existing reply. (reviews.reply)
   *
   * @param string $packageName Package name of the app.
   * @param string $reviewId Unique identifier for a review.
   * @param ReviewsReplyRequest $postBody
   * @param array $optParams Optional parameters.
   * @return ReviewsReplyResponse
   * @throws \Google\Service\Exception
   */
  public function reply($packageName, $reviewId, ReviewsReplyRequest $postBody, $optParams = [])
  {
    $params = ['packageName' => $packageName, 'reviewId' => $reviewId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('reply', [$params], ReviewsReplyResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Reviews::class, 'Google_Service_AndroidPublisher_Resource_Reviews');
