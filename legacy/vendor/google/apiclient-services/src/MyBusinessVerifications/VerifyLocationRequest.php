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

namespace Google\Service\MyBusinessVerifications;

class VerifyLocationRequest extends \Google\Model
{
  protected $contextType = ServiceBusinessContext::class;
  protected $contextDataType = '';
  /**
   * @var string
   */
  public $emailAddress;
  /**
   * @var string
   */
  public $languageCode;
  /**
   * @var string
   */
  public $mailerContact;
  /**
   * @var string
   */
  public $method;
  /**
   * @var string
   */
  public $phoneNumber;
  protected $tokenType = VerificationToken::class;
  protected $tokenDataType = '';

  /**
   * @param ServiceBusinessContext
   */
  public function setContext(ServiceBusinessContext $context)
  {
    $this->context = $context;
  }
  /**
   * @return ServiceBusinessContext
   */
  public function getContext()
  {
    return $this->context;
  }
  /**
   * @param string
   */
  public function setEmailAddress($emailAddress)
  {
    $this->emailAddress = $emailAddress;
  }
  /**
   * @return string
   */
  public function getEmailAddress()
  {
    return $this->emailAddress;
  }
  /**
   * @param string
   */
  public function setLanguageCode($languageCode)
  {
    $this->languageCode = $languageCode;
  }
  /**
   * @return string
   */
  public function getLanguageCode()
  {
    return $this->languageCode;
  }
  /**
   * @param string
   */
  public function setMailerContact($mailerContact)
  {
    $this->mailerContact = $mailerContact;
  }
  /**
   * @return string
   */
  public function getMailerContact()
  {
    return $this->mailerContact;
  }
  /**
   * @param string
   */
  public function setMethod($method)
  {
    $this->method = $method;
  }
  /**
   * @return string
   */
  public function getMethod()
  {
    return $this->method;
  }
  /**
   * @param string
   */
  public function setPhoneNumber($phoneNumber)
  {
    $this->phoneNumber = $phoneNumber;
  }
  /**
   * @return string
   */
  public function getPhoneNumber()
  {
    return $this->phoneNumber;
  }
  /**
   * @param VerificationToken
   */
  public function setToken(VerificationToken $token)
  {
    $this->token = $token;
  }
  /**
   * @return VerificationToken
   */
  public function getToken()
  {
    return $this->token;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(VerifyLocationRequest::class, 'Google_Service_MyBusinessVerifications_VerifyLocationRequest');
