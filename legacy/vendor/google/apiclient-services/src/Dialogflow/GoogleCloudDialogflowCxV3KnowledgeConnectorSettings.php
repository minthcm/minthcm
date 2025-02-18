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

class GoogleCloudDialogflowCxV3KnowledgeConnectorSettings extends \Google\Collection
{
  protected $collection_key = 'dataStoreConnections';
  protected $dataStoreConnectionsType = GoogleCloudDialogflowCxV3DataStoreConnection::class;
  protected $dataStoreConnectionsDataType = 'array';
  /**
   * @var bool
   */
  public $enabled;
  /**
   * @var string
   */
  public $targetFlow;
  /**
   * @var string
   */
  public $targetPage;
  protected $triggerFulfillmentType = GoogleCloudDialogflowCxV3Fulfillment::class;
  protected $triggerFulfillmentDataType = '';

  /**
   * @param GoogleCloudDialogflowCxV3DataStoreConnection[]
   */
  public function setDataStoreConnections($dataStoreConnections)
  {
    $this->dataStoreConnections = $dataStoreConnections;
  }
  /**
   * @return GoogleCloudDialogflowCxV3DataStoreConnection[]
   */
  public function getDataStoreConnections()
  {
    return $this->dataStoreConnections;
  }
  /**
   * @param bool
   */
  public function setEnabled($enabled)
  {
    $this->enabled = $enabled;
  }
  /**
   * @return bool
   */
  public function getEnabled()
  {
    return $this->enabled;
  }
  /**
   * @param string
   */
  public function setTargetFlow($targetFlow)
  {
    $this->targetFlow = $targetFlow;
  }
  /**
   * @return string
   */
  public function getTargetFlow()
  {
    return $this->targetFlow;
  }
  /**
   * @param string
   */
  public function setTargetPage($targetPage)
  {
    $this->targetPage = $targetPage;
  }
  /**
   * @return string
   */
  public function getTargetPage()
  {
    return $this->targetPage;
  }
  /**
   * @param GoogleCloudDialogflowCxV3Fulfillment
   */
  public function setTriggerFulfillment(GoogleCloudDialogflowCxV3Fulfillment $triggerFulfillment)
  {
    $this->triggerFulfillment = $triggerFulfillment;
  }
  /**
   * @return GoogleCloudDialogflowCxV3Fulfillment
   */
  public function getTriggerFulfillment()
  {
    return $this->triggerFulfillment;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDialogflowCxV3KnowledgeConnectorSettings::class, 'Google_Service_Dialogflow_GoogleCloudDialogflowCxV3KnowledgeConnectorSettings');
