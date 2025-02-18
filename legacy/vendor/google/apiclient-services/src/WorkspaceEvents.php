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

namespace Google\Service;

use Google\Client;

/**
 * Service definition for WorkspaceEvents (v1).
 *
 * <p>
 * The Google Workspace Events API lets you subscribe to events and manage
 * change notifications across Google Workspace applications.</p>
 *
 * <p>
 * For more information about this service, see the API
 * <a href="https://developers.google.com/workspace/events" target="_blank">Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */
class WorkspaceEvents extends \Google\Service
{
  /** Private Service: https://www.googleapis.com/auth/chat.bot. */
  const CHAT_BOT =
      "https://www.googleapis.com/auth/chat.bot";
  /** View, add, update, and remove members from conversations in Google Chat. */
  const CHAT_MEMBERSHIPS =
      "https://www.googleapis.com/auth/chat.memberships";
  /** View members in Google Chat conversations.. */
  const CHAT_MEMBERSHIPS_READONLY =
      "https://www.googleapis.com/auth/chat.memberships.readonly";
  /** View, compose, send, update, and delete messages, and add, view, and delete reactions to messages.. */
  const CHAT_MESSAGES =
      "https://www.googleapis.com/auth/chat.messages";
  /** View, add, and delete reactions to messages in Google Chat. */
  const CHAT_MESSAGES_REACTIONS =
      "https://www.googleapis.com/auth/chat.messages.reactions";
  /** View reactions to messages in Google Chat. */
  const CHAT_MESSAGES_REACTIONS_READONLY =
      "https://www.googleapis.com/auth/chat.messages.reactions.readonly";
  /** View messages and reactions in Google Chat. */
  const CHAT_MESSAGES_READONLY =
      "https://www.googleapis.com/auth/chat.messages.readonly";
  /** Create conversations and spaces and see or edit metadata (including history settings and access settings) in Google Chat. */
  const CHAT_SPACES =
      "https://www.googleapis.com/auth/chat.spaces";
  /** View chat and spaces in Google Chat. */
  const CHAT_SPACES_READONLY =
      "https://www.googleapis.com/auth/chat.spaces.readonly";
  /** Create, edit, and see information about your Google Meet conferences created by the app.. */
  const MEETINGS_SPACE_CREATED =
      "https://www.googleapis.com/auth/meetings.space.created";
  /** Read information about any of your Google Meet conferences. */
  const MEETINGS_SPACE_READONLY =
      "https://www.googleapis.com/auth/meetings.space.readonly";

  public $operations;
  public $subscriptions;
  public $rootUrlTemplate;

  /**
   * Constructs the internal representation of the WorkspaceEvents service.
   *
   * @param Client|array $clientOrConfig The client used to deliver requests, or a
   *                                     config array to pass to a new Client instance.
   * @param string $rootUrl The root URL used for requests to the service.
   */
  public function __construct($clientOrConfig = [], $rootUrl = null)
  {
    parent::__construct($clientOrConfig);
    $this->rootUrl = $rootUrl ?: 'https://workspaceevents.googleapis.com/';
    $this->rootUrlTemplate = $rootUrl ?: 'https://workspaceevents.UNIVERSE_DOMAIN/';
    $this->servicePath = '';
    $this->batchPath = 'batch';
    $this->version = 'v1';
    $this->serviceName = 'workspaceevents';

    $this->operations = new WorkspaceEvents\Resource\Operations(
        $this,
        $this->serviceName,
        'operations',
        [
          'methods' => [
            'get' => [
              'path' => 'v1/{+name}',
              'httpMethod' => 'GET',
              'parameters' => [
                'name' => [
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ],
              ],
            ],
          ]
        ]
    );
    $this->subscriptions = new WorkspaceEvents\Resource\Subscriptions(
        $this,
        $this->serviceName,
        'subscriptions',
        [
          'methods' => [
            'create' => [
              'path' => 'v1/subscriptions',
              'httpMethod' => 'POST',
              'parameters' => [
                'validateOnly' => [
                  'location' => 'query',
                  'type' => 'boolean',
                ],
              ],
            ],'delete' => [
              'path' => 'v1/{+name}',
              'httpMethod' => 'DELETE',
              'parameters' => [
                'name' => [
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ],
                'allowMissing' => [
                  'location' => 'query',
                  'type' => 'boolean',
                ],
                'etag' => [
                  'location' => 'query',
                  'type' => 'string',
                ],
                'validateOnly' => [
                  'location' => 'query',
                  'type' => 'boolean',
                ],
              ],
            ],'get' => [
              'path' => 'v1/{+name}',
              'httpMethod' => 'GET',
              'parameters' => [
                'name' => [
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ],
              ],
            ],'list' => [
              'path' => 'v1/subscriptions',
              'httpMethod' => 'GET',
              'parameters' => [
                'filter' => [
                  'location' => 'query',
                  'type' => 'string',
                ],
                'pageSize' => [
                  'location' => 'query',
                  'type' => 'integer',
                ],
                'pageToken' => [
                  'location' => 'query',
                  'type' => 'string',
                ],
              ],
            ],'patch' => [
              'path' => 'v1/{+name}',
              'httpMethod' => 'PATCH',
              'parameters' => [
                'name' => [
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ],
                'updateMask' => [
                  'location' => 'query',
                  'type' => 'string',
                ],
                'validateOnly' => [
                  'location' => 'query',
                  'type' => 'boolean',
                ],
              ],
            ],'reactivate' => [
              'path' => 'v1/{+name}:reactivate',
              'httpMethod' => 'POST',
              'parameters' => [
                'name' => [
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ],
              ],
            ],
          ]
        ]
    );
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(WorkspaceEvents::class, 'Google_Service_WorkspaceEvents');
