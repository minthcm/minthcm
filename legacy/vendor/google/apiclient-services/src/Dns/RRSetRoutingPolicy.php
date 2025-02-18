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

class RRSetRoutingPolicy extends \Google\Model
{
  protected $geoType = RRSetRoutingPolicyGeoPolicy::class;
  protected $geoDataType = '';
  /**
   * @var string
   */
  public $kind;
  protected $primaryBackupType = RRSetRoutingPolicyPrimaryBackupPolicy::class;
  protected $primaryBackupDataType = '';
  protected $wrrType = RRSetRoutingPolicyWrrPolicy::class;
  protected $wrrDataType = '';

  /**
   * @param RRSetRoutingPolicyGeoPolicy
   */
  public function setGeo(RRSetRoutingPolicyGeoPolicy $geo)
  {
    $this->geo = $geo;
  }
  /**
   * @return RRSetRoutingPolicyGeoPolicy
   */
  public function getGeo()
  {
    return $this->geo;
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
   * @param RRSetRoutingPolicyPrimaryBackupPolicy
   */
  public function setPrimaryBackup(RRSetRoutingPolicyPrimaryBackupPolicy $primaryBackup)
  {
    $this->primaryBackup = $primaryBackup;
  }
  /**
   * @return RRSetRoutingPolicyPrimaryBackupPolicy
   */
  public function getPrimaryBackup()
  {
    return $this->primaryBackup;
  }
  /**
   * @param RRSetRoutingPolicyWrrPolicy
   */
  public function setWrr(RRSetRoutingPolicyWrrPolicy $wrr)
  {
    $this->wrr = $wrr;
  }
  /**
   * @return RRSetRoutingPolicyWrrPolicy
   */
  public function getWrr()
  {
    return $this->wrr;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(RRSetRoutingPolicy::class, 'Google_Service_Dns_RRSetRoutingPolicy');
