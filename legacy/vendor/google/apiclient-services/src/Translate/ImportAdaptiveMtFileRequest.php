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

namespace Google\Service\Translate;

class ImportAdaptiveMtFileRequest extends \Google\Model
{
  protected $fileInputSourceType = FileInputSource::class;
  protected $fileInputSourceDataType = '';
  protected $gcsInputSourceType = GcsInputSource::class;
  protected $gcsInputSourceDataType = '';

  /**
   * @param FileInputSource
   */
  public function setFileInputSource(FileInputSource $fileInputSource)
  {
    $this->fileInputSource = $fileInputSource;
  }
  /**
   * @return FileInputSource
   */
  public function getFileInputSource()
  {
    return $this->fileInputSource;
  }
  /**
   * @param GcsInputSource
   */
  public function setGcsInputSource(GcsInputSource $gcsInputSource)
  {
    $this->gcsInputSource = $gcsInputSource;
  }
  /**
   * @return GcsInputSource
   */
  public function getGcsInputSource()
  {
    return $this->gcsInputSource;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ImportAdaptiveMtFileRequest::class, 'Google_Service_Translate_ImportAdaptiveMtFileRequest');
