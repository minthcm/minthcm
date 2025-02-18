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

namespace Google\Service\Dataflow;

class InstructionInput extends \Google\Model
{
  /**
   * @var int
   */
  public $outputNum;
  /**
   * @var int
   */
  public $producerInstructionIndex;

  /**
   * @param int
   */
  public function setOutputNum($outputNum)
  {
    $this->outputNum = $outputNum;
  }
  /**
   * @return int
   */
  public function getOutputNum()
  {
    return $this->outputNum;
  }
  /**
   * @param int
   */
  public function setProducerInstructionIndex($producerInstructionIndex)
  {
    $this->producerInstructionIndex = $producerInstructionIndex;
  }
  /**
   * @return int
   */
  public function getProducerInstructionIndex()
  {
    return $this->producerInstructionIndex;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(InstructionInput::class, 'Google_Service_Dataflow_InstructionInput');
