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

class GoogleCloudDialogflowCxV3ImportFlowRequest extends \Google\Model
{
  /**
   * @var string
   */
  public $flowContent;
  protected $flowImportStrategyType = GoogleCloudDialogflowCxV3FlowImportStrategy::class;
  protected $flowImportStrategyDataType = '';
  /**
   * @var string
   */
  public $flowUri;
  /**
   * @var string
   */
  public $importOption;

  /**
   * @param string
   */
  public function setFlowContent($flowContent)
  {
    $this->flowContent = $flowContent;
  }
  /**
   * @return string
   */
  public function getFlowContent()
  {
    return $this->flowContent;
  }
  /**
   * @param GoogleCloudDialogflowCxV3FlowImportStrategy
   */
  public function setFlowImportStrategy(GoogleCloudDialogflowCxV3FlowImportStrategy $flowImportStrategy)
  {
    $this->flowImportStrategy = $flowImportStrategy;
  }
  /**
   * @return GoogleCloudDialogflowCxV3FlowImportStrategy
   */
  public function getFlowImportStrategy()
  {
    return $this->flowImportStrategy;
  }
  /**
   * @param string
   */
  public function setFlowUri($flowUri)
  {
    $this->flowUri = $flowUri;
  }
  /**
   * @return string
   */
  public function getFlowUri()
  {
    return $this->flowUri;
  }
  /**
   * @param string
   */
  public function setImportOption($importOption)
  {
    $this->importOption = $importOption;
  }
  /**
   * @return string
   */
  public function getImportOption()
  {
    return $this->importOption;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDialogflowCxV3ImportFlowRequest::class, 'Google_Service_Dialogflow_GoogleCloudDialogflowCxV3ImportFlowRequest');
