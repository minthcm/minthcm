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

namespace Google\Service\Verifiedaccess;

class VerifyChallengeResponseResult extends \Google\Model
{
  /**
   * @var string
   */
  public $attestedDeviceId;
  /**
   * @var string
   */
  public $customerId;
  /**
   * @var string
   */
  public $deviceEnrollmentId;
  /**
   * @var string
   */
  public $devicePermanentId;
  /**
   * @var string
   */
  public $deviceSignal;
  protected $deviceSignalsType = DeviceSignals::class;
  protected $deviceSignalsDataType = '';
  /**
   * @var string
   */
  public $keyTrustLevel;
  /**
   * @var string
   */
  public $profileCustomerId;
  /**
   * @var string
   */
  public $profileKeyTrustLevel;
  /**
   * @var string
   */
  public $signedPublicKeyAndChallenge;
  /**
   * @var string
   */
  public $virtualDeviceId;
  /**
   * @var string
   */
  public $virtualProfileId;

  /**
   * @param string
   */
  public function setAttestedDeviceId($attestedDeviceId)
  {
    $this->attestedDeviceId = $attestedDeviceId;
  }
  /**
   * @return string
   */
  public function getAttestedDeviceId()
  {
    return $this->attestedDeviceId;
  }
  /**
   * @param string
   */
  public function setCustomerId($customerId)
  {
    $this->customerId = $customerId;
  }
  /**
   * @return string
   */
  public function getCustomerId()
  {
    return $this->customerId;
  }
  /**
   * @param string
   */
  public function setDeviceEnrollmentId($deviceEnrollmentId)
  {
    $this->deviceEnrollmentId = $deviceEnrollmentId;
  }
  /**
   * @return string
   */
  public function getDeviceEnrollmentId()
  {
    return $this->deviceEnrollmentId;
  }
  /**
   * @param string
   */
  public function setDevicePermanentId($devicePermanentId)
  {
    $this->devicePermanentId = $devicePermanentId;
  }
  /**
   * @return string
   */
  public function getDevicePermanentId()
  {
    return $this->devicePermanentId;
  }
  /**
   * @param string
   */
  public function setDeviceSignal($deviceSignal)
  {
    $this->deviceSignal = $deviceSignal;
  }
  /**
   * @return string
   */
  public function getDeviceSignal()
  {
    return $this->deviceSignal;
  }
  /**
   * @param DeviceSignals
   */
  public function setDeviceSignals(DeviceSignals $deviceSignals)
  {
    $this->deviceSignals = $deviceSignals;
  }
  /**
   * @return DeviceSignals
   */
  public function getDeviceSignals()
  {
    return $this->deviceSignals;
  }
  /**
   * @param string
   */
  public function setKeyTrustLevel($keyTrustLevel)
  {
    $this->keyTrustLevel = $keyTrustLevel;
  }
  /**
   * @return string
   */
  public function getKeyTrustLevel()
  {
    return $this->keyTrustLevel;
  }
  /**
   * @param string
   */
  public function setProfileCustomerId($profileCustomerId)
  {
    $this->profileCustomerId = $profileCustomerId;
  }
  /**
   * @return string
   */
  public function getProfileCustomerId()
  {
    return $this->profileCustomerId;
  }
  /**
   * @param string
   */
  public function setProfileKeyTrustLevel($profileKeyTrustLevel)
  {
    $this->profileKeyTrustLevel = $profileKeyTrustLevel;
  }
  /**
   * @return string
   */
  public function getProfileKeyTrustLevel()
  {
    return $this->profileKeyTrustLevel;
  }
  /**
   * @param string
   */
  public function setSignedPublicKeyAndChallenge($signedPublicKeyAndChallenge)
  {
    $this->signedPublicKeyAndChallenge = $signedPublicKeyAndChallenge;
  }
  /**
   * @return string
   */
  public function getSignedPublicKeyAndChallenge()
  {
    return $this->signedPublicKeyAndChallenge;
  }
  /**
   * @param string
   */
  public function setVirtualDeviceId($virtualDeviceId)
  {
    $this->virtualDeviceId = $virtualDeviceId;
  }
  /**
   * @return string
   */
  public function getVirtualDeviceId()
  {
    return $this->virtualDeviceId;
  }
  /**
   * @param string
   */
  public function setVirtualProfileId($virtualProfileId)
  {
    $this->virtualProfileId = $virtualProfileId;
  }
  /**
   * @return string
   */
  public function getVirtualProfileId()
  {
    return $this->virtualProfileId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(VerifyChallengeResponseResult::class, 'Google_Service_Verifiedaccess_VerifyChallengeResponseResult');
