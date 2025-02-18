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

namespace Google\Service\Dns;

class RRSetRoutingPolicyGeoPolicy extends \Google\Collection
{
  protected $collection_key = 'items';
  /**
   * @var bool
   */
  public $enableFencing;
  protected $itemsType = RRSetRoutingPolicyGeoPolicyGeoPolicyItem::class;
  protected $itemsDataType = 'array';
  /**
   * @var string
   */
  public $kind;

  /**
   * @param bool
   */
  public function setEnableFencing($enableFencing)
  {
    $this->enableFencing = $enableFencing;
  }
  /**
   * @return bool
   */
  public function getEnableFencing()
  {
    return $this->enableFencing;
  }
  /**
   * @param RRSetRoutingPolicyGeoPolicyGeoPolicyItem[]
   */
  public function setItems($items)
  {
    $this->items = $items;
  }
  /**
   * @return RRSetRoutingPolicyGeoPolicyGeoPolicyItem[]
   */
  public function getItems()
  {
    return $this->items;
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
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(RRSetRoutingPolicyGeoPolicy::class, 'Google_Service_Dns_RRSetRoutingPolicyGeoPolicy');
