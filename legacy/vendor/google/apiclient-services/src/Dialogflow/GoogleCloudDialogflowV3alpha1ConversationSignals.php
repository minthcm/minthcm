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

class GoogleCloudDialogflowV3alpha1ConversationSignals extends \Google\Model
{
  protected $turnSignalsType = GoogleCloudDialogflowV3alpha1TurnSignals::class;
  protected $turnSignalsDataType = '';

  /**
   * @param GoogleCloudDialogflowV3alpha1TurnSignals
   */
  public function setTurnSignals(GoogleCloudDialogflowV3alpha1TurnSignals $turnSignals)
  {
    $this->turnSignals = $turnSignals;
  }
  /**
   * @return GoogleCloudDialogflowV3alpha1TurnSignals
   */
  public function getTurnSignals()
  {
    return $this->turnSignals;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDialogflowV3alpha1ConversationSignals::class, 'Google_Service_Dialogflow_GoogleCloudDialogflowV3alpha1ConversationSignals');
