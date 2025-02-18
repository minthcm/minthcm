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

namespace Google\Service\CloudHealthcare;

class ConsentStore extends \Google\Model
{
  /**
   * @var string
   */
  public $defaultConsentTtl;
  /**
   * @var bool
   */
  public $enableConsentCreateOnUpdate;
  /**
   * @var string[]
   */
  public $labels;
  /**
   * @var string
   */
  public $name;

  /**
   * @param string
   */
  public function setDefaultConsentTtl($defaultConsentTtl)
  {
    $this->defaultConsentTtl = $defaultConsentTtl;
  }
  /**
   * @return string
   */
  public function getDefaultConsentTtl()
  {
    return $this->defaultConsentTtl;
  }
  /**
   * @param bool
   */
  public function setEnableConsentCreateOnUpdate($enableConsentCreateOnUpdate)
  {
    $this->enableConsentCreateOnUpdate = $enableConsentCreateOnUpdate;
  }
  /**
   * @return bool
   */
  public function getEnableConsentCreateOnUpdate()
  {
    return $this->enableConsentCreateOnUpdate;
  }
  /**
   * @param string[]
   */
  public function setLabels($labels)
  {
    $this->labels = $labels;
  }
  /**
   * @return string[]
   */
  public function getLabels()
  {
    return $this->labels;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ConsentStore::class, 'Google_Service_CloudHealthcare_ConsentStore');
