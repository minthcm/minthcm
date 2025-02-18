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

namespace Google\Service\OrgPolicyAPI\Resource;

use Google\Service\OrgPolicyAPI\GoogleCloudOrgpolicyV2ListConstraintsResponse;

/**
 * The "constraints" collection of methods.
 * Typical usage is:
 *  <code>
 *   $orgpolicyService = new Google\Service\OrgPolicyAPI(...);
 *   $constraints = $orgpolicyService->folders_constraints;
 *  </code>
 */
class FoldersConstraints extends \Google\Service\Resource
{
  /**
   * Lists constraints that could be applied on the specified resource.
   * (constraints.listFoldersConstraints)
   *
   * @param string $parent Required. The Google Cloud resource that parents the
   * constraint. Must be in one of the following forms: *
   * `projects/{project_number}` * `projects/{project_id}` * `folders/{folder_id}`
   * * `organizations/{organization_id}`
   * @param array $optParams Optional parameters.
   *
   * @opt_param int pageSize Size of the pages to be returned. This is currently
   * unsupported and will be ignored. The server may at any point start using this
   * field to limit page size.
   * @opt_param string pageToken Page token used to retrieve the next page. This
   * is currently unsupported and will be ignored. The server may at any point
   * start using this field.
   * @return GoogleCloudOrgpolicyV2ListConstraintsResponse
   * @throws \Google\Service\Exception
   */
  public function listFoldersConstraints($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], GoogleCloudOrgpolicyV2ListConstraintsResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(FoldersConstraints::class, 'Google_Service_OrgPolicyAPI_Resource_FoldersConstraints');
