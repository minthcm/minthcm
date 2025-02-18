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

namespace Google\Service\AndroidEnterprise;

class StoreLayout extends \Google\Model
{
  /**
   * @var string
   */
  public $homepageId;
  /**
   * @var string
   */
  public $storeLayoutType;

  /**
   * @param string
   */
  public function setHomepageId($homepageId)
  {
    $this->homepageId = $homepageId;
  }
  /**
   * @return string
   */
  public function getHomepageId()
  {
    return $this->homepageId;
  }
  /**
   * @param string
   */
  public function setStoreLayoutType($storeLayoutType)
  {
    $this->storeLayoutType = $storeLayoutType;
  }
  /**
   * @return string
   */
  public function getStoreLayoutType()
  {
    return $this->storeLayoutType;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(StoreLayout::class, 'Google_Service_AndroidEnterprise_StoreLayout');
