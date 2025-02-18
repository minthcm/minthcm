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

namespace Google\Service\HangoutsChat\Resource;

use Google\Service\HangoutsChat\ListMembershipsResponse;
use Google\Service\HangoutsChat\Membership;

/**
 * The "members" collection of methods.
 * Typical usage is:
 *  <code>
 *   $chatService = new Google\Service\HangoutsChat(...);
 *   $members = $chatService->spaces_members;
 *  </code>
 */
class SpacesMembers extends \Google\Service\Resource
{
  /**
   * Creates a human membership or app membership for the calling app. Creating
   * memberships for other apps isn't supported. For an example, see [Invite or
   * add a user or a Google Chat app to a
   * space](https://developers.google.com/workspace/chat/create-members). When
   * creating a membership, if the specified member has their auto-accept policy
   * turned off, then they're invited, and must accept the space invitation before
   * joining. Otherwise, creating a membership adds the member directly to the
   * specified space. Requires [user
   * authentication](https://developers.google.com/workspace/chat/authenticate-
   * authorize-chat-user). To specify the member to add, set the
   * `membership.member.name` for the human or app member, or set the
   * `membership.group_member.name` for the group member. - To add the calling app
   * to a space or a direct message between two human users, use `users/app`.
   * Unable to add other apps to the space. - To add a human user, use
   * `users/{user}`, where `{user}` can be the email address for the user. For
   * users in the same Workspace organization `{user}` can also be the `id` for
   * the person from the People API, or the `id` for the user in the Directory
   * API. For example, if the People API Person profile ID for `user@example.com`
   * is `123456789`, you can add the user to the space by setting the
   * `membership.member.name` to `users/user@example.com` or `users/123456789`. -
   * To add or invite a Google group in a named space, use `groups/{group}`, where
   * `{group}` is the `id` for the group from the Cloud Identity Groups API. For
   * example, you can use [Cloud Identity Groups lookup
   * API](https://cloud.google.com/identity/docs/reference/rest/v1/groups/lookup)
   * to retrieve the ID `123456789` for group email `group@example.com`, then you
   * can add or invite the group to a named space by setting the
   * `membership.group_member.name` to `groups/123456789`. Group email is not
   * supported, and Google groups can only be added as members in named spaces.
   * (members.create)
   *
   * @param string $parent Required. The resource name of the space for which to
   * create the membership. Format: spaces/{space}
   * @param Membership $postBody
   * @param array $optParams Optional parameters.
   * @return Membership
   * @throws \Google\Service\Exception
   */
  public function create($parent, Membership $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Membership::class);
  }
  /**
   * Deletes a membership. For an example, see [Remove a user or a Google Chat app
   * from a space](https://developers.google.com/workspace/chat/delete-members).
   * Requires [user
   * authentication](https://developers.google.com/workspace/chat/authenticate-
   * authorize-chat-user). (members.delete)
   *
   * @param string $name Required. Resource name of the membership to delete. Chat
   * apps can delete human users' or their own memberships. Chat apps can't delete
   * other apps' memberships. When deleting a human membership, requires the
   * `chat.memberships` scope and `spaces/{space}/members/{member}` format. You
   * can use the email as an alias for `{member}`. For example,
   * `spaces/{space}/members/example@gmail.com` where `example@gmail.com` is the
   * email of the Google Chat user. When deleting an app membership, requires the
   * `chat.memberships.app` scope and `spaces/{space}/members/app` format. Format:
   * `spaces/{space}/members/{member}` or `spaces/{space}/members/app`.
   * @param array $optParams Optional parameters.
   * @return Membership
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], Membership::class);
  }
  /**
   * Returns details about a membership. For an example, see [Get details about a
   * user's or Google Chat app's
   * membership](https://developers.google.com/workspace/chat/get-members).
   * Requires
   * [authentication](https://developers.google.com/workspace/chat/authenticate-
   * authorize). Supports [app
   * authentication](https://developers.google.com/workspace/chat/authenticate-
   * authorize-chat-app) and [user
   * authentication](https://developers.google.com/workspace/chat/authenticate-
   * authorize-chat-user). (members.get)
   *
   * @param string $name Required. Resource name of the membership to retrieve. To
   * get the app's own membership [by using user
   * authentication](https://developers.google.com/workspace/chat/authenticate-
   * authorize-chat-user), you can optionally use `spaces/{space}/members/app`.
   * Format: `spaces/{space}/members/{member}` or `spaces/{space}/members/app`
   * When [authenticated as a
   * user](https://developers.google.com/workspace/chat/authenticate-authorize-
   * chat-user), you can use the user's email as an alias for `{member}`. For
   * example, `spaces/{space}/members/example@gmail.com` where `example@gmail.com`
   * is the email of the Google Chat user.
   * @param array $optParams Optional parameters.
   * @return Membership
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Membership::class);
  }
  /**
   * Lists memberships in a space. For an example, see [List users and Google Chat
   * apps in a space](https://developers.google.com/workspace/chat/list-members).
   * Listing memberships with [app
   * authentication](https://developers.google.com/workspace/chat/authenticate-
   * authorize-chat-app) lists memberships in spaces that the Chat app has access
   * to, but excludes Chat app memberships, including its own. Listing memberships
   * with [User
   * authentication](https://developers.google.com/workspace/chat/authenticate-
   * authorize-chat-user) lists memberships in spaces that the authenticated user
   * has access to. Requires
   * [authentication](https://developers.google.com/workspace/chat/authenticate-
   * authorize). Supports [app
   * authentication](https://developers.google.com/workspace/chat/authenticate-
   * authorize-chat-app) and [user
   * authentication](https://developers.google.com/workspace/chat/authenticate-
   * authorize-chat-user). (members.listSpacesMembers)
   *
   * @param string $parent Required. The resource name of the space for which to
   * fetch a membership list. Format: spaces/{space}
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter Optional. A query filter. You can filter memberships
   * by a member's role ([`role`](https://developers.google.com/workspace/chat/api
   * /reference/rest/v1/spaces.members#membershiprole)) and type ([`member.type`](
   * https://developers.google.com/workspace/chat/api/reference/rest/v1/User#type)
   * ). To filter by role, set `role` to `ROLE_MEMBER` or `ROLE_MANAGER`. To
   * filter by type, set `member.type` to `HUMAN` or `BOT`. Developer Preview: You
   * can also filter for `member.type` using the `!=` operator. To filter by both
   * role and type, use the `AND` operator. To filter by either role or type, use
   * the `OR` operator. Either `member.type = "HUMAN"` or `member.type != "BOT"`
   * is required when `use_admin_access` is set to true. Other member type filters
   * will be rejected. For example, the following queries are valid: ``` role =
   * "ROLE_MANAGER" OR role = "ROLE_MEMBER" member.type = "HUMAN" AND role =
   * "ROLE_MANAGER" member.type != "BOT" ``` The following queries are invalid:
   * ``` member.type = "HUMAN" AND member.type = "BOT" role = "ROLE_MANAGER" AND
   * role = "ROLE_MEMBER" ``` Invalid queries are rejected by the server with an
   * `INVALID_ARGUMENT` error.
   * @opt_param int pageSize Optional. The maximum number of memberships to
   * return. The service might return fewer than this value. If unspecified, at
   * most 100 memberships are returned. The maximum value is 1000. If you use a
   * value more than 1000, it's automatically changed to 1000. Negative values
   * return an `INVALID_ARGUMENT` error.
   * @opt_param string pageToken Optional. A page token, received from a previous
   * call to list memberships. Provide this parameter to retrieve the subsequent
   * page. When paginating, all other parameters provided should match the call
   * that provided the page token. Passing different values to the other
   * parameters might lead to unexpected results.
   * @opt_param bool showGroups Optional. When `true`, also returns memberships
   * associated with a Google Group, in addition to other types of memberships. If
   * a filter is set, Google Group memberships that don't match the filter
   * criteria aren't returned.
   * @opt_param bool showInvited Optional. When `true`, also returns memberships
   * associated with invited members, in addition to other types of memberships.
   * If a filter is set, invited memberships that don't match the filter criteria
   * aren't returned. Currently requires [user
   * authentication](https://developers.google.com/workspace/chat/authenticate-
   * authorize-chat-user).
   * @return ListMembershipsResponse
   * @throws \Google\Service\Exception
   */
  public function listSpacesMembers($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListMembershipsResponse::class);
  }
  /**
   * Updates a membership. For an example, see [Update a user's membership in a
   * space](https://developers.google.com/workspace/chat/update-members). Requires
   * [user
   * authentication](https://developers.google.com/workspace/chat/authenticate-
   * authorize-chat-user). (members.patch)
   *
   * @param string $name Resource name of the membership, assigned by the server.
   * Format: `spaces/{space}/members/{member}`
   * @param Membership $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string updateMask Required. The field paths to update. Separate
   * multiple values with commas or use `*` to update all field paths. Currently
   * supported field paths: - `role`
   * @return Membership
   * @throws \Google\Service\Exception
   */
  public function patch($name, Membership $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Membership::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SpacesMembers::class, 'Google_Service_HangoutsChat_Resource_SpacesMembers');
