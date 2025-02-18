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

class GoogleCloudDialogflowCxV3beta1Fulfillment extends \Google\Collection
{
  protected $collection_key = 'setParameterActions';
  protected $advancedSettingsType = GoogleCloudDialogflowCxV3beta1AdvancedSettings::class;
  protected $advancedSettingsDataType = '';
  protected $conditionalCasesType = GoogleCloudDialogflowCxV3beta1FulfillmentConditionalCases::class;
  protected $conditionalCasesDataType = 'array';
  /**
   * @var bool
   */
  public $enableGenerativeFallback;
  protected $messagesType = GoogleCloudDialogflowCxV3beta1ResponseMessage::class;
  protected $messagesDataType = 'array';
  /**
   * @var bool
   */
  public $returnPartialResponses;
  protected $setParameterActionsType = GoogleCloudDialogflowCxV3beta1FulfillmentSetParameterAction::class;
  protected $setParameterActionsDataType = 'array';
  /**
   * @var string
   */
  public $tag;
  /**
   * @var string
   */
  public $webhook;

  /**
   * @param GoogleCloudDialogflowCxV3beta1AdvancedSettings
   */
  public function setAdvancedSettings(GoogleCloudDialogflowCxV3beta1AdvancedSettings $advancedSettings)
  {
    $this->advancedSettings = $advancedSettings;
  }
  /**
   * @return GoogleCloudDialogflowCxV3beta1AdvancedSettings
   */
  public function getAdvancedSettings()
  {
    return $this->advancedSettings;
  }
  /**
   * @param GoogleCloudDialogflowCxV3beta1FulfillmentConditionalCases[]
   */
  public function setConditionalCases($conditionalCases)
  {
    $this->conditionalCases = $conditionalCases;
  }
  /**
   * @return GoogleCloudDialogflowCxV3beta1FulfillmentConditionalCases[]
   */
  public function getConditionalCases()
  {
    return $this->conditionalCases;
  }
  /**
   * @param bool
   */
  public function setEnableGenerativeFallback($enableGenerativeFallback)
  {
    $this->enableGenerativeFallback = $enableGenerativeFallback;
  }
  /**
   * @return bool
   */
  public function getEnableGenerativeFallback()
  {
    return $this->enableGenerativeFallback;
  }
  /**
   * @param GoogleCloudDialogflowCxV3beta1ResponseMessage[]
   */
  public function setMessages($messages)
  {
    $this->messages = $messages;
  }
  /**
   * @return GoogleCloudDialogflowCxV3beta1ResponseMessage[]
   */
  public function getMessages()
  {
    return $this->messages;
  }
  /**
   * @param bool
   */
  public function setReturnPartialResponses($returnPartialResponses)
  {
    $this->returnPartialResponses = $returnPartialResponses;
  }
  /**
   * @return bool
   */
  public function getReturnPartialResponses()
  {
    return $this->returnPartialResponses;
  }
  /**
   * @param GoogleCloudDialogflowCxV3beta1FulfillmentSetParameterAction[]
   */
  public function setSetParameterActions($setParameterActions)
  {
    $this->setParameterActions = $setParameterActions;
  }
  /**
   * @return GoogleCloudDialogflowCxV3beta1FulfillmentSetParameterAction[]
   */
  public function getSetParameterActions()
  {
    return $this->setParameterActions;
  }
  /**
   * @param string
   */
  public function setTag($tag)
  {
    $this->tag = $tag;
  }
  /**
   * @return string
   */
  public function getTag()
  {
    return $this->tag;
  }
  /**
   * @param string
   */
  public function setWebhook($webhook)
  {
    $this->webhook = $webhook;
  }
  /**
   * @return string
   */
  public function getWebhook()
  {
    return $this->webhook;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDialogflowCxV3beta1Fulfillment::class, 'Google_Service_Dialogflow_GoogleCloudDialogflowCxV3beta1Fulfillment');
