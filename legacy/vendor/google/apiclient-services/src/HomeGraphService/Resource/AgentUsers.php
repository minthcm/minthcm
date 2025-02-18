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

namespace Google\Service\HomeGraphService\Resource;

use Google\Service\HomeGraphService\HomegraphEmpty;

/**
 * The "agentUsers" collection of methods.
 * Typical usage is:
 *  <code>
 *   $homegraphService = new Google\Service\HomeGraphService(...);
 *   $agentUsers = $homegraphService->agentUsers;
 *  </code>
 */
class AgentUsers extends \Google\Service\Resource
{
  /**
   * Unlinks the given third-party user from your smart home Action. All data
   * related to this user will be deleted. For more details on how users link
   * their accounts, see [fulfillment and
   * authentication](https://developers.home.google.com/cloud-to-
   * cloud/primer/fulfillment). The third-party user's identity is passed in via
   * the `agent_user_id` (see DeleteAgentUserRequest). This request must be
   * authorized using service account credentials from your Actions console
   * project. (agentUsers.delete)
   *
   * @param string $agentUserId Required. Third-party user ID.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string requestId Request ID used for debugging.
   * @return HomegraphEmpty
   * @throws \Google\Service\Exception
   */
  public function delete($agentUserId, $optParams = [])
  {
    $params = ['agentUserId' => $agentUserId];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], HomegraphEmpty::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AgentUsers::class, 'Google_Service_HomeGraphService_Resource_AgentUsers');
