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

namespace Google\Service\HomeGraphService;

class QueryRequest extends \Google\Collection
{
  protected $collection_key = 'inputs';
  /**
   * @var string
   */
  public $agentUserId;
  protected $inputsType = QueryRequestInput::class;
  protected $inputsDataType = 'array';
  /**
   * @var string
   */
  public $requestId;

  /**
   * @param string
   */
  public function setAgentUserId($agentUserId)
  {
    $this->agentUserId = $agentUserId;
  }
  /**
   * @return string
   */
  public function getAgentUserId()
  {
    return $this->agentUserId;
  }
  /**
   * @param QueryRequestInput[]
   */
  public function setInputs($inputs)
  {
    $this->inputs = $inputs;
  }
  /**
   * @return QueryRequestInput[]
   */
  public function getInputs()
  {
    return $this->inputs;
  }
  /**
   * @param string
   */
  public function setRequestId($requestId)
  {
    $this->requestId = $requestId;
  }
  /**
   * @return string
   */
  public function getRequestId()
  {
    return $this->requestId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(QueryRequest::class, 'Google_Service_HomeGraphService_QueryRequest');
