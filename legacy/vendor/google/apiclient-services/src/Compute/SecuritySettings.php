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

class SecuritySettings extends \Google\Collection
{
  protected $collection_key = 'subjectAltNames';
  protected $awsV4AuthenticationType = AWSV4Signature::class;
  protected $awsV4AuthenticationDataType = '';
  /**
   * @var string
   */
  public $clientTlsPolicy;
  /**
   * @var string[]
   */
  public $subjectAltNames;

  /**
   * @param AWSV4Signature
   */
  public function setAwsV4Authentication(AWSV4Signature $awsV4Authentication)
  {
    $this->awsV4Authentication = $awsV4Authentication;
  }
  /**
   * @return AWSV4Signature
   */
  public function getAwsV4Authentication()
  {
    return $this->awsV4Authentication;
  }
  /**
   * @param string
   */
  public function setClientTlsPolicy($clientTlsPolicy)
  {
    $this->clientTlsPolicy = $clientTlsPolicy;
  }
  /**
   * @return string
   */
  public function getClientTlsPolicy()
  {
    return $this->clientTlsPolicy;
  }
  /**
   * @param string[]
   */
  public function setSubjectAltNames($subjectAltNames)
  {
    $this->subjectAltNames = $subjectAltNames;
  }
  /**
   * @return string[]
   */
  public function getSubjectAltNames()
  {
    return $this->subjectAltNames;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SecuritySettings::class, 'Google_Service_Compute_SecuritySettings');
