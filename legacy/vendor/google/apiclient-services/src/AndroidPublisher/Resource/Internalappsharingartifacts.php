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

namespace Google\Service\AndroidPublisher\Resource;

use Google\Service\AndroidPublisher\InternalAppSharingArtifact;

/**
 * The "internalappsharingartifacts" collection of methods.
 * Typical usage is:
 *  <code>
 *   $androidpublisherService = new Google\Service\AndroidPublisher(...);
 *   $internalappsharingartifacts = $androidpublisherService->internalappsharingartifacts;
 *  </code>
 */
class Internalappsharingartifacts extends \Google\Service\Resource
{
  /**
   * Uploads an APK to internal app sharing. If you are using the Google API
   * client libraries, please increase the timeout of the http request before
   * calling this endpoint (a timeout of 2 minutes is recommended). See [Timeouts
   * and Errors](https://developers.google.com/api-client-library/java/google-api-
   * java-client/errors) for an example in java.
   * (internalappsharingartifacts.uploadapk)
   *
   * @param string $packageName Package name of the app.
   * @param array $optParams Optional parameters.
   * @return InternalAppSharingArtifact
   * @throws \Google\Service\Exception
   */
  public function uploadapk($packageName, $optParams = [])
  {
    $params = ['packageName' => $packageName];
    $params = array_merge($params, $optParams);
    return $this->call('uploadapk', [$params], InternalAppSharingArtifact::class);
  }
  /**
   * Uploads an app bundle to internal app sharing. If you are using the Google
   * API client libraries, please increase the timeout of the http request before
   * calling this endpoint (a timeout of 2 minutes is recommended). See [Timeouts
   * and Errors](https://developers.google.com/api-client-library/java/google-api-
   * java-client/errors) for an example in java.
   * (internalappsharingartifacts.uploadbundle)
   *
   * @param string $packageName Package name of the app.
   * @param array $optParams Optional parameters.
   * @return InternalAppSharingArtifact
   * @throws \Google\Service\Exception
   */
  public function uploadbundle($packageName, $optParams = [])
  {
    $params = ['packageName' => $packageName];
    $params = array_merge($params, $optParams);
    return $this->call('uploadbundle', [$params], InternalAppSharingArtifact::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Internalappsharingartifacts::class, 'Google_Service_AndroidPublisher_Resource_Internalappsharingartifacts');
