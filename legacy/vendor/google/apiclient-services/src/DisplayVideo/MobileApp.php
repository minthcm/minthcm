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

namespace Google\Service\DisplayVideo;

class MobileApp extends \Google\Model
{
  /**
   * @var string
   */
  public $appId;
  /**
   * @var string
   */
  public $displayName;
  /**
   * @var string
   */
  public $platform;
  /**
   * @var string
   */
  public $publisher;

  /**
   * @param string
   */
  public function setAppId($appId)
  {
    $this->appId = $appId;
  }
  /**
   * @return string
   */
  public function getAppId()
  {
    return $this->appId;
  }
  /**
   * @param string
   */
  public function setDisplayName($displayName)
  {
    $this->displayName = $displayName;
  }
  /**
   * @return string
   */
  public function getDisplayName()
  {
    return $this->displayName;
  }
  /**
   * @param string
   */
  public function setPlatform($platform)
  {
    $this->platform = $platform;
  }
  /**
   * @return string
   */
  public function getPlatform()
  {
    return $this->platform;
  }
  /**
   * @param string
   */
  public function setPublisher($publisher)
  {
    $this->publisher = $publisher;
  }
  /**
   * @return string
   */
  public function getPublisher()
  {
    return $this->publisher;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(MobileApp::class, 'Google_Service_DisplayVideo_MobileApp');
