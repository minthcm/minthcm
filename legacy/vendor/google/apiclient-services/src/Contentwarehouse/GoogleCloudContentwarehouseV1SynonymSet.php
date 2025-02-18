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

namespace Google\Service\Contentwarehouse;

class GoogleCloudContentwarehouseV1SynonymSet extends \Google\Collection
{
  protected $collection_key = 'synonyms';
  /**
   * @var string
   */
  public $context;
  /**
   * @var string
   */
  public $name;
  protected $synonymsType = GoogleCloudContentwarehouseV1SynonymSetSynonym::class;
  protected $synonymsDataType = 'array';

  /**
   * @param string
   */
  public function setContext($context)
  {
    $this->context = $context;
  }
  /**
   * @return string
   */
  public function getContext()
  {
    return $this->context;
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
   * @param GoogleCloudContentwarehouseV1SynonymSetSynonym[]
   */
  public function setSynonyms($synonyms)
  {
    $this->synonyms = $synonyms;
  }
  /**
   * @return GoogleCloudContentwarehouseV1SynonymSetSynonym[]
   */
  public function getSynonyms()
  {
    return $this->synonyms;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudContentwarehouseV1SynonymSet::class, 'Google_Service_Contentwarehouse_GoogleCloudContentwarehouseV1SynonymSet');
