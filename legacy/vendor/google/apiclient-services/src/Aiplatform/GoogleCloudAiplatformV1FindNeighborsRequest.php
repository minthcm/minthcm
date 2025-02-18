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

class GoogleCloudAiplatformV1FindNeighborsRequest extends \Google\Collection
{
  protected $collection_key = 'queries';
  /**
   * @var string
   */
  public $deployedIndexId;
  protected $queriesType = GoogleCloudAiplatformV1FindNeighborsRequestQuery::class;
  protected $queriesDataType = 'array';
  /**
   * @var bool
   */
  public $returnFullDatapoint;

  /**
   * @param string
   */
  public function setDeployedIndexId($deployedIndexId)
  {
    $this->deployedIndexId = $deployedIndexId;
  }
  /**
   * @return string
   */
  public function getDeployedIndexId()
  {
    return $this->deployedIndexId;
  }
  /**
   * @param GoogleCloudAiplatformV1FindNeighborsRequestQuery[]
   */
  public function setQueries($queries)
  {
    $this->queries = $queries;
  }
  /**
   * @return GoogleCloudAiplatformV1FindNeighborsRequestQuery[]
   */
  public function getQueries()
  {
    return $this->queries;
  }
  /**
   * @param bool
   */
  public function setReturnFullDatapoint($returnFullDatapoint)
  {
    $this->returnFullDatapoint = $returnFullDatapoint;
  }
  /**
   * @return bool
   */
  public function getReturnFullDatapoint()
  {
    return $this->returnFullDatapoint;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAiplatformV1FindNeighborsRequest::class, 'Google_Service_Aiplatform_GoogleCloudAiplatformV1FindNeighborsRequest');
