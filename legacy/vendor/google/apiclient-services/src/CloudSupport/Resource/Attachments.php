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

namespace Google\Service\CloudSupport\Resource;

use Google\Service\CloudSupport\Attachment;
use Google\Service\CloudSupport\CreateAttachmentRequest;

/**
 * The "attachments" collection of methods.
 * Typical usage is:
 *  <code>
 *   $cloudsupportService = new Google\Service\CloudSupport(...);
 *   $attachments = $cloudsupportService->attachments;
 *  </code>
 */
class Attachments extends \Google\Service\Resource
{
  /**
   * Create a file attachment on a case or Cloud resource. The attachment object
   * must have the following fields set: filename. (attachments.create)
   *
   * @param string $parent Required. The resource name of the case (or case
   * parent) to which the attachment should be attached.
   * @param CreateAttachmentRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Attachment
   */
  public function create($parent, CreateAttachmentRequest $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Attachment::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Attachments::class, 'Google_Service_CloudSupport_Resource_Attachments');
