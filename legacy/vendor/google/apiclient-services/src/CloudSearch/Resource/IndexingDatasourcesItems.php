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

namespace Google\Service\CloudSearch\Resource;

use Google\Service\CloudSearch\DeleteQueueItemsRequest;
use Google\Service\CloudSearch\IndexItemRequest;
use Google\Service\CloudSearch\Item;
use Google\Service\CloudSearch\ListItemsResponse;
use Google\Service\CloudSearch\Operation;
use Google\Service\CloudSearch\PollItemsRequest;
use Google\Service\CloudSearch\PollItemsResponse;
use Google\Service\CloudSearch\PushItemRequest;
use Google\Service\CloudSearch\StartUploadItemRequest;
use Google\Service\CloudSearch\UnreserveItemsRequest;
use Google\Service\CloudSearch\UploadItemRef;

/**
 * The "items" collection of methods.
 * Typical usage is:
 *  <code>
 *   $cloudsearchService = new Google\Service\CloudSearch(...);
 *   $items = $cloudsearchService->indexing_datasources_items;
 *  </code>
 */
class IndexingDatasourcesItems extends \Google\Service\Resource
{
  /**
   * Deletes Item resource for the specified resource name. This API requires an
   * admin or service account to execute. The service account used is the one
   * whitelisted in the corresponding data source. (items.delete)
   *
   * @param string $name Required. The name of the item to delete. Format:
   * datasources/{source_id}/items/{item_id}
   * @param array $optParams Optional parameters.
   *
   * @opt_param string connectorName The name of connector making this call.
   * Format: datasources/{source_id}/connectors/{ID}
   * @opt_param bool debugOptions.enableDebugging If you are asked by Google to
   * help with debugging, set this field. Otherwise, ignore this field.
   * @opt_param string mode Required. The RequestMode for this request.
   * @opt_param string version Required. The incremented version of the item to
   * delete from the index. The indexing system stores the version from the
   * datasource as a byte string and compares the Item version in the index to the
   * version of the queued Item using lexical ordering. Cloud Search Indexing
   * won't delete any queued item with a version value that is less than or equal
   * to the version of the currently indexed item. The maximum length for this
   * field is 1024 bytes. For information on how item version affects the deletion
   * process, refer to [Handle revisions after manual
   * deletes](https://developers.google.com/cloud-search/docs/guides/operations).
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], Operation::class);
  }
  /**
   * Deletes all items in a queue. This method is useful for deleting stale items.
   * This API requires an admin or service account to execute. The service account
   * used is the one whitelisted in the corresponding data source.
   * (items.deleteQueueItems)
   *
   * @param string $name The name of the Data Source to delete items in a queue.
   * Format: datasources/{source_id}
   * @param DeleteQueueItemsRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function deleteQueueItems($name, DeleteQueueItemsRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('deleteQueueItems', [$params], Operation::class);
  }
  /**
   * Gets Item resource by item name. This API requires an admin or service
   * account to execute. The service account used is the one whitelisted in the
   * corresponding data source. (items.get)
   *
   * @param string $name The name of the item to get info. Format:
   * datasources/{source_id}/items/{item_id}
   * @param array $optParams Optional parameters.
   *
   * @opt_param string connectorName The name of connector making this call.
   * Format: datasources/{source_id}/connectors/{ID}
   * @opt_param bool debugOptions.enableDebugging If you are asked by Google to
   * help with debugging, set this field. Otherwise, ignore this field.
   * @return Item
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Item::class);
  }
  /**
   * Updates Item ACL, metadata, and content. It will insert the Item if it does
   * not exist. This method does not support partial updates. Fields with no
   * provided values are cleared out in the Cloud Search index. This API requires
   * an admin or service account to execute. The service account used is the one
   * whitelisted in the corresponding data source. (items.index)
   *
   * @param string $name The name of the Item. Format:
   * datasources/{source_id}/items/{item_id} This is a required field. The maximum
   * length is 1536 characters.
   * @param IndexItemRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function index($name, IndexItemRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('index', [$params], Operation::class);
  }
  /**
   * Lists all or a subset of Item resources. This API requires an admin or
   * service account to execute. The service account used is the one whitelisted
   * in the corresponding data source. (items.listIndexingDatasourcesItems)
   *
   * @param string $name The name of the Data Source to list Items. Format:
   * datasources/{source_id}
   * @param array $optParams Optional parameters.
   *
   * @opt_param bool brief When set to true, the indexing system only populates
   * the following fields: name, version, queue. metadata.hash, metadata.title,
   * metadata.sourceRepositoryURL, metadata.objectType, metadata.createTime,
   * metadata.updateTime, metadata.contentLanguage, metadata.mimeType,
   * structured_data.hash, content.hash, itemType, itemStatus.code,
   * itemStatus.processingError.code, itemStatus.repositoryError.type, If this
   * value is false, then all the fields are populated in Item.
   * @opt_param string connectorName The name of connector making this call.
   * Format: datasources/{source_id}/connectors/{ID}
   * @opt_param bool debugOptions.enableDebugging If you are asked by Google to
   * help with debugging, set this field. Otherwise, ignore this field.
   * @opt_param int pageSize Maximum number of items to fetch in a request. The
   * max value is 1000 when brief is true. The max value is 10 if brief is false.
   * The default value is 10
   * @opt_param string pageToken The next_page_token value returned from a
   * previous List request, if any.
   * @return ListItemsResponse
   * @throws \Google\Service\Exception
   */
  public function listIndexingDatasourcesItems($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListItemsResponse::class);
  }
  /**
   * Polls for unreserved items from the indexing queue and marks a set as
   * reserved, starting with items that have the oldest timestamp from the highest
   * priority ItemStatus. The priority order is as follows: ERROR MODIFIED
   * NEW_ITEM ACCEPTED Reserving items ensures that polling from other threads
   * cannot create overlapping sets. After handling the reserved items, the client
   * should put items back into the unreserved state, either by calling index, or
   * by calling push with the type REQUEUE. Items automatically become available
   * (unreserved) after 4 hours even if no update or push method is called. This
   * API requires an admin or service account to execute. The service account used
   * is the one whitelisted in the corresponding data source. (items.poll)
   *
   * @param string $name The name of the Data Source to poll items. Format:
   * datasources/{source_id}
   * @param PollItemsRequest $postBody
   * @param array $optParams Optional parameters.
   * @return PollItemsResponse
   * @throws \Google\Service\Exception
   */
  public function poll($name, PollItemsRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('poll', [$params], PollItemsResponse::class);
  }
  /**
   * Pushes an item onto a queue for later polling and updating. This API requires
   * an admin or service account to execute. The service account used is the one
   * whitelisted in the corresponding data source. (items.push)
   *
   * @param string $name The name of the item to push into the indexing queue.
   * Format: datasources/{source_id}/items/{ID} This is a required field. The
   * maximum length is 1536 characters.
   * @param PushItemRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Item
   * @throws \Google\Service\Exception
   */
  public function push($name, PushItemRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('push', [$params], Item::class);
  }
  /**
   * Unreserves all items from a queue, making them all eligible to be polled.
   * This method is useful for resetting the indexing queue after a connector has
   * been restarted. This API requires an admin or service account to execute. The
   * service account used is the one whitelisted in the corresponding data source.
   * (items.unreserve)
   *
   * @param string $name The name of the Data Source to unreserve all items.
   * Format: datasources/{source_id}
   * @param UnreserveItemsRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function unreserve($name, UnreserveItemsRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('unreserve', [$params], Operation::class);
  }
  /**
   * Creates an upload session for uploading item content. For items smaller than
   * 100 KB, it's easier to embed the content inline within an index request. This
   * API requires an admin or service account to execute. The service account used
   * is the one whitelisted in the corresponding data source. (items.upload)
   *
   * @param string $name The name of the Item to start a resumable upload. Format:
   * datasources/{source_id}/items/{item_id}. The maximum length is 1536 bytes.
   * @param StartUploadItemRequest $postBody
   * @param array $optParams Optional parameters.
   * @return UploadItemRef
   * @throws \Google\Service\Exception
   */
  public function upload($name, StartUploadItemRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('upload', [$params], UploadItemRef::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(IndexingDatasourcesItems::class, 'Google_Service_CloudSearch_Resource_IndexingDatasourcesItems');
