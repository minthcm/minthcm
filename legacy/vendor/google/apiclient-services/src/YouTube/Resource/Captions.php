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

namespace Google\Service\YouTube\Resource;

use Google\Service\YouTube\Caption;
use Google\Service\YouTube\CaptionListResponse;

/**
 * The "captions" collection of methods.
 * Typical usage is:
 *  <code>
 *   $youtubeService = new Google\Service\YouTube(...);
 *   $captions = $youtubeService->captions;
 *  </code>
 */
class Captions extends \Google\Service\Resource
{
  /**
   * Deletes a resource. (captions.delete)
   *
   * @param string $id
   * @param array $optParams Optional parameters.
   *
   * @opt_param string onBehalfOf ID of the Google+ Page for the channel that the
   * request is be on behalf of
   * @opt_param string onBehalfOfContentOwner *Note:* This parameter is intended
   * exclusively for YouTube content partners. The *onBehalfOfContentOwner*
   * parameter indicates that the request's authorization credentials identify a
   * YouTube CMS user who is acting on behalf of the content owner specified in
   * the parameter value. This parameter is intended for YouTube content partners
   * that own and manage many different YouTube channels. It allows content owners
   * to authenticate once and get access to all their video and channel data,
   * without having to provide authentication credentials for each individual
   * channel. The actual CMS account that the user authenticates with must be
   * linked to the specified YouTube content owner.
   * @throws \Google\Service\Exception
   */
  public function delete($id, $optParams = [])
  {
    $params = ['id' => $id];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params]);
  }
  /**
   * Downloads a caption track. (captions.download)
   *
   * @param string $id The ID of the caption track to download, required for One
   * Platform.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string onBehalfOf ID of the Google+ Page for the channel that the
   * request is be on behalf of
   * @opt_param string onBehalfOfContentOwner *Note:* This parameter is intended
   * exclusively for YouTube content partners. The *onBehalfOfContentOwner*
   * parameter indicates that the request's authorization credentials identify a
   * YouTube CMS user who is acting on behalf of the content owner specified in
   * the parameter value. This parameter is intended for YouTube content partners
   * that own and manage many different YouTube channels. It allows content owners
   * to authenticate once and get access to all their video and channel data,
   * without having to provide authentication credentials for each individual
   * channel. The actual CMS account that the user authenticates with must be
   * linked to the specified YouTube content owner.
   * @opt_param string tfmt Convert the captions into this format. Supported
   * options are sbv, srt, and vtt.
   * @opt_param string tlang tlang is the language code; machine translate the
   * captions into this language.
   * @throws \Google\Service\Exception
   */
  public function download($id, $optParams = [])
  {
    $params = ['id' => $id];
    $params = array_merge($params, $optParams);
    return $this->call('download', [$params]);
  }
  /**
   * Inserts a new resource into this collection. (captions.insert)
   *
   * @param string|array $part The *part* parameter specifies the caption resource
   * parts that the API response will include. Set the parameter value to snippet.
   * @param Caption $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string onBehalfOf ID of the Google+ Page for the channel that the
   * request is be on behalf of
   * @opt_param string onBehalfOfContentOwner *Note:* This parameter is intended
   * exclusively for YouTube content partners. The *onBehalfOfContentOwner*
   * parameter indicates that the request's authorization credentials identify a
   * YouTube CMS user who is acting on behalf of the content owner specified in
   * the parameter value. This parameter is intended for YouTube content partners
   * that own and manage many different YouTube channels. It allows content owners
   * to authenticate once and get access to all their video and channel data,
   * without having to provide authentication credentials for each individual
   * channel. The actual CMS account that the user authenticates with must be
   * linked to the specified YouTube content owner.
   * @opt_param bool sync Extra parameter to allow automatically syncing the
   * uploaded caption/transcript with the audio.
   * @return Caption
   * @throws \Google\Service\Exception
   */
  public function insert($part, Caption $postBody, $optParams = [])
  {
    $params = ['part' => $part, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('insert', [$params], Caption::class);
  }
  /**
   * Retrieves a list of resources, possibly filtered. (captions.listCaptions)
   *
   * @param string|array $part The *part* parameter specifies a comma-separated
   * list of one or more caption resource parts that the API response will
   * include. The part names that you can include in the parameter value are id
   * and snippet.
   * @param string $videoId Returns the captions for the specified video.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string id Returns the captions with the given IDs for Stubby or
   * Apiary.
   * @opt_param string onBehalfOf ID of the Google+ Page for the channel that the
   * request is on behalf of.
   * @opt_param string onBehalfOfContentOwner *Note:* This parameter is intended
   * exclusively for YouTube content partners. The *onBehalfOfContentOwner*
   * parameter indicates that the request's authorization credentials identify a
   * YouTube CMS user who is acting on behalf of the content owner specified in
   * the parameter value. This parameter is intended for YouTube content partners
   * that own and manage many different YouTube channels. It allows content owners
   * to authenticate once and get access to all their video and channel data,
   * without having to provide authentication credentials for each individual
   * channel. The actual CMS account that the user authenticates with must be
   * linked to the specified YouTube content owner.
   * @return CaptionListResponse
   * @throws \Google\Service\Exception
   */
  public function listCaptions($part, $videoId, $optParams = [])
  {
    $params = ['part' => $part, 'videoId' => $videoId];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], CaptionListResponse::class);
  }
  /**
   * Updates an existing resource. (captions.update)
   *
   * @param string|array $part The *part* parameter specifies a comma-separated
   * list of one or more caption resource parts that the API response will
   * include. The part names that you can include in the parameter value are id
   * and snippet.
   * @param Caption $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string onBehalfOf ID of the Google+ Page for the channel that the
   * request is on behalf of.
   * @opt_param string onBehalfOfContentOwner *Note:* This parameter is intended
   * exclusively for YouTube content partners. The *onBehalfOfContentOwner*
   * parameter indicates that the request's authorization credentials identify a
   * YouTube CMS user who is acting on behalf of the content owner specified in
   * the parameter value. This parameter is intended for YouTube content partners
   * that own and manage many different YouTube channels. It allows content owners
   * to authenticate once and get access to all their video and channel data,
   * without having to provide authentication credentials for each individual
   * channel. The actual CMS account that the user authenticates with must be
   * linked to the specified YouTube content owner.
   * @opt_param bool sync Extra parameter to allow automatically syncing the
   * uploaded caption/transcript with the audio.
   * @return Caption
   * @throws \Google\Service\Exception
   */
  public function update($part, Caption $postBody, $optParams = [])
  {
    $params = ['part' => $part, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('update', [$params], Caption::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Captions::class, 'Google_Service_YouTube_Resource_Captions');
