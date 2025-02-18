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

namespace Google\Service\Logging;

class CreateLinkRequest extends \Google\Model
{
  protected $linkType = Link::class;
  protected $linkDataType = '';
  /**
   * @var string
   */
  public $linkId;
  /**
   * @var string
   */
  public $parent;

  /**
   * @param Link
   */
  public function setLink(Link $link)
  {
    $this->link = $link;
  }
  /**
   * @return Link
   */
  public function getLink()
  {
    return $this->link;
  }
  /**
   * @param string
   */
  public function setLinkId($linkId)
  {
    $this->linkId = $linkId;
  }
  /**
   * @return string
   */
  public function getLinkId()
  {
    return $this->linkId;
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
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(CreateLinkRequest::class, 'Google_Service_Logging_CreateLinkRequest');
