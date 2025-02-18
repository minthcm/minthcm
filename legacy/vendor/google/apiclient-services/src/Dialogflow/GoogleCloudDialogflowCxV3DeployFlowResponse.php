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

namespace Google\Service\Dialogflow;

class GoogleCloudDialogflowCxV3DeployFlowResponse extends \Google\Model
{
  /**
   * @var string
   */
  public $deployment;
  protected $environmentType = GoogleCloudDialogflowCxV3Environment::class;
  protected $environmentDataType = '';

  /**
   * @param string
   */
  public function setDeployment($deployment)
  {
    $this->deployment = $deployment;
  }
  /**
   * @return string
   */
  public function getDeployment()
  {
    return $this->deployment;
  }
  /**
   * @param GoogleCloudDialogflowCxV3Environment
   */
  public function setEnvironment(GoogleCloudDialogflowCxV3Environment $environment)
  {
    $this->environment = $environment;
  }
  /**
   * @return GoogleCloudDialogflowCxV3Environment
   */
  public function getEnvironment()
  {
    return $this->environment;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDialogflowCxV3DeployFlowResponse::class, 'Google_Service_Dialogflow_GoogleCloudDialogflowCxV3DeployFlowResponse');
