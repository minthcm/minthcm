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

namespace Google\Service\Books\Resource;

use Google\Service\Books\BooksEmpty;
use Google\Service\Books\FamilyInfo;

/**
 * The "familysharing" collection of methods.
 * Typical usage is:
 *  <code>
 *   $booksService = new Google\Service\Books(...);
 *   $familysharing = $booksService->familysharing;
 *  </code>
 */
class Familysharing extends \Google\Service\Resource
{
  /**
   * Gets information regarding the family that the user is part of.
   * (familysharing.getFamilyInfo)
   *
   * @param array $optParams Optional parameters.
   *
   * @opt_param string source String to identify the originator of this request.
   * @return FamilyInfo
   * @throws \Google\Service\Exception
   */
  public function getFamilyInfo($optParams = [])
  {
    $params = [];
    $params = array_merge($params, $optParams);
    return $this->call('getFamilyInfo', [$params], FamilyInfo::class);
  }
  /**
   * Initiates sharing of the content with the user's family. Empty response
   * indicates success. (familysharing.share)
   *
   * @param array $optParams Optional parameters.
   *
   * @opt_param string docId The docid to share.
   * @opt_param string source String to identify the originator of this request.
   * @opt_param string volumeId The volume to share.
   * @return BooksEmpty
   * @throws \Google\Service\Exception
   */
  public function share($optParams = [])
  {
    $params = [];
    $params = array_merge($params, $optParams);
    return $this->call('share', [$params], BooksEmpty::class);
  }
  /**
   * Initiates revoking content that has already been shared with the user's
   * family. Empty response indicates success. (familysharing.unshare)
   *
   * @param array $optParams Optional parameters.
   *
   * @opt_param string docId The docid to unshare.
   * @opt_param string source String to identify the originator of this request.
   * @opt_param string volumeId The volume to unshare.
   * @return BooksEmpty
   * @throws \Google\Service\Exception
   */
  public function unshare($optParams = [])
  {
    $params = [];
    $params = array_merge($params, $optParams);
    return $this->call('unshare', [$params], BooksEmpty::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Familysharing::class, 'Google_Service_Books_Resource_Familysharing');
