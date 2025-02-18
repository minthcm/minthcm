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

namespace Google\Service\OSConfig\Resource;

use Google\Service\OSConfig\ListPatchDeploymentsResponse;
use Google\Service\OSConfig\OsconfigEmpty;
use Google\Service\OSConfig\PatchDeployment;
use Google\Service\OSConfig\PausePatchDeploymentRequest;
use Google\Service\OSConfig\ResumePatchDeploymentRequest;

/**
 * The "patchDeployments" collection of methods.
 * Typical usage is:
 *  <code>
 *   $osconfigService = new Google\Service\OSConfig(...);
 *   $patchDeployments = $osconfigService->projects_patchDeployments;
 *  </code>
 */
class ProjectsPatchDeployments extends \Google\Service\Resource
{
  /**
   * Create an OS Config patch deployment. (patchDeployments.create)
   *
   * @param string $parent Required. The project to apply this patch deployment to
   * in the form `projects`.
   * @param PatchDeployment $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string patchDeploymentId Required. A name for the patch deployment
   * in the project. When creating a name the following rules apply: * Must
   * contain only lowercase letters, numbers, and hyphens. * Must start with a
   * letter. * Must be between 1-63 characters. * Must end with a number or a
   * letter. * Must be unique within the project.
   * @return PatchDeployment
   * @throws \Google\Service\Exception
   */
  public function create($parent, PatchDeployment $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], PatchDeployment::class);
  }
  /**
   * Delete an OS Config patch deployment. (patchDeployments.delete)
   *
   * @param string $name Required. The resource name of the patch deployment in
   * the form `projects/patchDeployments`.
   * @param array $optParams Optional parameters.
   * @return OsconfigEmpty
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], OsconfigEmpty::class);
  }
  /**
   * Get an OS Config patch deployment. (patchDeployments.get)
   *
   * @param string $name Required. The resource name of the patch deployment in
   * the form `projects/patchDeployments`.
   * @param array $optParams Optional parameters.
   * @return PatchDeployment
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], PatchDeployment::class);
  }
  /**
   * Get a page of OS Config patch deployments.
   * (patchDeployments.listProjectsPatchDeployments)
   *
   * @param string $parent Required. The resource name of the parent in the form
   * `projects`.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int pageSize Optional. The maximum number of patch deployments to
   * return. Default is 100.
   * @opt_param string pageToken Optional. A pagination token returned from a
   * previous call to ListPatchDeployments that indicates where this listing
   * should continue from.
   * @return ListPatchDeploymentsResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsPatchDeployments($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListPatchDeploymentsResponse::class);
  }
  /**
   * Update an OS Config patch deployment. (patchDeployments.patch)
   *
   * @param string $name Unique name for the patch deployment resource in a
   * project. The patch deployment name is in the form:
   * `projects/{project_id}/patchDeployments/{patch_deployment_id}`. This field is
   * ignored when you create a new patch deployment.
   * @param PatchDeployment $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string updateMask Optional. Field mask that controls which fields
   * of the patch deployment should be updated.
   * @return PatchDeployment
   * @throws \Google\Service\Exception
   */
  public function patch($name, PatchDeployment $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], PatchDeployment::class);
  }
  /**
   * Change state of patch deployment to "PAUSED". Patch deployment in paused
   * state doesn't generate patch jobs. (patchDeployments.pause)
   *
   * @param string $name Required. The resource name of the patch deployment in
   * the form `projects/patchDeployments`.
   * @param PausePatchDeploymentRequest $postBody
   * @param array $optParams Optional parameters.
   * @return PatchDeployment
   * @throws \Google\Service\Exception
   */
  public function pause($name, PausePatchDeploymentRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('pause', [$params], PatchDeployment::class);
  }
  /**
   * Change state of patch deployment back to "ACTIVE". Patch deployment in active
   * state continues to generate patch jobs. (patchDeployments.resume)
   *
   * @param string $name Required. The resource name of the patch deployment in
   * the form `projects/patchDeployments`.
   * @param ResumePatchDeploymentRequest $postBody
   * @param array $optParams Optional parameters.
   * @return PatchDeployment
   * @throws \Google\Service\Exception
   */
  public function resume($name, ResumePatchDeploymentRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('resume', [$params], PatchDeployment::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsPatchDeployments::class, 'Google_Service_OSConfig_Resource_ProjectsPatchDeployments');
