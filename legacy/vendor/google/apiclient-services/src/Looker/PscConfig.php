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

namespace Google\Service\Looker;

class PscConfig extends \Google\Collection
{
  protected $collection_key = 'serviceAttachments';
  /**
   * @var string[]
   */
  public $allowedVpcs;
  /**
   * @var string
   */
  public $lookerServiceAttachmentUri;
  protected $serviceAttachmentsType = ServiceAttachment::class;
  protected $serviceAttachmentsDataType = 'array';

  /**
   * @param string[]
   */
  public function setAllowedVpcs($allowedVpcs)
  {
    $this->allowedVpcs = $allowedVpcs;
  }
  /**
   * @return string[]
   */
  public function getAllowedVpcs()
  {
    return $this->allowedVpcs;
  }
  /**
   * @param string
   */
  public function setLookerServiceAttachmentUri($lookerServiceAttachmentUri)
  {
    $this->lookerServiceAttachmentUri = $lookerServiceAttachmentUri;
  }
  /**
   * @return string
   */
  public function getLookerServiceAttachmentUri()
  {
    return $this->lookerServiceAttachmentUri;
  }
  /**
   * @param ServiceAttachment[]
   */
  public function setServiceAttachments($serviceAttachments)
  {
    $this->serviceAttachments = $serviceAttachments;
  }
  /**
   * @return ServiceAttachment[]
   */
  public function getServiceAttachments()
  {
    return $this->serviceAttachments;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(PscConfig::class, 'Google_Service_Looker_PscConfig');
