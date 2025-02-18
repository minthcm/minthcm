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

class GoogleCloudSecuritycenterV2ContactDetails extends \Google\Collection
{
  protected $collection_key = 'contacts';
  protected $contactsType = GoogleCloudSecuritycenterV2Contact::class;
  protected $contactsDataType = 'array';

  /**
   * @param GoogleCloudSecuritycenterV2Contact[]
   */
  public function setContacts($contacts)
  {
    $this->contacts = $contacts;
  }
  /**
   * @return GoogleCloudSecuritycenterV2Contact[]
   */
  public function getContacts()
  {
    return $this->contacts;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudSecuritycenterV2ContactDetails::class, 'Google_Service_SecurityCommandCenter_GoogleCloudSecuritycenterV2ContactDetails');
