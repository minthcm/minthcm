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

namespace Google\Service\Bigquery;

class ProjectListProjects extends \Google\Model
{
  /**
   * @var string
   */
  public $friendlyName;
  /**
   * @var string
   */
  public $id;
  /**
   * @var string
   */
  public $kind;
  /**
   * @var string
   */
  public $numericId;
  protected $projectReferenceType = ProjectReference::class;
  protected $projectReferenceDataType = '';

  /**
   * @param string
   */
  public function setFriendlyName($friendlyName)
  {
    $this->friendlyName = $friendlyName;
  }
  /**
   * @return string
   */
  public function getFriendlyName()
  {
    return $this->friendlyName;
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
   * @param string
   */
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  /**
   * @return string
   */
  public function getKind()
  {
    return $this->kind;
  }
  /**
   * @param string
   */
  public function setNumericId($numericId)
  {
    $this->numericId = $numericId;
  }
  /**
   * @return string
   */
  public function getNumericId()
  {
    return $this->numericId;
  }
  /**
   * @param ProjectReference
   */
  public function setProjectReference(ProjectReference $projectReference)
  {
    $this->projectReference = $projectReference;
  }
  /**
   * @return ProjectReference
   */
  public function getProjectReference()
  {
    return $this->projectReference;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectListProjects::class, 'Google_Service_Bigquery_ProjectListProjects');
