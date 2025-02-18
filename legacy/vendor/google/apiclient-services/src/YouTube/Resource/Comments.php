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

use Google\Service\YouTube\Comment;
use Google\Service\YouTube\CommentListResponse;

/**
 * The "comments" collection of methods.
 * Typical usage is:
 *  <code>
 *   $youtubeService = new Google\Service\YouTube(...);
 *   $comments = $youtubeService->comments;
 *  </code>
 */
class Comments extends \Google\Service\Resource
{
  /**
   * Deletes a resource. (comments.delete)
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
   * Inserts a new resource into this collection. (comments.insert)
   *
   * @param string|array $part The *part* parameter identifies the properties that
   * the API response will include. Set the parameter value to snippet. The
   * snippet part has a quota cost of 2 units.
   * @param Comment $postBody
   * @param array $optParams Optional parameters.
   * @return Comment
   * @throws \Google\Service\Exception
   */
  public function insert($part, Comment $postBody, $optParams = [])
  {
    $params = ['part' => $part, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('insert', [$params], Comment::class);
  }
  /**
   * Retrieves a list of resources, possibly filtered. (comments.listComments)
   *
   * @param string|array $part The *part* parameter specifies a comma-separated
   * list of one or more comment resource properties that the API response will
   * include.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string id Returns the comments with the given IDs for One
   * Platform.
   * @opt_param string maxResults The *maxResults* parameter specifies the maximum
   * number of items that should be returned in the result set.
   * @opt_param string pageToken The *pageToken* parameter identifies a specific
   * page in the result set that should be returned. In an API response, the
   * nextPageToken and prevPageToken properties identify other pages that could be
   * retrieved.
   * @opt_param string parentId Returns replies to the specified comment. Note,
   * currently YouTube features only one level of replies (ie replies to top level
   * comments). However replies to replies may be supported in the future.
   * @opt_param string textFormat The requested text format for the returned
   * comments.
   * @return CommentListResponse
   * @throws \Google\Service\Exception
   */
  public function listComments($part, $optParams = [])
  {
    $params = ['part' => $part];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], CommentListResponse::class);
  }
  /**
   * Expresses the caller's opinion that one or more comments should be flagged as
   * spam. (comments.markAsSpam)
   *
   * @param string|array $id Flags the comments with the given IDs as spam in the
   * caller's opinion.
   * @param array $optParams Optional parameters.
   * @throws \Google\Service\Exception
   */
  public function markAsSpam($id, $optParams = [])
  {
    $params = ['id' => $id];
    $params = array_merge($params, $optParams);
    return $this->call('markAsSpam', [$params]);
  }
  /**
   * Sets the moderation status of one or more comments.
   * (comments.setModerationStatus)
   *
   * @param string|array $id Modifies the moderation status of the comments with
   * the given IDs
   * @param string $moderationStatus Specifies the requested moderation status.
   * Note, comments can be in statuses, which are not available through this call.
   * For example, this call does not allow to mark a comment as 'likely spam'.
   * Valid values: 'heldForReview', 'published' or 'rejected'.
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool banAuthor If set to true the author of the comment gets added
   * to the ban list. This means all future comments of the author will
   * autmomatically be rejected. Only valid in combination with STATUS_REJECTED.
   * @throws \Google\Service\Exception
   */
  public function setModerationStatus($id, $moderationStatus, $optParams = [])
  {
    $params = ['id' => $id, 'moderationStatus' => $moderationStatus];
    $params = array_merge($params, $optParams);
    return $this->call('setModerationStatus', [$params]);
  }
  /**
   * Updates an existing resource. (comments.update)
   *
   * @param string|array $part The *part* parameter identifies the properties that
   * the API response will include. You must at least include the snippet part in
   * the parameter value since that part contains all of the properties that the
   * API request can update.
   * @param Comment $postBody
   * @param array $optParams Optional parameters.
   * @return Comment
   * @throws \Google\Service\Exception
   */
  public function update($part, Comment $postBody, $optParams = [])
  {
    $params = ['part' => $part, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('update', [$params], Comment::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Comments::class, 'Google_Service_YouTube_Resource_Comments');
