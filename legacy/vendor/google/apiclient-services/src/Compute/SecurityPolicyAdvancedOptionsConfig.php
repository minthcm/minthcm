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

namespace Google\Service\Compute;

class SecurityPolicyAdvancedOptionsConfig extends \Google\Collection
{
  protected $collection_key = 'userIpRequestHeaders';
  protected $jsonCustomConfigType = SecurityPolicyAdvancedOptionsConfigJsonCustomConfig::class;
  protected $jsonCustomConfigDataType = '';
  /**
   * @var string
   */
  public $jsonParsing;
  /**
   * @var string
   */
  public $logLevel;
  /**
   * @var string[]
   */
  public $userIpRequestHeaders;

  /**
   * @param SecurityPolicyAdvancedOptionsConfigJsonCustomConfig
   */
  public function setJsonCustomConfig(SecurityPolicyAdvancedOptionsConfigJsonCustomConfig $jsonCustomConfig)
  {
    $this->jsonCustomConfig = $jsonCustomConfig;
  }
  /**
   * @return SecurityPolicyAdvancedOptionsConfigJsonCustomConfig
   */
  public function getJsonCustomConfig()
  {
    return $this->jsonCustomConfig;
  }
  /**
   * @param string
   */
  public function setJsonParsing($jsonParsing)
  {
    $this->jsonParsing = $jsonParsing;
  }
  /**
   * @return string
   */
  public function getJsonParsing()
  {
    return $this->jsonParsing;
  }
  /**
   * @param string
   */
  public function setLogLevel($logLevel)
  {
    $this->logLevel = $logLevel;
  }
  /**
   * @return string
   */
  public function getLogLevel()
  {
    return $this->logLevel;
  }
  /**
   * @param string[]
   */
  public function setUserIpRequestHeaders($userIpRequestHeaders)
  {
    $this->userIpRequestHeaders = $userIpRequestHeaders;
  }
  /**
   * @return string[]
   */
  public function getUserIpRequestHeaders()
  {
    return $this->userIpRequestHeaders;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SecurityPolicyAdvancedOptionsConfig::class, 'Google_Service_Compute_SecurityPolicyAdvancedOptionsConfig');
