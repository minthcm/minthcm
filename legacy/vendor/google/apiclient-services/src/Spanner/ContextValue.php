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

namespace Google\Service\Spanner;

class ContextValue extends \Google\Model
{
  protected $labelType = LocalizedString::class;
  protected $labelDataType = '';
  /**
   * @var string
   */
  public $severity;
  /**
   * @var string
   */
  public $unit;
  /**
   * @var float
   */
  public $value;

  /**
   * @param LocalizedString
   */
  public function setLabel(LocalizedString $label)
  {
    $this->label = $label;
  }
  /**
   * @return LocalizedString
   */
  public function getLabel()
  {
    return $this->label;
  }
  /**
   * @param string
   */
  public function setSeverity($severity)
  {
    $this->severity = $severity;
  }
  /**
   * @return string
   */
  public function getSeverity()
  {
    return $this->severity;
  }
  /**
   * @param string
   */
  public function setUnit($unit)
  {
    $this->unit = $unit;
  }
  /**
   * @return string
   */
  public function getUnit()
  {
    return $this->unit;
  }
  /**
   * @param float
   */
  public function setValue($value)
  {
    $this->value = $value;
  }
  /**
   * @return float
   */
  public function getValue()
  {
    return $this->value;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ContextValue::class, 'Google_Service_Spanner_ContextValue');
