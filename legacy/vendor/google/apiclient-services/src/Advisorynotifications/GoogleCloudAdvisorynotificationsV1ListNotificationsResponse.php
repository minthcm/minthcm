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

class GoogleCloudAdvisorynotificationsV1ListNotificationsResponse extends \Google\Collection
{
  protected $collection_key = 'notifications';
  /**
   * @var string
   */
  public $nextPageToken;
  protected $notificationsType = GoogleCloudAdvisorynotificationsV1Notification::class;
  protected $notificationsDataType = 'array';
  /**
   * @var int
   */
  public $totalSize;

  /**
   * @param string
   */
  public function setNextPageToken($nextPageToken)
  {
    $this->nextPageToken = $nextPageToken;
  }
  /**
   * @return string
   */
  public function getNextPageToken()
  {
    return $this->nextPageToken;
  }
  /**
   * @param GoogleCloudAdvisorynotificationsV1Notification[]
   */
  public function setNotifications($notifications)
  {
    $this->notifications = $notifications;
  }
  /**
   * @return GoogleCloudAdvisorynotificationsV1Notification[]
   */
  public function getNotifications()
  {
    return $this->notifications;
  }
  /**
   * @param int
   */
  public function setTotalSize($totalSize)
  {
    $this->totalSize = $totalSize;
  }
  /**
   * @return int
   */
  public function getTotalSize()
  {
    return $this->totalSize;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAdvisorynotificationsV1ListNotificationsResponse::class, 'Google_Service_Advisorynotifications_GoogleCloudAdvisorynotificationsV1ListNotificationsResponse');
