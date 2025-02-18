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

namespace Google\Service\Batch;

class AgentKMSEnvMap extends \Google\Model
{
  /**
   * @var string
   */
  public $cipherText;
  /**
   * @var string
   */
  public $keyName;

  /**
   * @param string
   */
  public function setCipherText($cipherText)
  {
    $this->cipherText = $cipherText;
  }
  /**
   * @return string
   */
  public function getCipherText()
  {
    return $this->cipherText;
  }
  /**
   * @param string
   */
  public function setKeyName($keyName)
  {
    $this->keyName = $keyName;
  }
  /**
   * @return string
   */
  public function getKeyName()
  {
    return $this->keyName;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AgentKMSEnvMap::class, 'Google_Service_Batch_AgentKMSEnvMap');
