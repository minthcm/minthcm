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

use Google\Service\CloudSearch\Media as MediaModel;

/**
 * The "media" collection of methods.
 * Typical usage is:
 *  <code>
 *   $cloudsearchService = new Google\Service\CloudSearch(...);
 *   $media = $cloudsearchService->media;
 *  </code>
 */
class Media extends \Google\Service\Resource
{
  /**
   * Uploads media for indexing. The upload endpoint supports direct and resumable
   * upload protocols and is intended for large items that can not be [inlined
   * during index requests](https://developers.google.com/cloud-
   * search/docs/reference/rest/v1/indexing.datasources.items#itemcontent). To
   * index large content: 1. Call indexing.datasources.items.upload with the item
   * name to begin an upload session and retrieve the UploadItemRef. 1. Call
   * media.upload to upload the content, as a streaming request, using the same
   * resource name from the UploadItemRef from step 1. 1. Call
   * indexing.datasources.items.index to index the item. Populate the
   * [ItemContent](/cloud-
   * search/docs/reference/rest/v1/indexing.datasources.items#ItemContent) with
   * the UploadItemRef from step 1. For additional information, see [Create a
   * content connector using the REST API](https://developers.google.com/cloud-
   * search/docs/guides/content-connector#rest). **Note:** This API requires a
   * service account to execute. (media.upload)
   *
   * @param string $resourceName Name of the media that is being downloaded. See
   * ReadRequest.resource_name.
   * @param MediaModel $postBody
   * @param array $optParams Optional parameters.
   * @return MediaModel
   * @throws \Google\Service\Exception
   */
  public function upload($resourceName, MediaModel $postBody, $optParams = [])
  {
    $params = ['resourceName' => $resourceName, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('upload', [$params], MediaModel::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Media::class, 'Google_Service_CloudSearch_Resource_Media');
