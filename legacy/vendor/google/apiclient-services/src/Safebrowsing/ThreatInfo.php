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

namespace Google\Service\Safebrowsing;

class ThreatInfo extends \Google\Collection
{
  protected $collection_key = 'threatTypes';
  /**
   * @var string[]
   */
  public $platformTypes;
  protected $threatEntriesType = ThreatEntry::class;
  protected $threatEntriesDataType = 'array';
  public $threatEntries;
  /**
   * @var string[]
   */
  public $threatEntryTypes;
  /**
   * @var string[]
   */
  public $threatTypes;

  /**
   * @param string[]
   */
  public function setPlatformTypes($platformTypes)
  {
    $this->platformTypes = $platformTypes;
  }
  /**
   * @return string[]
   */
  public function getPlatformTypes()
  {
    return $this->platformTypes;
  }
  /**
   * @param ThreatEntry[]
   */
  public function setThreatEntries($threatEntries)
  {
    $this->threatEntries = $threatEntries;
  }
  /**
   * @return ThreatEntry[]
   */
  public function getThreatEntries()
  {
    return $this->threatEntries;
  }
  /**
   * @param string[]
   */
  public function setThreatEntryTypes($threatEntryTypes)
  {
    $this->threatEntryTypes = $threatEntryTypes;
  }
  /**
   * @return string[]
   */
  public function getThreatEntryTypes()
  {
    return $this->threatEntryTypes;
  }
  /**
   * @param string[]
   */
  public function setThreatTypes($threatTypes)
  {
    $this->threatTypes = $threatTypes;
  }
  /**
   * @return string[]
   */
  public function getThreatTypes()
  {
    return $this->threatTypes;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ThreatInfo::class, 'Google_Service_Safebrowsing_ThreatInfo');
