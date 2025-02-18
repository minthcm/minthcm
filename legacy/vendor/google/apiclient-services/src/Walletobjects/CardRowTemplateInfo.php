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

namespace Google\Service\Walletobjects;

class CardRowTemplateInfo extends \Google\Model
{
  protected $oneItemType = CardRowOneItem::class;
  protected $oneItemDataType = '';
  protected $threeItemsType = CardRowThreeItems::class;
  protected $threeItemsDataType = '';
  protected $twoItemsType = CardRowTwoItems::class;
  protected $twoItemsDataType = '';

  /**
   * @param CardRowOneItem
   */
  public function setOneItem(CardRowOneItem $oneItem)
  {
    $this->oneItem = $oneItem;
  }
  /**
   * @return CardRowOneItem
   */
  public function getOneItem()
  {
    return $this->oneItem;
  }
  /**
   * @param CardRowThreeItems
   */
  public function setThreeItems(CardRowThreeItems $threeItems)
  {
    $this->threeItems = $threeItems;
  }
  /**
   * @return CardRowThreeItems
   */
  public function getThreeItems()
  {
    return $this->threeItems;
  }
  /**
   * @param CardRowTwoItems
   */
  public function setTwoItems(CardRowTwoItems $twoItems)
  {
    $this->twoItems = $twoItems;
  }
  /**
   * @return CardRowTwoItems
   */
  public function getTwoItems()
  {
    return $this->twoItems;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CardRowTemplateInfo::class, 'Google_Service_Walletobjects_CardRowTemplateInfo');
