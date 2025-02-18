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

namespace Google\Service\AccessApproval;

class ApproveDecision extends \Google\Model
{
  /**
   * @var string
   */
  public $approveTime;
  /**
   * @var bool
   */
  public $autoApproved;
  /**
   * @var string
   */
  public $expireTime;
  /**
   * @var string
   */
  public $invalidateTime;
  protected $signatureInfoType = SignatureInfo::class;
  protected $signatureInfoDataType = '';

  /**
   * @param string
   */
  public function setApproveTime($approveTime)
  {
    $this->approveTime = $approveTime;
  }
  /**
   * @return string
   */
  public function getApproveTime()
  {
    return $this->approveTime;
  }
  /**
   * @param bool
   */
  public function setAutoApproved($autoApproved)
  {
    $this->autoApproved = $autoApproved;
  }
  /**
   * @return bool
   */
  public function getAutoApproved()
  {
    return $this->autoApproved;
  }
  /**
   * @param string
   */
  public function setExpireTime($expireTime)
  {
    $this->expireTime = $expireTime;
  }
  /**
   * @return string
   */
  public function getExpireTime()
  {
    return $this->expireTime;
  }
  /**
   * @param string
   */
  public function setInvalidateTime($invalidateTime)
  {
    $this->invalidateTime = $invalidateTime;
  }
  /**
   * @return string
   */
  public function getInvalidateTime()
  {
    return $this->invalidateTime;
  }
  /**
   * @param SignatureInfo
   */
  public function setSignatureInfo(SignatureInfo $signatureInfo)
  {
    $this->signatureInfo = $signatureInfo;
  }
  /**
   * @return SignatureInfo
   */
  public function getSignatureInfo()
  {
    return $this->signatureInfo;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ApproveDecision::class, 'Google_Service_AccessApproval_ApproveDecision');
