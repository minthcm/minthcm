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

namespace Google\Service\Apigee\Resource;

use Google\Service\Apigee\GoogleApiHttpBody;
use Google\Service\Apigee\GoogleCloudApigeeV1SharedFlowRevision;

/**
 * The "revisions" collection of methods.
 * Typical usage is:
 *  <code>
 *   $apigeeService = new Google\Service\Apigee(...);
 *   $revisions = $apigeeService->organizations_sharedflows_revisions;
 *  </code>
 */
class OrganizationsSharedflowsRevisions extends \Google\Service\Resource
{
  /**
   * Deletes a shared flow and all associated policies, resources, and revisions.
   * You must undeploy the shared flow before deleting it. (revisions.delete)
   *
   * @param string $name Required. The name of the shared flow revision to delete.
   * Must be of the form: `organizations/{organization_id}/sharedflows/{shared_flo
   * w_id}/revisions/{revision_id}`
   * @param array $optParams Optional parameters.
   * @return GoogleCloudApigeeV1SharedFlowRevision
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], GoogleCloudApigeeV1SharedFlowRevision::class);
  }
  /**
   * Gets a revision of a shared flow. To download the shared flow configuration
   * bundle for the specified revision as a zip file, set the `format` query
   * parameter to `bundle`. If you are using curl, specify `-o filename.zip` to
   * save the output to a file; otherwise, it displays to `stdout`. Then, develop
   * the shared flow configuration locally and upload the updated sharedFlow
   * configuration revision, as described in
   * [updateSharedFlowRevision](updateSharedFlowRevision). (revisions.get)
   *
   * @param string $name Required. The name of the shared flow revision to get.
   * Must be of the form: `organizations/{organization_id}/sharedflows/{shared_flo
   * w_id}/revisions/{revision_id}`
   * @param array $optParams Optional parameters.
   *
   * @opt_param string format Specify `bundle` to export the contents of the
   * shared flow bundle. Otherwise, the bundle metadata is returned.
   * @return GoogleApiHttpBody
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], GoogleApiHttpBody::class);
  }
  /**
   * Updates a shared flow revision. This operation is only allowed on revisions
   * which have never been deployed. After deployment a revision becomes
   * immutable, even if it becomes undeployed. The payload is a ZIP-formatted
   * shared flow. Content type must be either multipart/form-data or
   * application/octet-stream. (revisions.updateSharedFlowRevision)
   *
   * @param string $name Required. The name of the shared flow revision to update.
   * Must be of the form: `organizations/{organization_id}/sharedflows/{shared_flo
   * w_id}/revisions/{revision_id}`
   * @param GoogleApiHttpBody $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool validate Ignored. All uploads are validated regardless of the
   * value of this field. It is kept for compatibility with existing APIs. Must be
   * `true` or `false` if provided.
   * @return GoogleCloudApigeeV1SharedFlowRevision
   * @throws \Google\Service\Exception
   */
  public function updateSharedFlowRevision($name, GoogleApiHttpBody $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('updateSharedFlowRevision', [$params], GoogleCloudApigeeV1SharedFlowRevision::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(OrganizationsSharedflowsRevisions::class, 'Google_Service_Apigee_Resource_OrganizationsSharedflowsRevisions');
