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

namespace Google\Service\Advisorynotifications;

class GoogleCloudAdvisorynotificationsV1Message extends \Google\Collection
{
  protected $collection_key = 'attachments';
  protected $attachmentsType = GoogleCloudAdvisorynotificationsV1Attachment::class;
  protected $attachmentsDataType = 'array';
  protected $bodyType = GoogleCloudAdvisorynotificationsV1MessageBody::class;
  protected $bodyDataType = '';
  /**
   * @var string
   */
  public $createTime;
  /**
   * @var string
   */
  public $localizationTime;

  /**
   * @param GoogleCloudAdvisorynotificationsV1Attachment[]
   */
  public function setAttachments($attachments)
  {
    $this->attachments = $attachments;
  }
  /**
   * @return GoogleCloudAdvisorynotificationsV1Attachment[]
   */
  public function getAttachments()
  {
    return $this->attachments;
  }
  /**
   * @param GoogleCloudAdvisorynotificationsV1MessageBody
   */
  public function setBody(GoogleCloudAdvisorynotificationsV1MessageBody $body)
  {
    $this->body = $body;
  }
  /**
   * @return GoogleCloudAdvisorynotificationsV1MessageBody
   */
  public function getBody()
  {
    return $this->body;
  }
  /**
   * @param string
   */
  public function setCreateTime($createTime)
  {
    $this->createTime = $createTime;
  }
  /**
   * @return string
   */
  public function getCreateTime()
  {
    return $this->createTime;
  }
  /**
   * @param string
   */
  public function setLocalizationTime($localizationTime)
  {
    $this->localizationTime = $localizationTime;
  }
  /**
   * @return string
   */
  public function getLocalizationTime()
  {
    return $this->localizationTime;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAdvisorynotificationsV1Message::class, 'Google_Service_Advisorynotifications_GoogleCloudAdvisorynotificationsV1Message');
