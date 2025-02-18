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

namespace Google\Service\AndroidProvisioningPartner;

class FindDevicesByOwnerRequest extends \Google\Collection
{
  protected $collection_key = 'googleWorkspaceCustomerId';
  /**
   * @var string[]
   */
  public $customerId;
  /**
   * @var string[]
   */
  public $googleWorkspaceCustomerId;
  /**
   * @var string
   */
  public $limit;
  /**
   * @var string
   */
  public $pageToken;
  /**
   * @var string
   */
  public $sectionType;

  /**
   * @param string[]
   */
  public function setCustomerId($customerId)
  {
    $this->customerId = $customerId;
  }
  /**
   * @return string[]
   */
  public function getCustomerId()
  {
    return $this->customerId;
  }
  /**
   * @param string[]
   */
  public function setGoogleWorkspaceCustomerId($googleWorkspaceCustomerId)
  {
    $this->googleWorkspaceCustomerId = $googleWorkspaceCustomerId;
  }
  /**
   * @return string[]
   */
  public function getGoogleWorkspaceCustomerId()
  {
    return $this->googleWorkspaceCustomerId;
  }
  /**
   * @param string
   */
  public function setLimit($limit)
  {
    $this->limit = $limit;
  }
  /**
   * @return string
   */
  public function getLimit()
  {
    return $this->limit;
  }
  /**
   * @param string
   */
  public function setPageToken($pageToken)
  {
    $this->pageToken = $pageToken;
  }
  /**
   * @return string
   */
  public function getPageToken()
  {
    return $this->pageToken;
  }
  /**
   * @param string
   */
  public function setSectionType($sectionType)
  {
    $this->sectionType = $sectionType;
  }
  /**
   * @return string
   */
  public function getSectionType()
  {
    return $this->sectionType;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(FindDevicesByOwnerRequest::class, 'Google_Service_AndroidProvisioningPartner_FindDevicesByOwnerRequest');
