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

namespace Google\Service\Connectors;

class SupportedRuntimeFeatures extends \Google\Model
{
  /**
   * @var bool
   */
  public $actionApis;
  /**
   * @var bool
   */
  public $entityApis;
  /**
   * @var bool
   */
  public $sqlQuery;

  /**
   * @param bool
   */
  public function setActionApis($actionApis)
  {
    $this->actionApis = $actionApis;
  }
  /**
   * @return bool
   */
  public function getActionApis()
  {
    return $this->actionApis;
  }
  /**
   * @param bool
   */
  public function setEntityApis($entityApis)
  {
    $this->entityApis = $entityApis;
  }
  /**
   * @return bool
   */
  public function getEntityApis()
  {
    return $this->entityApis;
  }
  /**
   * @param bool
   */
  public function setSqlQuery($sqlQuery)
  {
    $this->sqlQuery = $sqlQuery;
  }
  /**
   * @return bool
   */
  public function getSqlQuery()
  {
    return $this->sqlQuery;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SupportedRuntimeFeatures::class, 'Google_Service_Connectors_SupportedRuntimeFeatures');
