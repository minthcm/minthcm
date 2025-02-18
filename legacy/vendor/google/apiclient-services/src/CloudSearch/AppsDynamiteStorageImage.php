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

class AppsDynamiteStorageImage extends \Google\Model
{
  /**
   * @var string
   */
  public $altText;
  /**
   * @var string
   */
  public $imageUrl;
  protected $onClickType = AppsDynamiteStorageOnClick::class;
  protected $onClickDataType = '';

  /**
   * @param string
   */
  public function setAltText($altText)
  {
    $this->altText = $altText;
  }
  /**
   * @return string
   */
  public function getAltText()
  {
    return $this->altText;
  }
  /**
   * @param string
   */
  public function setImageUrl($imageUrl)
  {
    $this->imageUrl = $imageUrl;
  }
  /**
   * @return string
   */
  public function getImageUrl()
  {
    return $this->imageUrl;
  }
  /**
   * @param AppsDynamiteStorageOnClick
   */
  public function setOnClick(AppsDynamiteStorageOnClick $onClick)
  {
    $this->onClick = $onClick;
  }
  /**
   * @return AppsDynamiteStorageOnClick
   */
  public function getOnClick()
  {
    return $this->onClick;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AppsDynamiteStorageImage::class, 'Google_Service_CloudSearch_AppsDynamiteStorageImage');
