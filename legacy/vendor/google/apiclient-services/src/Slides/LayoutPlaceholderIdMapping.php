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

namespace Google\Service\Slides;

class LayoutPlaceholderIdMapping extends \Google\Model
{
  protected $layoutPlaceholderType = Placeholder::class;
  protected $layoutPlaceholderDataType = '';
  /**
   * @var string
   */
  public $layoutPlaceholderObjectId;
  /**
   * @var string
   */
  public $objectId;

  /**
   * @param Placeholder
   */
  public function setLayoutPlaceholder(Placeholder $layoutPlaceholder)
  {
    $this->layoutPlaceholder = $layoutPlaceholder;
  }
  /**
   * @return Placeholder
   */
  public function getLayoutPlaceholder()
  {
    return $this->layoutPlaceholder;
  }
  /**
   * @param string
   */
  public function setLayoutPlaceholderObjectId($layoutPlaceholderObjectId)
  {
    $this->layoutPlaceholderObjectId = $layoutPlaceholderObjectId;
  }
  /**
   * @return string
   */
  public function getLayoutPlaceholderObjectId()
  {
    return $this->layoutPlaceholderObjectId;
  }
  /**
   * @param string
   */
  public function setObjectId($objectId)
  {
    $this->objectId = $objectId;
  }
  /**
   * @return string
   */
  public function getObjectId()
  {
    return $this->objectId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(LayoutPlaceholderIdMapping::class, 'Google_Service_Slides_LayoutPlaceholderIdMapping');
