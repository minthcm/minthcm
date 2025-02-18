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

namespace Google\Service\DatabaseMigrationService;

class SeedConversionWorkspaceRequest extends \Google\Model
{
  /**
   * @var bool
   */
  public $autoCommit;
  /**
   * @var string
   */
  public $destinationConnectionProfile;
  /**
   * @var string
   */
  public $sourceConnectionProfile;

  /**
   * @param bool
   */
  public function setAutoCommit($autoCommit)
  {
    $this->autoCommit = $autoCommit;
  }
  /**
   * @return bool
   */
  public function getAutoCommit()
  {
    return $this->autoCommit;
  }
  /**
   * @param string
   */
  public function setDestinationConnectionProfile($destinationConnectionProfile)
  {
    $this->destinationConnectionProfile = $destinationConnectionProfile;
  }
  /**
   * @return string
   */
  public function getDestinationConnectionProfile()
  {
    return $this->destinationConnectionProfile;
  }
  /**
   * @param string
   */
  public function setSourceConnectionProfile($sourceConnectionProfile)
  {
    $this->sourceConnectionProfile = $sourceConnectionProfile;
  }
  /**
   * @return string
   */
  public function getSourceConnectionProfile()
  {
    return $this->sourceConnectionProfile;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SeedConversionWorkspaceRequest::class, 'Google_Service_DatabaseMigrationService_SeedConversionWorkspaceRequest');
