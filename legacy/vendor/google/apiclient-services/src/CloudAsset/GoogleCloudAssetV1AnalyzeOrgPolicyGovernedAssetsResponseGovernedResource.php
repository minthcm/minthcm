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

namespace Google\Service\CloudAsset;

class GoogleCloudAssetV1AnalyzeOrgPolicyGovernedAssetsResponseGovernedResource extends \Google\Collection
{
  protected $collection_key = 'folders';
  /**
   * @var string
   */
  public $assetType;
  protected $effectiveTagsType = EffectiveTagDetails::class;
  protected $effectiveTagsDataType = 'array';
  /**
   * @var string[]
   */
  public $folders;
  /**
   * @var string
   */
  public $fullResourceName;
  /**
   * @var string
   */
  public $organization;
  /**
   * @var string
   */
  public $parent;
  /**
   * @var string
   */
  public $project;

  /**
   * @param string
   */
  public function setAssetType($assetType)
  {
    $this->assetType = $assetType;
  }
  /**
   * @return string
   */
  public function getAssetType()
  {
    return $this->assetType;
  }
  /**
   * @param EffectiveTagDetails[]
   */
  public function setEffectiveTags($effectiveTags)
  {
    $this->effectiveTags = $effectiveTags;
  }
  /**
   * @return EffectiveTagDetails[]
   */
  public function getEffectiveTags()
  {
    return $this->effectiveTags;
  }
  /**
   * @param string[]
   */
  public function setFolders($folders)
  {
    $this->folders = $folders;
  }
  /**
   * @return string[]
   */
  public function getFolders()
  {
    return $this->folders;
  }
  /**
   * @param string
   */
  public function setFullResourceName($fullResourceName)
  {
    $this->fullResourceName = $fullResourceName;
  }
  /**
   * @return string
   */
  public function getFullResourceName()
  {
    return $this->fullResourceName;
  }
  /**
   * @param string
   */
  public function setOrganization($organization)
  {
    $this->organization = $organization;
  }
  /**
   * @return string
   */
  public function getOrganization()
  {
    return $this->organization;
  }
  /**
   * @param string
   */
  public function setParent($parent)
  {
    $this->parent = $parent;
  }
  /**
   * @return string
   */
  public function getParent()
  {
    return $this->parent;
  }
  /**
   * @param string
   */
  public function setProject($project)
  {
    $this->project = $project;
  }
  /**
   * @return string
   */
  public function getProject()
  {
    return $this->project;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAssetV1AnalyzeOrgPolicyGovernedAssetsResponseGovernedResource::class, 'Google_Service_CloudAsset_GoogleCloudAssetV1AnalyzeOrgPolicyGovernedAssetsResponseGovernedResource');
