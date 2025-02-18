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

namespace Google\Service\CloudSearch;

class Person extends \Google\Collection
{
  protected $collection_key = 'photos';
  protected $emailAddressesType = EmailAddress::class;
  protected $emailAddressesDataType = 'array';
  /**
   * @var string
   */
  public $name;
  /**
   * @var string
   */
  public $obfuscatedId;
  protected $personNamesType = Name::class;
  protected $personNamesDataType = 'array';
  protected $phoneNumbersType = PhoneNumber::class;
  protected $phoneNumbersDataType = 'array';
  protected $photosType = Photo::class;
  protected $photosDataType = 'array';

  /**
   * @param EmailAddress[]
   */
  public function setEmailAddresses($emailAddresses)
  {
    $this->emailAddresses = $emailAddresses;
  }
  /**
   * @return EmailAddress[]
   */
  public function getEmailAddresses()
  {
    return $this->emailAddresses;
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
  public function setObfuscatedId($obfuscatedId)
  {
    $this->obfuscatedId = $obfuscatedId;
  }
  /**
   * @return string
   */
  public function getObfuscatedId()
  {
    return $this->obfuscatedId;
  }
  /**
   * @param Name[]
   */
  public function setPersonNames($personNames)
  {
    $this->personNames = $personNames;
  }
  /**
   * @return Name[]
   */
  public function getPersonNames()
  {
    return $this->personNames;
  }
  /**
   * @param PhoneNumber[]
   */
  public function setPhoneNumbers($phoneNumbers)
  {
    $this->phoneNumbers = $phoneNumbers;
  }
  /**
   * @return PhoneNumber[]
   */
  public function getPhoneNumbers()
  {
    return $this->phoneNumbers;
  }
  /**
   * @param Photo[]
   */
  public function setPhotos($photos)
  {
    $this->photos = $photos;
  }
  /**
   * @return Photo[]
   */
  public function getPhotos()
  {
    return $this->photos;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Person::class, 'Google_Service_CloudSearch_Person');
