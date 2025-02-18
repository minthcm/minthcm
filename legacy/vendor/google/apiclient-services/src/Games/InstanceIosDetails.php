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

namespace Google\Service\Games;

class InstanceIosDetails extends \Google\Model
{
  /**
   * @var string
   */
  public $bundleIdentifier;
  /**
   * @var string
   */
  public $itunesAppId;
  /**
   * @var string
   */
  public $kind;
  /**
   * @var bool
   */
  public $preferredForIpad;
  /**
   * @var bool
   */
  public $preferredForIphone;
  /**
   * @var bool
   */
  public $supportIpad;
  /**
   * @var bool
   */
  public $supportIphone;

  /**
   * @param string
   */
  public function setBundleIdentifier($bundleIdentifier)
  {
    $this->bundleIdentifier = $bundleIdentifier;
  }
  /**
   * @return string
   */
  public function getBundleIdentifier()
  {
    return $this->bundleIdentifier;
  }
  /**
   * @param string
   */
  public function setItunesAppId($itunesAppId)
  {
    $this->itunesAppId = $itunesAppId;
  }
  /**
   * @return string
   */
  public function getItunesAppId()
  {
    return $this->itunesAppId;
  }
  /**
   * @param string
   */
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  /**
   * @return string
   */
  public function getKind()
  {
    return $this->kind;
  }
  /**
   * @param bool
   */
  public function setPreferredForIpad($preferredForIpad)
  {
    $this->preferredForIpad = $preferredForIpad;
  }
  /**
   * @return bool
   */
  public function getPreferredForIpad()
  {
    return $this->preferredForIpad;
  }
  /**
   * @param bool
   */
  public function setPreferredForIphone($preferredForIphone)
  {
    $this->preferredForIphone = $preferredForIphone;
  }
  /**
   * @return bool
   */
  public function getPreferredForIphone()
  {
    return $this->preferredForIphone;
  }
  /**
   * @param bool
   */
  public function setSupportIpad($supportIpad)
  {
    $this->supportIpad = $supportIpad;
  }
  /**
   * @return bool
   */
  public function getSupportIpad()
  {
    return $this->supportIpad;
  }
  /**
   * @param bool
   */
  public function setSupportIphone($supportIphone)
  {
    $this->supportIphone = $supportIphone;
  }
  /**
   * @return bool
   */
  public function getSupportIphone()
  {
    return $this->supportIphone;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(InstanceIosDetails::class, 'Google_Service_Games_InstanceIosDetails');
