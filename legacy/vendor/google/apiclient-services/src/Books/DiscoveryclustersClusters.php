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

namespace Google\Service\Books;

class DiscoveryclustersClusters extends \Google\Collection
{
  protected $collection_key = 'volumes';
  protected $internal_gapi_mappings = [
        "bannerWithContentContainer" => "banner_with_content_container",
  ];
  protected $bannerWithContentContainerType = DiscoveryclustersClustersBannerWithContentContainer::class;
  protected $bannerWithContentContainerDataType = '';
  /**
   * @var string
   */
  public $subTitle;
  /**
   * @var string
   */
  public $title;
  /**
   * @var int
   */
  public $totalVolumes;
  /**
   * @var string
   */
  public $uid;
  protected $volumesType = Volume::class;
  protected $volumesDataType = 'array';

  /**
   * @param DiscoveryclustersClustersBannerWithContentContainer
   */
  public function setBannerWithContentContainer(DiscoveryclustersClustersBannerWithContentContainer $bannerWithContentContainer)
  {
    $this->bannerWithContentContainer = $bannerWithContentContainer;
  }
  /**
   * @return DiscoveryclustersClustersBannerWithContentContainer
   */
  public function getBannerWithContentContainer()
  {
    return $this->bannerWithContentContainer;
  }
  /**
   * @param string
   */
  public function setSubTitle($subTitle)
  {
    $this->subTitle = $subTitle;
  }
  /**
   * @return string
   */
  public function getSubTitle()
  {
    return $this->subTitle;
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
  /**
   * @param int
   */
  public function setTotalVolumes($totalVolumes)
  {
    $this->totalVolumes = $totalVolumes;
  }
  /**
   * @return int
   */
  public function getTotalVolumes()
  {
    return $this->totalVolumes;
  }
  /**
   * @param string
   */
  public function setUid($uid)
  {
    $this->uid = $uid;
  }
  /**
   * @return string
   */
  public function getUid()
  {
    return $this->uid;
  }
  /**
   * @param Volume[]
   */
  public function setVolumes($volumes)
  {
    $this->volumes = $volumes;
  }
  /**
   * @return Volume[]
   */
  public function getVolumes()
  {
    return $this->volumes;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DiscoveryclustersClusters::class, 'Google_Service_Books_DiscoveryclustersClusters');
