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

namespace Google\Service\CloudIAP\Resource;

use Google\Service\CloudIAP\IapEmpty;
use Google\Service\CloudIAP\IdentityAwareProxyClient;
use Google\Service\CloudIAP\ListIdentityAwareProxyClientsResponse;
use Google\Service\CloudIAP\ResetIdentityAwareProxyClientSecretRequest;

/**
 * The "identityAwareProxyClients" collection of methods.
 * Typical usage is:
 *  <code>
 *   $iapService = new Google\Service\CloudIAP(...);
 *   $identityAwareProxyClients = $iapService->projects_brands_identityAwareProxyClients;
 *  </code>
 */
class ProjectsBrandsIdentityAwareProxyClients extends \Google\Service\Resource
{
  /**
   * Creates an Identity Aware Proxy (IAP) OAuth client. The client is owned by
   * IAP. Requires that the brand for the project exists and that it is set for
   * internal-only use. (identityAwareProxyClients.create)
   *
   * @param string $parent Required. Path to create the client in. In the
   * following format: projects/{project_number/id}/brands/{brand}. The project
   * must belong to a G Suite account.
   * @param IdentityAwareProxyClient $postBody
   * @param array $optParams Optional parameters.
   * @return IdentityAwareProxyClient
   * @throws \Google\Service\Exception
   */
  public function create($parent, IdentityAwareProxyClient $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], IdentityAwareProxyClient::class);
  }
  /**
   * Deletes an Identity Aware Proxy (IAP) OAuth client. Useful for removing
   * obsolete clients, managing the number of clients in a given project, and
   * cleaning up after tests. Requires that the client is owned by IAP.
   * (identityAwareProxyClients.delete)
   *
   * @param string $name Required. Name of the Identity Aware Proxy client to be
   * deleted. In the following format: projects/{project_number/id}/brands/{brand}
   * /identityAwareProxyClients/{client_id}.
   * @param array $optParams Optional parameters.
   * @return IapEmpty
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], IapEmpty::class);
  }
  /**
   * Retrieves an Identity Aware Proxy (IAP) OAuth client. Requires that the
   * client is owned by IAP. (identityAwareProxyClients.get)
   *
   * @param string $name Required. Name of the Identity Aware Proxy client to be
   * fetched. In the following format: projects/{project_number/id}/brands/{brand}
   * /identityAwareProxyClients/{client_id}.
   * @param array $optParams Optional parameters.
   * @return IdentityAwareProxyClient
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], IdentityAwareProxyClient::class);
  }
  /**
   * Lists the existing clients for the brand.
   * (identityAwareProxyClients.listProjectsBrandsIdentityAwareProxyClients)
   *
   * @param string $parent Required. Full brand path. In the following format:
   * projects/{project_number/id}/brands/{brand}.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int pageSize The maximum number of clients to return. The service
   * may return fewer than this value. If unspecified, at most 100 clients will be
   * returned. The maximum value is 1000; values above 1000 will be coerced to
   * 1000.
   * @opt_param string pageToken A page token, received from a previous
   * `ListIdentityAwareProxyClients` call. Provide this to retrieve the subsequent
   * page. When paginating, all other parameters provided to
   * `ListIdentityAwareProxyClients` must match the call that provided the page
   * token.
   * @return ListIdentityAwareProxyClientsResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsBrandsIdentityAwareProxyClients($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListIdentityAwareProxyClientsResponse::class);
  }
  /**
   * Resets an Identity Aware Proxy (IAP) OAuth client secret. Useful if the
   * secret was compromised. Requires that the client is owned by IAP.
   * (identityAwareProxyClients.resetSecret)
   *
   * @param string $name Required. Name of the Identity Aware Proxy client to that
   * will have its secret reset. In the following format: projects/{project_number
   * /id}/brands/{brand}/identityAwareProxyClients/{client_id}.
   * @param ResetIdentityAwareProxyClientSecretRequest $postBody
   * @param array $optParams Optional parameters.
   * @return IdentityAwareProxyClient
   * @throws \Google\Service\Exception
   */
  public function resetSecret($name, ResetIdentityAwareProxyClientSecretRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('resetSecret', [$params], IdentityAwareProxyClient::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsBrandsIdentityAwareProxyClients::class, 'Google_Service_CloudIAP_Resource_ProjectsBrandsIdentityAwareProxyClients');
