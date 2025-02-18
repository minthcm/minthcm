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

namespace Google\Service\Document;

class GoogleCloudDocumentaiV1ProcessorType extends \Google\Collection
{
  protected $collection_key = 'sampleDocumentUris';
  /**
   * @var bool
   */
  public $allowCreation;
  protected $availableLocationsType = GoogleCloudDocumentaiV1ProcessorTypeLocationInfo::class;
  protected $availableLocationsDataType = 'array';
  /**
   * @var string
   */
  public $category;
  /**
   * @var string
   */
  public $launchStage;
  /**
   * @var string
   */
  public $name;
  /**
   * @var string[]
   */
  public $sampleDocumentUris;
  /**
   * @var string
   */
  public $type;

  /**
   * @param bool
   */
  public function setAllowCreation($allowCreation)
  {
    $this->allowCreation = $allowCreation;
  }
  /**
   * @return bool
   */
  public function getAllowCreation()
  {
    return $this->allowCreation;
  }
  /**
   * @param GoogleCloudDocumentaiV1ProcessorTypeLocationInfo[]
   */
  public function setAvailableLocations($availableLocations)
  {
    $this->availableLocations = $availableLocations;
  }
  /**
   * @return GoogleCloudDocumentaiV1ProcessorTypeLocationInfo[]
   */
  public function getAvailableLocations()
  {
    return $this->availableLocations;
  }
  /**
   * @param string
   */
  public function setCategory($category)
  {
    $this->category = $category;
  }
  /**
   * @return string
   */
  public function getCategory()
  {
    return $this->category;
  }
  /**
   * @param string
   */
  public function setLaunchStage($launchStage)
  {
    $this->launchStage = $launchStage;
  }
  /**
   * @return string
   */
  public function getLaunchStage()
  {
    return $this->launchStage;
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
  public function setSampleDocumentUris($sampleDocumentUris)
  {
    $this->sampleDocumentUris = $sampleDocumentUris;
  }
  /**
   * @return string[]
   */
  public function getSampleDocumentUris()
  {
    return $this->sampleDocumentUris;
  }
  /**
   * @param string
   */
  public function setType($type)
  {
    $this->type = $type;
  }
  /**
   * @return string
   */
  public function getType()
  {
    return $this->type;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDocumentaiV1ProcessorType::class, 'Google_Service_Document_GoogleCloudDocumentaiV1ProcessorType');
