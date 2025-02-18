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

namespace Google\Service\AndroidProvisioningPartner;

class Company extends \Google\Collection
{
  protected $collection_key = 'ownerEmails';
  /**
   * @var string[]
   */
  public $adminEmails;
  /**
   * @var string
   */
  public $companyId;
  /**
   * @var string
   */
  public $companyName;
  protected $googleWorkspaceAccountType = GoogleWorkspaceAccount::class;
  protected $googleWorkspaceAccountDataType = '';
  /**
   * @var string
   */
  public $languageCode;
  /**
   * @var string
   */
  public $name;
  /**
   * @var string[]
   */
  public $ownerEmails;
  /**
   * @var bool
   */
  public $skipWelcomeEmail;
  /**
   * @var string
   */
  public $termsStatus;

  /**
   * @param string[]
   */
  public function setAdminEmails($adminEmails)
  {
    $this->adminEmails = $adminEmails;
  }
  /**
   * @return string[]
   */
  public function getAdminEmails()
  {
    return $this->adminEmails;
  }
  /**
   * @param string
   */
  public function setCompanyId($companyId)
  {
    $this->companyId = $companyId;
  }
  /**
   * @return string
   */
  public function getCompanyId()
  {
    return $this->companyId;
  }
  /**
   * @param string
   */
  public function setCompanyName($companyName)
  {
    $this->companyName = $companyName;
  }
  /**
   * @return string
   */
  public function getCompanyName()
  {
    return $this->companyName;
  }
  /**
   * @param GoogleWorkspaceAccount
   */
  public function setGoogleWorkspaceAccount(GoogleWorkspaceAccount $googleWorkspaceAccount)
  {
    $this->googleWorkspaceAccount = $googleWorkspaceAccount;
  }
  /**
   * @return GoogleWorkspaceAccount
   */
  public function getGoogleWorkspaceAccount()
  {
    return $this->googleWorkspaceAccount;
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
   * @param string[]
   */
  public function setOwnerEmails($ownerEmails)
  {
    $this->ownerEmails = $ownerEmails;
  }
  /**
   * @return string[]
   */
  public function getOwnerEmails()
  {
    return $this->ownerEmails;
  }
  /**
   * @param bool
   */
  public function setSkipWelcomeEmail($skipWelcomeEmail)
  {
    $this->skipWelcomeEmail = $skipWelcomeEmail;
  }
  /**
   * @return bool
   */
  public function getSkipWelcomeEmail()
  {
    return $this->skipWelcomeEmail;
  }
  /**
   * @param string
   */
  public function setTermsStatus($termsStatus)
  {
    $this->termsStatus = $termsStatus;
  }
  /**
   * @return string
   */
  public function getTermsStatus()
  {
    return $this->termsStatus;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Company::class, 'Google_Service_AndroidProvisioningPartner_Company');
