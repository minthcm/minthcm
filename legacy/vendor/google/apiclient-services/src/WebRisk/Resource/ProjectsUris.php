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

namespace Google\Service\WebRisk\Resource;

use Google\Service\WebRisk\GoogleCloudWebriskV1SubmitUriRequest;
use Google\Service\WebRisk\GoogleLongrunningOperation;

/**
 * The "uris" collection of methods.
 * Typical usage is:
 *  <code>
 *   $webriskService = new Google\Service\WebRisk(...);
 *   $uris = $webriskService->projects_uris;
 *  </code>
 */
class ProjectsUris extends \Google\Service\Resource
{
  /**
   * Submits a URI suspected of containing malicious content to be reviewed.
   * Returns a google.longrunning.Operation which, once the review is complete, is
   * updated with its result. You can use the [Pub/Sub API]
   * (https://cloud.google.com/pubsub) to receive notifications for the returned
   * Operation. If the result verifies the existence of malicious content, the
   * site will be added to the [Google's Social Engineering lists]
   * (https://support.google.com/webmasters/answer/6350487/) in order to protect
   * users that could get exposed to this threat in the future. Only allowlisted
   * projects can use this method during Early Access. Please reach out to Sales
   * or your customer engineer to obtain access. (uris.submit)
   *
   * @param string $parent Required. The name of the project that is making the
   * submission. This string is in the format "projects/{project_number}".
   * @param GoogleCloudWebriskV1SubmitUriRequest $postBody
   * @param array $optParams Optional parameters.
   * @return GoogleLongrunningOperation
   */
  public function submit($parent, GoogleCloudWebriskV1SubmitUriRequest $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('submit', [$params], GoogleLongrunningOperation::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsUris::class, 'Google_Service_WebRisk_Resource_ProjectsUris');
