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

namespace Google\Service\CloudIdentity;

class InboundSamlSsoProfile extends \Google\Model
{
  /**
   * @var string
   */
  public $customer;
  /**
   * @var string
   */
  public $displayName;
  protected $idpConfigType = SamlIdpConfig::class;
  protected $idpConfigDataType = '';
  /**
   * @var string
   */
  public $name;
  protected $spConfigType = SamlSpConfig::class;
  protected $spConfigDataType = '';

  /**
   * @param string
   */
  public function setCustomer($customer)
  {
    $this->customer = $customer;
  }
  /**
   * @return string
   */
  public function getCustomer()
  {
    return $this->customer;
  }
  /**
   * @param string
   */
  public function setDisplayName($displayName)
  {
    $this->displayName = $displayName;
  }
  /**
   * @return string
   */
  public function getDisplayName()
  {
    return $this->displayName;
  }
  /**
   * @param SamlIdpConfig
   */
  public function setIdpConfig(SamlIdpConfig $idpConfig)
  {
    $this->idpConfig = $idpConfig;
  }
  /**
   * @return SamlIdpConfig
   */
  public function getIdpConfig()
  {
    return $this->idpConfig;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
  /**
   * @param SamlSpConfig
   */
  public function setSpConfig(SamlSpConfig $spConfig)
  {
    $this->spConfig = $spConfig;
  }
  /**
   * @return SamlSpConfig
   */
  public function getSpConfig()
  {
    return $this->spConfig;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(InboundSamlSsoProfile::class, 'Google_Service_CloudIdentity_InboundSamlSsoProfile');
