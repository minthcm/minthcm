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

namespace Google\Service\ToolResults;

class EncounteredLoginScreen extends \Google\Collection
{
  protected $collection_key = 'screenIds';
  /**
   * @var int
   */
  public $distinctScreens;
  /**
   * @var string[]
   */
  public $screenIds;

  /**
   * @param int
   */
  public function setDistinctScreens($distinctScreens)
  {
    $this->distinctScreens = $distinctScreens;
  }
  /**
   * @return int
   */
  public function getDistinctScreens()
  {
    return $this->distinctScreens;
  }
  /**
   * @param string[]
   */
  public function setScreenIds($screenIds)
  {
    $this->screenIds = $screenIds;
  }
  /**
   * @return string[]
   */
  public function getScreenIds()
  {
    return $this->screenIds;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(EncounteredLoginScreen::class, 'Google_Service_ToolResults_EncounteredLoginScreen');
