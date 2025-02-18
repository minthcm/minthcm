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

namespace Google\Service\StreetViewPublish;

class UpdatePhotoRequest extends \Google\Model
{
  protected $photoType = Photo::class;
  protected $photoDataType = '';
  /**
   * @var string
   */
  public $updateMask;

  /**
   * @param Photo
   */
  public function setPhoto(Photo $photo)
  {
    $this->photo = $photo;
  }
  /**
   * @return Photo
   */
  public function getPhoto()
  {
    return $this->photo;
  }
  /**
   * @param string
   */
  public function setUpdateMask($updateMask)
  {
    $this->updateMask = $updateMask;
  }
  /**
   * @return string
   */
  public function getUpdateMask()
  {
    return $this->updateMask;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(UpdatePhotoRequest::class, 'Google_Service_StreetViewPublish_UpdatePhotoRequest');
