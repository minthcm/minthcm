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

namespace Google\Service\NetworkServices;

class HttpRouteRequestMirrorPolicy extends \Google\Model
{
  protected $destinationType = HttpRouteDestination::class;
  protected $destinationDataType = '';
  /**
   * @var float
   */
  public $mirrorPercent;

  /**
   * @param HttpRouteDestination
   */
  public function setDestination(HttpRouteDestination $destination)
  {
    $this->destination = $destination;
  }
  /**
   * @return HttpRouteDestination
   */
  public function getDestination()
  {
    return $this->destination;
  }
  /**
   * @param float
   */
  public function setMirrorPercent($mirrorPercent)
  {
    $this->mirrorPercent = $mirrorPercent;
  }
  /**
   * @return float
   */
  public function getMirrorPercent()
  {
    return $this->mirrorPercent;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(HttpRouteRequestMirrorPolicy::class, 'Google_Service_NetworkServices_HttpRouteRequestMirrorPolicy');
