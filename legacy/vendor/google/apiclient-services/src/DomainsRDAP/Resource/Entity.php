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

namespace Google\Service\DomainsRDAP\Resource;

use Google\Service\DomainsRDAP\RdapResponse;

/**
 * The "entity" collection of methods.
 * Typical usage is:
 *  <code>
 *   $domainsrdapService = new Google\Service\DomainsRDAP(...);
 *   $entity = $domainsrdapService->entity;
 *  </code>
 */
class Entity extends \Google\Service\Resource
{
  /**
   * The RDAP API recognizes this command from the RDAP specification but does not
   * support it. The response is a formatted 501 error. (entity.get)
   *
   * @param string $entityId
   * @param array $optParams Optional parameters.
   * @return RdapResponse
   * @throws \Google\Service\Exception
   */
  public function get($entityId, $optParams = [])
  {
    $params = ['entityId' => $entityId];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], RdapResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Entity::class, 'Google_Service_DomainsRDAP_Resource_Entity');
