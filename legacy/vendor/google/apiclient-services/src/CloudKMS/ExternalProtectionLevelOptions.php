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

namespace Google\Service\CloudKMS;

class ExternalProtectionLevelOptions extends \Google\Model
{
  /**
   * @var string
   */
  public $ekmConnectionKeyPath;
  /**
   * @var string
   */
  public $externalKeyUri;

  /**
   * @param string
   */
  public function setEkmConnectionKeyPath($ekmConnectionKeyPath)
  {
    $this->ekmConnectionKeyPath = $ekmConnectionKeyPath;
  }
  /**
   * @return string
   */
  public function getEkmConnectionKeyPath()
  {
    return $this->ekmConnectionKeyPath;
  }
  /**
   * @param string
   */
  public function setExternalKeyUri($externalKeyUri)
  {
    $this->externalKeyUri = $externalKeyUri;
  }
  /**
   * @return string
   */
  public function getExternalKeyUri()
  {
    return $this->externalKeyUri;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ExternalProtectionLevelOptions::class, 'Google_Service_CloudKMS_ExternalProtectionLevelOptions');
