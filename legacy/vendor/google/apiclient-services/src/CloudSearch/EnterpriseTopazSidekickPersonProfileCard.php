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

class EnterpriseTopazSidekickPersonProfileCard extends \Google\Collection
{
  protected $collection_key = 'relatedPeople';
  protected $relatedPeopleType = EnterpriseTopazSidekickPersonProfileCardRelatedPeople::class;
  protected $relatedPeopleDataType = 'array';
  protected $subjectType = EnterpriseTopazSidekickCommonPerson::class;
  protected $subjectDataType = '';

  /**
   * @param EnterpriseTopazSidekickPersonProfileCardRelatedPeople[]
   */
  public function setRelatedPeople($relatedPeople)
  {
    $this->relatedPeople = $relatedPeople;
  }
  /**
   * @return EnterpriseTopazSidekickPersonProfileCardRelatedPeople[]
   */
  public function getRelatedPeople()
  {
    return $this->relatedPeople;
  }
  /**
   * @param EnterpriseTopazSidekickCommonPerson
   */
  public function setSubject(EnterpriseTopazSidekickCommonPerson $subject)
  {
    $this->subject = $subject;
  }
  /**
   * @return EnterpriseTopazSidekickCommonPerson
   */
  public function getSubject()
  {
    return $this->subject;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(EnterpriseTopazSidekickPersonProfileCard::class, 'Google_Service_CloudSearch_EnterpriseTopazSidekickPersonProfileCard');
