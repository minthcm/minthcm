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

namespace Google\Service\Testing;

class AndroidVersion extends \Google\Collection
{
  protected $collection_key = 'tags';
  /**
   * @var int
   */
  public $apiLevel;
  /**
   * @var string
   */
  public $codeName;
  protected $distributionType = Distribution::class;
  protected $distributionDataType = '';
  /**
   * @var string
   */
  public $id;
  protected $releaseDateType = Date::class;
  protected $releaseDateDataType = '';
  /**
   * @var string[]
   */
  public $tags;
  /**
   * @var string
   */
  public $versionString;

  /**
   * @param int
   */
  public function setApiLevel($apiLevel)
  {
    $this->apiLevel = $apiLevel;
  }
  /**
   * @return int
   */
  public function getApiLevel()
  {
    return $this->apiLevel;
  }
  /**
   * @param string
   */
  public function setCodeName($codeName)
  {
    $this->codeName = $codeName;
  }
  /**
   * @return string
   */
  public function getCodeName()
  {
    return $this->codeName;
  }
  /**
   * @param Distribution
   */
  public function setDistribution(Distribution $distribution)
  {
    $this->distribution = $distribution;
  }
  /**
   * @return Distribution
   */
  public function getDistribution()
  {
    return $this->distribution;
  }
  /**
   * @param string
   */
  public function setId($id)
  {
    $this->id = $id;
  }
  /**
   * @return string
   */
  public function getId()
  {
    return $this->id;
  }
  /**
   * @param Date
   */
  public function setReleaseDate(Date $releaseDate)
  {
    $this->releaseDate = $releaseDate;
  }
  /**
   * @return Date
   */
  public function getReleaseDate()
  {
    return $this->releaseDate;
  }
  /**
   * @param string[]
   */
  public function setTags($tags)
  {
    $this->tags = $tags;
  }
  /**
   * @return string[]
   */
  public function getTags()
  {
    return $this->tags;
  }
  /**
   * @param string
   */
  public function setVersionString($versionString)
  {
    $this->versionString = $versionString;
  }
  /**
   * @return string
   */
  public function getVersionString()
  {
    return $this->versionString;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AndroidVersion::class, 'Google_Service_Testing_AndroidVersion');
