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

namespace Google\Service\SA360;

class GoogleAdsSearchads360V0ErrorsSearchAds360Error extends \Google\Model
{
  protected $detailsType = GoogleAdsSearchads360V0ErrorsErrorDetails::class;
  protected $detailsDataType = '';
  protected $errorCodeType = GoogleAdsSearchads360V0ErrorsErrorCode::class;
  protected $errorCodeDataType = '';
  protected $locationType = GoogleAdsSearchads360V0ErrorsErrorLocation::class;
  protected $locationDataType = '';
  /**
   * @var string
   */
  public $message;
  protected $triggerType = GoogleAdsSearchads360V0CommonValue::class;
  protected $triggerDataType = '';

  /**
   * @param GoogleAdsSearchads360V0ErrorsErrorDetails
   */
  public function setDetails(GoogleAdsSearchads360V0ErrorsErrorDetails $details)
  {
    $this->details = $details;
  }
  /**
   * @return GoogleAdsSearchads360V0ErrorsErrorDetails
   */
  public function getDetails()
  {
    return $this->details;
  }
  /**
   * @param GoogleAdsSearchads360V0ErrorsErrorCode
   */
  public function setErrorCode(GoogleAdsSearchads360V0ErrorsErrorCode $errorCode)
  {
    $this->errorCode = $errorCode;
  }
  /**
   * @return GoogleAdsSearchads360V0ErrorsErrorCode
   */
  public function getErrorCode()
  {
    return $this->errorCode;
  }
  /**
   * @param GoogleAdsSearchads360V0ErrorsErrorLocation
   */
  public function setLocation(GoogleAdsSearchads360V0ErrorsErrorLocation $location)
  {
    $this->location = $location;
  }
  /**
   * @return GoogleAdsSearchads360V0ErrorsErrorLocation
   */
  public function getLocation()
  {
    return $this->location;
  }
  /**
   * @param string
   */
  public function setMessage($message)
  {
    $this->message = $message;
  }
  /**
   * @return string
   */
  public function getMessage()
  {
    return $this->message;
  }
  /**
   * @param GoogleAdsSearchads360V0CommonValue
   */
  public function setTrigger(GoogleAdsSearchads360V0CommonValue $trigger)
  {
    $this->trigger = $trigger;
  }
  /**
   * @return GoogleAdsSearchads360V0CommonValue
   */
  public function getTrigger()
  {
    return $this->trigger;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleAdsSearchads360V0ErrorsSearchAds360Error::class, 'Google_Service_SA360_GoogleAdsSearchads360V0ErrorsSearchAds360Error');
