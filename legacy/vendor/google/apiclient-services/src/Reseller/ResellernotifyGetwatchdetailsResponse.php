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

namespace Google\Service\Reseller;

class ResellernotifyGetwatchdetailsResponse extends \Google\Collection
{
  protected $collection_key = 'serviceAccountEmailAddresses';
  /**
   * @var string[]
   */
  public $serviceAccountEmailAddresses;
  /**
   * @var string
   */
  public $topicName;

  /**
   * @param string[]
   */
  public function setServiceAccountEmailAddresses($serviceAccountEmailAddresses)
  {
    $this->serviceAccountEmailAddresses = $serviceAccountEmailAddresses;
  }
  /**
   * @return string[]
   */
  public function getServiceAccountEmailAddresses()
  {
    return $this->serviceAccountEmailAddresses;
  }
  /**
   * @param string
   */
  public function setTopicName($topicName)
  {
    $this->topicName = $topicName;
  }
  /**
   * @return string
   */
  public function getTopicName()
  {
    return $this->topicName;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ResellernotifyGetwatchdetailsResponse::class, 'Google_Service_Reseller_ResellernotifyGetwatchdetailsResponse');
