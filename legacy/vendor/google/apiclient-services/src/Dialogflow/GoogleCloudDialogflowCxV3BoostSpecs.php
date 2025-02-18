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

class GoogleCloudDialogflowCxV3BoostSpecs extends \Google\Collection
{
  protected $collection_key = 'spec';
  /**
   * @var string[]
   */
  public $dataStores;
  protected $specType = GoogleCloudDialogflowCxV3BoostSpec::class;
  protected $specDataType = 'array';

  /**
   * @param string[]
   */
  public function setDataStores($dataStores)
  {
    $this->dataStores = $dataStores;
  }
  /**
   * @return string[]
   */
  public function getDataStores()
  {
    return $this->dataStores;
  }
  /**
   * @param GoogleCloudDialogflowCxV3BoostSpec[]
   */
  public function setSpec($spec)
  {
    $this->spec = $spec;
  }
  /**
   * @return GoogleCloudDialogflowCxV3BoostSpec[]
   */
  public function getSpec()
  {
    return $this->spec;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDialogflowCxV3BoostSpecs::class, 'Google_Service_Dialogflow_GoogleCloudDialogflowCxV3BoostSpecs');
