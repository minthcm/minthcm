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

namespace Google\Service\Aiplatform;

class GoogleCloudAiplatformV1NearestNeighborQueryStringFilter extends \Google\Collection
{
  protected $collection_key = 'denyTokens';
  /**
   * @var string[]
   */
  public $allowTokens;
  /**
   * @var string[]
   */
  public $denyTokens;
  /**
   * @var string
   */
  public $name;

  /**
   * @param string[]
   */
  public function setAllowTokens($allowTokens)
  {
    $this->allowTokens = $allowTokens;
  }
  /**
   * @return string[]
   */
  public function getAllowTokens()
  {
    return $this->allowTokens;
  }
  /**
   * @param string[]
   */
  public function setDenyTokens($denyTokens)
  {
    $this->denyTokens = $denyTokens;
  }
  /**
   * @return string[]
   */
  public function getDenyTokens()
  {
    return $this->denyTokens;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAiplatformV1NearestNeighborQueryStringFilter::class, 'Google_Service_Aiplatform_GoogleCloudAiplatformV1NearestNeighborQueryStringFilter');
