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

namespace Google\Service\AccessApproval\Resource;

use Google\Service\AccessApproval\AccessApprovalServiceAccount;
use Google\Service\AccessApproval\AccessApprovalSettings;
use Google\Service\AccessApproval\AccessapprovalEmpty;

/**
 * The "organizations" collection of methods.
 * Typical usage is:
 *  <code>
 *   $accessapprovalService = new Google\Service\AccessApproval(...);
 *   $organizations = $accessapprovalService->organizations;
 *  </code>
 */
class Organizations extends \Google\Service\Resource
{
  /**
   * Deletes the settings associated with a project, folder, or organization. This
   * will have the effect of disabling Access Approval for the project, folder, or
   * organization, but only if all ancestors also have Access Approval disabled.
   * If Access Approval is enabled at a higher level of the hierarchy, then Access
   * Approval will still be enabled at this level as the settings are inherited.
   * (organizations.deleteAccessApprovalSettings)
   *
   * @param string $name Name of the AccessApprovalSettings to delete.
   * @param array $optParams Optional parameters.
   * @return AccessapprovalEmpty
   * @throws \Google\Service\Exception
   */
  public function deleteAccessApprovalSettings($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('deleteAccessApprovalSettings', [$params], AccessapprovalEmpty::class);
  }
  /**
   * Gets the settings associated with a project, folder, or organization.
   * (organizations.getAccessApprovalSettings)
   *
   * @param string $name The name of the AccessApprovalSettings to retrieve.
   * Format: "{projects|folders|organizations}/{id}/accessApprovalSettings"
   * @param array $optParams Optional parameters.
   * @return AccessApprovalSettings
   * @throws \Google\Service\Exception
   */
  public function getAccessApprovalSettings($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('getAccessApprovalSettings', [$params], AccessApprovalSettings::class);
  }
  /**
   * Retrieves the service account that is used by Access Approval to access KMS
   * keys for signing approved approval requests.
   * (organizations.getServiceAccount)
   *
   * @param string $name Name of the AccessApprovalServiceAccount to retrieve.
   * @param array $optParams Optional parameters.
   * @return AccessApprovalServiceAccount
   * @throws \Google\Service\Exception
   */
  public function getServiceAccount($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('getServiceAccount', [$params], AccessApprovalServiceAccount::class);
  }
  /**
   * Updates the settings associated with a project, folder, or organization.
   * Settings to update are determined by the value of field_mask.
   * (organizations.updateAccessApprovalSettings)
   *
   * @param string $name The resource name of the settings. Format is one of: *
   * "projects/{project}/accessApprovalSettings" *
   * "folders/{folder}/accessApprovalSettings" *
   * "organizations/{organization}/accessApprovalSettings"
   * @param AccessApprovalSettings $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string updateMask The update mask applies to the settings. Only
   * the top level fields of AccessApprovalSettings (notification_emails &
   * enrolled_services) are supported. For each field, if it is included, the
   * currently stored value will be entirely overwritten with the value of the
   * field passed in this request. For the `FieldMask` definition, see
   * https://developers.google.com/protocol-
   * buffers/docs/reference/google.protobuf#fieldmask If this field is left unset,
   * only the notification_emails field will be updated.
   * @return AccessApprovalSettings
   * @throws \Google\Service\Exception
   */
  public function updateAccessApprovalSettings($name, AccessApprovalSettings $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('updateAccessApprovalSettings', [$params], AccessApprovalSettings::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Organizations::class, 'Google_Service_AccessApproval_Resource_Organizations');
