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

namespace Google\Service\CloudHealthcare;

class ConsentArtifact extends \Google\Collection
{
  protected $collection_key = 'consentContentScreenshots';
  protected $consentContentScreenshotsType = Image::class;
  protected $consentContentScreenshotsDataType = 'array';
  /**
   * @var string
   */
  public $consentContentVersion;
  protected $guardianSignatureType = Signature::class;
  protected $guardianSignatureDataType = '';
  /**
   * @var string[]
   */
  public $metadata;
  /**
   * @var string
   */
  public $name;
  /**
   * @var string
   */
  public $userId;
  protected $userSignatureType = Signature::class;
  protected $userSignatureDataType = '';
  protected $witnessSignatureType = Signature::class;
  protected $witnessSignatureDataType = '';

  /**
   * @param Image[]
   */
  public function setConsentContentScreenshots($consentContentScreenshots)
  {
    $this->consentContentScreenshots = $consentContentScreenshots;
  }
  /**
   * @return Image[]
   */
  public function getConsentContentScreenshots()
  {
    return $this->consentContentScreenshots;
  }
  /**
   * @param string
   */
  public function setConsentContentVersion($consentContentVersion)
  {
    $this->consentContentVersion = $consentContentVersion;
  }
  /**
   * @return string
   */
  public function getConsentContentVersion()
  {
    return $this->consentContentVersion;
  }
  /**
   * @param Signature
   */
  public function setGuardianSignature(Signature $guardianSignature)
  {
    $this->guardianSignature = $guardianSignature;
  }
  /**
   * @return Signature
   */
  public function getGuardianSignature()
  {
    return $this->guardianSignature;
  }
  /**
   * @param string[]
   */
  public function setMetadata($metadata)
  {
    $this->metadata = $metadata;
  }
  /**
   * @return string[]
   */
  public function getMetadata()
  {
    return $this->metadata;
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
   * @param string
   */
  public function setUserId($userId)
  {
    $this->userId = $userId;
  }
  /**
   * @return string
   */
  public function getUserId()
  {
    return $this->userId;
  }
  /**
   * @param Signature
   */
  public function setUserSignature(Signature $userSignature)
  {
    $this->userSignature = $userSignature;
  }
  /**
   * @return Signature
   */
  public function getUserSignature()
  {
    return $this->userSignature;
  }
  /**
   * @param Signature
   */
  public function setWitnessSignature(Signature $witnessSignature)
  {
    $this->witnessSignature = $witnessSignature;
  }
  /**
   * @return Signature
   */
  public function getWitnessSignature()
  {
    return $this->witnessSignature;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ConsentArtifact::class, 'Google_Service_CloudHealthcare_ConsentArtifact');
