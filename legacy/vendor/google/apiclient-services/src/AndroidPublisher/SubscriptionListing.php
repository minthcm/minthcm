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

namespace Google\Service\AndroidPublisher;

class SubscriptionListing extends \Google\Collection
{
  protected $collection_key = 'benefits';
  /**
   * @var string[]
   */
  public $benefits;
  /**
   * @var string
   */
  public $description;
  /**
   * @var string
   */
  public $languageCode;
  /**
   * @var string
   */
  public $title;

  /**
   * @param string[]
   */
  public function setBenefits($benefits)
  {
    $this->benefits = $benefits;
  }
  /**
   * @return string[]
   */
  public function getBenefits()
  {
    return $this->benefits;
  }
  /**
   * @param string
   */
  public function setDescription($description)
  {
    $this->description = $description;
  }
  /**
   * @return string
   */
  public function getDescription()
  {
    return $this->description;
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
  public function setTitle($title)
  {
    $this->title = $title;
  }
  /**
   * @return string
   */
  public function getTitle()
  {
    return $this->title;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SubscriptionListing::class, 'Google_Service_AndroidPublisher_SubscriptionListing');
