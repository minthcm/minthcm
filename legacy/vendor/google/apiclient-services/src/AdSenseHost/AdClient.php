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

namespace Google\Service\AdSenseHost;

class AdClient extends \Google\Model
{
  /**
   * @var bool
   */
  public $arcOptIn;
  /**
   * @var string
   */
  public $id;
  /**
   * @var string
   */
  public $kind;
  /**
   * @var string
   */
  public $productCode;
  /**
   * @var bool
   */
  public $supportsReporting;

  /**
   * @param bool
   */
  public function setArcOptIn($arcOptIn)
  {
    $this->arcOptIn = $arcOptIn;
  }
  /**
   * @return bool
   */
  public function getArcOptIn()
  {
    return $this->arcOptIn;
  }
  /**
   * @param string
   */
  public function setId($id)
  {
    $this->id = $id;
  }
  /**
   * @return string
   */
  public function getId()
  {
    return $this->id;
  }
  /**
   * @param string
   */
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  /**
   * @return string
   */
  public function getKind()
  {
    return $this->kind;
  }
  /**
   * @param string
   */
  public function setProductCode($productCode)
  {
    $this->productCode = $productCode;
  }
  /**
   * @return string
   */
  public function getProductCode()
  {
    return $this->productCode;
  }
  /**
   * @param bool
   */
  public function setSupportsReporting($supportsReporting)
  {
    $this->supportsReporting = $supportsReporting;
  }
  /**
   * @return bool
   */
  public function getSupportsReporting()
  {
    return $this->supportsReporting;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AdClient::class, 'Google_Service_AdSenseHost_AdClient');
