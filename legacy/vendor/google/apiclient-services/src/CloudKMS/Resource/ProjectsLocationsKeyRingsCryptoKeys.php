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

namespace Google\Service\CloudKMS\Resource;

use Google\Service\CloudKMS\CryptoKey;
use Google\Service\CloudKMS\DecryptRequest;
use Google\Service\CloudKMS\DecryptResponse;
use Google\Service\CloudKMS\EncryptRequest;
use Google\Service\CloudKMS\EncryptResponse;
use Google\Service\CloudKMS\ListCryptoKeysResponse;
use Google\Service\CloudKMS\Policy;
use Google\Service\CloudKMS\SetIamPolicyRequest;
use Google\Service\CloudKMS\TestIamPermissionsRequest;
use Google\Service\CloudKMS\TestIamPermissionsResponse;
use Google\Service\CloudKMS\UpdateCryptoKeyPrimaryVersionRequest;

/**
 * The "cryptoKeys" collection of methods.
 * Typical usage is:
 *  <code>
 *   $cloudkmsService = new Google\Service\CloudKMS(...);
 *   $cryptoKeys = $cloudkmsService->projects_locations_keyRings_cryptoKeys;
 *  </code>
 */
class ProjectsLocationsKeyRingsCryptoKeys extends \Google\Service\Resource
{
  /**
   * Create a new CryptoKey within a KeyRing. CryptoKey.purpose and
   * CryptoKey.version_template.algorithm are required. (cryptoKeys.create)
   *
   * @param string $parent Required. The name of the KeyRing associated with the
   * CryptoKeys.
   * @param CryptoKey $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string cryptoKeyId Required. It must be unique within a KeyRing
   * and match the regular expression `[a-zA-Z0-9_-]{1,63}`
   * @opt_param bool skipInitialVersionCreation If set to true, the request will
   * create a CryptoKey without any CryptoKeyVersions. You must manually call
   * CreateCryptoKeyVersion or ImportCryptoKeyVersion before you can use this
   * CryptoKey.
   * @return CryptoKey
   * @throws \Google\Service\Exception
   */
  public function create($parent, CryptoKey $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], CryptoKey::class);
  }
  /**
   * Decrypts data that was protected by Encrypt. The CryptoKey.purpose must be
   * ENCRYPT_DECRYPT. (cryptoKeys.decrypt)
   *
   * @param string $name Required. The resource name of the CryptoKey to use for
   * decryption. The server will choose the appropriate version.
   * @param DecryptRequest $postBody
   * @param array $optParams Optional parameters.
   * @return DecryptResponse
   * @throws \Google\Service\Exception
   */
  public function decrypt($name, DecryptRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('decrypt', [$params], DecryptResponse::class);
  }
  /**
   * Encrypts data, so that it can only be recovered by a call to Decrypt. The
   * CryptoKey.purpose must be ENCRYPT_DECRYPT. (cryptoKeys.encrypt)
   *
   * @param string $name Required. The resource name of the CryptoKey or
   * CryptoKeyVersion to use for encryption. If a CryptoKey is specified, the
   * server will use its primary version.
   * @param EncryptRequest $postBody
   * @param array $optParams Optional parameters.
   * @return EncryptResponse
   * @throws \Google\Service\Exception
   */
  public function encrypt($name, EncryptRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('encrypt', [$params], EncryptResponse::class);
  }
  /**
   * Returns metadata for a given CryptoKey, as well as its primary
   * CryptoKeyVersion. (cryptoKeys.get)
   *
   * @param string $name Required. The name of the CryptoKey to get.
   * @param array $optParams Optional parameters.
   * @return CryptoKey
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], CryptoKey::class);
  }
  /**
   * Gets the access control policy for a resource. Returns an empty policy if the
   * resource exists and does not have a policy set. (cryptoKeys.getIamPolicy)
   *
   * @param string $resource REQUIRED: The resource for which the policy is being
   * requested. See [Resource
   * names](https://cloud.google.com/apis/design/resource_names) for the
   * appropriate value for this field.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int options.requestedPolicyVersion Optional. The maximum policy
   * version that will be used to format the policy. Valid values are 0, 1, and 3.
   * Requests specifying an invalid value will be rejected. Requests for policies
   * with any conditional role bindings must specify version 3. Policies with no
   * conditional role bindings may specify any valid value or leave the field
   * unset. The policy in the response might use the policy version that you
   * specified, or it might use a lower policy version. For example, if you
   * specify version 3, but the policy has no conditional role bindings, the
   * response uses version 1. To learn which resources support conditions in their
   * IAM policies, see the [IAM
   * documentation](https://cloud.google.com/iam/help/conditions/resource-
   * policies).
   * @return Policy
   * @throws \Google\Service\Exception
   */
  public function getIamPolicy($resource, $optParams = [])
  {
    $params = ['resource' => $resource];
    $params = array_merge($params, $optParams);
    return $this->call('getIamPolicy', [$params], Policy::class);
  }
  /**
   * Lists CryptoKeys. (cryptoKeys.listProjectsLocationsKeyRingsCryptoKeys)
   *
   * @param string $parent Required. The resource name of the KeyRing to list, in
   * the format `projects/locations/keyRings`.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter Optional. Only include resources that match the
   * filter in the response. For more information, see [Sorting and filtering list
   * results](https://cloud.google.com/kms/docs/sorting-and-filtering).
   * @opt_param string orderBy Optional. Specify how the results should be sorted.
   * If not specified, the results will be sorted in the default order. For more
   * information, see [Sorting and filtering list
   * results](https://cloud.google.com/kms/docs/sorting-and-filtering).
   * @opt_param int pageSize Optional. Optional limit on the number of CryptoKeys
   * to include in the response. Further CryptoKeys can subsequently be obtained
   * by including the ListCryptoKeysResponse.next_page_token in a subsequent
   * request. If unspecified, the server will pick an appropriate default.
   * @opt_param string pageToken Optional. Optional pagination token, returned
   * earlier via ListCryptoKeysResponse.next_page_token.
   * @opt_param string versionView The fields of the primary version to include in
   * the response.
   * @return ListCryptoKeysResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsLocationsKeyRingsCryptoKeys($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListCryptoKeysResponse::class);
  }
  /**
   * Update a CryptoKey. (cryptoKeys.patch)
   *
   * @param string $name Output only. The resource name for this CryptoKey in the
   * format `projects/locations/keyRings/cryptoKeys`.
   * @param CryptoKey $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string updateMask Required. List of fields to be updated in this
   * request.
   * @return CryptoKey
   * @throws \Google\Service\Exception
   */
  public function patch($name, CryptoKey $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], CryptoKey::class);
  }
  /**
   * Sets the access control policy on the specified resource. Replaces any
   * existing policy. Can return `NOT_FOUND`, `INVALID_ARGUMENT`, and
   * `PERMISSION_DENIED` errors. (cryptoKeys.setIamPolicy)
   *
   * @param string $resource REQUIRED: The resource for which the policy is being
   * specified. See [Resource
   * names](https://cloud.google.com/apis/design/resource_names) for the
   * appropriate value for this field.
   * @param SetIamPolicyRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Policy
   * @throws \Google\Service\Exception
   */
  public function setIamPolicy($resource, SetIamPolicyRequest $postBody, $optParams = [])
  {
    $params = ['resource' => $resource, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('setIamPolicy', [$params], Policy::class);
  }
  /**
   * Returns permissions that a caller has on the specified resource. If the
   * resource does not exist, this will return an empty set of permissions, not a
   * `NOT_FOUND` error. Note: This operation is designed to be used for building
   * permission-aware UIs and command-line tools, not for authorization checking.
   * This operation may "fail open" without warning.
   * (cryptoKeys.testIamPermissions)
   *
   * @param string $resource REQUIRED: The resource for which the policy detail is
   * being requested. See [Resource
   * names](https://cloud.google.com/apis/design/resource_names) for the
   * appropriate value for this field.
   * @param TestIamPermissionsRequest $postBody
   * @param array $optParams Optional parameters.
   * @return TestIamPermissionsResponse
   * @throws \Google\Service\Exception
   */
  public function testIamPermissions($resource, TestIamPermissionsRequest $postBody, $optParams = [])
  {
    $params = ['resource' => $resource, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('testIamPermissions', [$params], TestIamPermissionsResponse::class);
  }
  /**
   * Update the version of a CryptoKey that will be used in Encrypt. Returns an
   * error if called on a key whose purpose is not ENCRYPT_DECRYPT.
   * (cryptoKeys.updatePrimaryVersion)
   *
   * @param string $name Required. The resource name of the CryptoKey to update.
   * @param UpdateCryptoKeyPrimaryVersionRequest $postBody
   * @param array $optParams Optional parameters.
   * @return CryptoKey
   * @throws \Google\Service\Exception
   */
  public function updatePrimaryVersion($name, UpdateCryptoKeyPrimaryVersionRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('updatePrimaryVersion', [$params], CryptoKey::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsKeyRingsCryptoKeys::class, 'Google_Service_CloudKMS_Resource_ProjectsLocationsKeyRingsCryptoKeys');
