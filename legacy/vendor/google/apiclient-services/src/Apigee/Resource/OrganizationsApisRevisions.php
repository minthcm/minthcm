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
use Google\Service\Apigee\GoogleCloudApigeeV1ApiProxyRevision;

/**
 * The "revisions" collection of methods.
 * Typical usage is:
 *  <code>
 *   $apigeeService = new Google\Service\Apigee(...);
 *   $revisions = $apigeeService->organizations_apis_revisions;
 *  </code>
 */
class OrganizationsApisRevisions extends \Google\Service\Resource
{
  /**
   * Deletes an API proxy revision and all policies, resources, endpoints, and
   * revisions associated with it. The API proxy revision must be undeployed
   * before you can delete it. (revisions.delete)
   *
   * @param string $name Required. API proxy revision in the following format:
   * `organizations/{org}/apis/{api}/revisions/{rev}`
   * @param array $optParams Optional parameters.
   * @return GoogleCloudApigeeV1ApiProxyRevision
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], GoogleCloudApigeeV1ApiProxyRevision::class);
  }
  /**
   * Gets an API proxy revision. To download the API proxy configuration bundle
   * for the specified revision as a zip file, set the `format` query parameter to
   * `bundle`. If you are using curl, specify `-o filename.zip` to save the output
   * to a file; otherwise, it displays to `stdout`. Then, develop the API proxy
   * configuration locally and upload the updated API proxy configuration
   * revision, as described in [updateApiProxyRevision](updateApiProxyRevision).
   * (revisions.get)
   *
   * @param string $name Required. API proxy revision in the following format:
   * `organizations/{org}/apis/{api}/revisions/{rev}`
   * @param array $optParams Optional parameters.
   *
   * @opt_param string format Format used when downloading the API proxy
   * configuration revision. Set to `bundle` to download the API proxy
   * configuration revision as a zip file.
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
   * Updates an existing API proxy revision by uploading the API proxy
   * configuration bundle as a zip file from your local machine. You can update
   * only API proxy revisions that have never been deployed. After deployment, an
   * API proxy revision becomes immutable, even if it is undeployed. Set the
   * `Content-Type` header to either `multipart/form-data` or `application/octet-
   * stream`. (revisions.updateApiProxyRevision)
   *
   * @param string $name Required. API proxy revision to update in the following
   * format: `organizations/{org}/apis/{api}/revisions/{rev}`
   * @param GoogleApiHttpBody $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool validate Ignored. All uploads are validated regardless of the
   * value of this field. Maintained for compatibility with Apigee Edge API.
   * @return GoogleCloudApigeeV1ApiProxyRevision
   * @throws \Google\Service\Exception
   */
  public function updateApiProxyRevision($name, GoogleApiHttpBody $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('updateApiProxyRevision', [$params], GoogleCloudApigeeV1ApiProxyRevision::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(OrganizationsApisRevisions::class, 'Google_Service_Apigee_Resource_OrganizationsApisRevisions');
