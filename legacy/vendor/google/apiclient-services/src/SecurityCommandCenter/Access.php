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

namespace Google\Service\SecurityCommandCenter;

class Access extends \Google\Collection
{
  protected $collection_key = 'serviceAccountDelegationInfo';
  /**
   * @var string
   */
  public $callerIp;
  protected $callerIpGeoType = Geolocation::class;
  protected $callerIpGeoDataType = '';
  /**
   * @var string
   */
  public $methodName;
  /**
   * @var string
   */
  public $principalEmail;
  /**
   * @var string
   */
  public $principalSubject;
  protected $serviceAccountDelegationInfoType = ServiceAccountDelegationInfo::class;
  protected $serviceAccountDelegationInfoDataType = 'array';
  /**
   * @var string
   */
  public $serviceAccountKeyName;
  /**
   * @var string
   */
  public $serviceName;
  /**
   * @var string
   */
  public $userAgent;
  /**
   * @var string
   */
  public $userAgentFamily;
  /**
   * @var string
   */
  public $userName;

  /**
   * @param string
   */
  public function setCallerIp($callerIp)
  {
    $this->callerIp = $callerIp;
  }
  /**
   * @return string
   */
  public function getCallerIp()
  {
    return $this->callerIp;
  }
  /**
   * @param Geolocation
   */
  public function setCallerIpGeo(Geolocation $callerIpGeo)
  {
    $this->callerIpGeo = $callerIpGeo;
  }
  /**
   * @return Geolocation
   */
  public function getCallerIpGeo()
  {
    return $this->callerIpGeo;
  }
  /**
   * @param string
   */
  public function setMethodName($methodName)
  {
    $this->methodName = $methodName;
  }
  /**
   * @return string
   */
  public function getMethodName()
  {
    return $this->methodName;
  }
  /**
   * @param string
   */
  public function setPrincipalEmail($principalEmail)
  {
    $this->principalEmail = $principalEmail;
  }
  /**
   * @return string
   */
  public function getPrincipalEmail()
  {
    return $this->principalEmail;
  }
  /**
   * @param string
   */
  public function setPrincipalSubject($principalSubject)
  {
    $this->principalSubject = $principalSubject;
  }
  /**
   * @return string
   */
  public function getPrincipalSubject()
  {
    return $this->principalSubject;
  }
  /**
   * @param ServiceAccountDelegationInfo[]
   */
  public function setServiceAccountDelegationInfo($serviceAccountDelegationInfo)
  {
    $this->serviceAccountDelegationInfo = $serviceAccountDelegationInfo;
  }
  /**
   * @return ServiceAccountDelegationInfo[]
   */
  public function getServiceAccountDelegationInfo()
  {
    return $this->serviceAccountDelegationInfo;
  }
  /**
   * @param string
   */
  public function setServiceAccountKeyName($serviceAccountKeyName)
  {
    $this->serviceAccountKeyName = $serviceAccountKeyName;
  }
  /**
   * @return string
   */
  public function getServiceAccountKeyName()
  {
    return $this->serviceAccountKeyName;
  }
  /**
   * @param string
   */
  public function setServiceName($serviceName)
  {
    $this->serviceName = $serviceName;
  }
  /**
   * @return string
   */
  public function getServiceName()
  {
    return $this->serviceName;
  }
  /**
   * @param string
   */
  public function setUserAgent($userAgent)
  {
    $this->userAgent = $userAgent;
  }
  /**
   * @return string
   */
  public function getUserAgent()
  {
    return $this->userAgent;
  }
  /**
   * @param string
   */
  public function setUserAgentFamily($userAgentFamily)
  {
    $this->userAgentFamily = $userAgentFamily;
  }
  /**
   * @return string
   */
  public function getUserAgentFamily()
  {
    return $this->userAgentFamily;
  }
  /**
   * @param string
   */
  public function setUserName($userName)
  {
    $this->userName = $userName;
  }
  /**
   * @return string
   */
  public function getUserName()
  {
    return $this->userName;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Access::class, 'Google_Service_SecurityCommandCenter_Access');
