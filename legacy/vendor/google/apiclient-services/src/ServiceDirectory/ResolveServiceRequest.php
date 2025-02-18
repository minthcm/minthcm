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

namespace Google\Service\ServiceDirectory;

class ResolveServiceRequest extends \Google\Model
{
  /**
   * @var string
   */
  public $endpointFilter;
  /**
   * @var int
   */
  public $maxEndpoints;

  /**
   * @param string
   */
  public function setEndpointFilter($endpointFilter)
  {
    $this->endpointFilter = $endpointFilter;
  }
  /**
   * @return string
   */
  public function getEndpointFilter()
  {
    return $this->endpointFilter;
  }
  /**
   * @param int
   */
  public function setMaxEndpoints($maxEndpoints)
  {
    $this->maxEndpoints = $maxEndpoints;
  }
  /**
   * @return int
   */
  public function getMaxEndpoints()
  {
    return $this->maxEndpoints;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ResolveServiceRequest::class, 'Google_Service_ServiceDirectory_ResolveServiceRequest');
