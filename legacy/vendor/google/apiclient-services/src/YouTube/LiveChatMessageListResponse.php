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

namespace Google\Service\YouTube;

class LiveChatMessageListResponse extends \Google\Collection
{
  protected $collection_key = 'items';
  protected $activePollItemType = LiveChatMessage::class;
  protected $activePollItemDataType = '';
  /**
   * @var string
   */
  public $etag;
  /**
   * @var string
   */
  public $eventId;
  protected $itemsType = LiveChatMessage::class;
  protected $itemsDataType = 'array';
  /**
   * @var string
   */
  public $kind;
  /**
   * @var string
   */
  public $nextPageToken;
  /**
   * @var string
   */
  public $offlineAt;
  protected $pageInfoType = PageInfo::class;
  protected $pageInfoDataType = '';
  /**
   * @var string
   */
  public $pollingIntervalMillis;
  protected $tokenPaginationType = TokenPagination::class;
  protected $tokenPaginationDataType = '';
  /**
   * @var string
   */
  public $visitorId;

  /**
   * @param LiveChatMessage
   */
  public function setActivePollItem(LiveChatMessage $activePollItem)
  {
    $this->activePollItem = $activePollItem;
  }
  /**
   * @return LiveChatMessage
   */
  public function getActivePollItem()
  {
    return $this->activePollItem;
  }
  /**
   * @param string
   */
  public function setEtag($etag)
  {
    $this->etag = $etag;
  }
  /**
   * @return string
   */
  public function getEtag()
  {
    return $this->etag;
  }
  /**
   * @param string
   */
  public function setEventId($eventId)
  {
    $this->eventId = $eventId;
  }
  /**
   * @return string
   */
  public function getEventId()
  {
    return $this->eventId;
  }
  /**
   * @param LiveChatMessage[]
   */
  public function setItems($items)
  {
    $this->items = $items;
  }
  /**
   * @return LiveChatMessage[]
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
  /**
   * @param string
   */
  public function setNextPageToken($nextPageToken)
  {
    $this->nextPageToken = $nextPageToken;
  }
  /**
   * @return string
   */
  public function getNextPageToken()
  {
    return $this->nextPageToken;
  }
  /**
   * @param string
   */
  public function setOfflineAt($offlineAt)
  {
    $this->offlineAt = $offlineAt;
  }
  /**
   * @return string
   */
  public function getOfflineAt()
  {
    return $this->offlineAt;
  }
  /**
   * @param PageInfo
   */
  public function setPageInfo(PageInfo $pageInfo)
  {
    $this->pageInfo = $pageInfo;
  }
  /**
   * @return PageInfo
   */
  public function getPageInfo()
  {
    return $this->pageInfo;
  }
  /**
   * @param string
   */
  public function setPollingIntervalMillis($pollingIntervalMillis)
  {
    $this->pollingIntervalMillis = $pollingIntervalMillis;
  }
  /**
   * @return string
   */
  public function getPollingIntervalMillis()
  {
    return $this->pollingIntervalMillis;
  }
  /**
   * @param TokenPagination
   */
  public function setTokenPagination(TokenPagination $tokenPagination)
  {
    $this->tokenPagination = $tokenPagination;
  }
  /**
   * @return TokenPagination
   */
  public function getTokenPagination()
  {
    return $this->tokenPagination;
  }
  /**
   * @param string
   */
  public function setVisitorId($visitorId)
  {
    $this->visitorId = $visitorId;
  }
  /**
   * @return string
   */
  public function getVisitorId()
  {
    return $this->visitorId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(LiveChatMessageListResponse::class, 'Google_Service_YouTube_LiveChatMessageListResponse');
