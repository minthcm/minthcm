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

namespace Google\Service\ArtifactRegistry;

class ImportGoogetArtifactsErrorInfo extends \Google\Model
{
  protected $errorType = Status::class;
  protected $errorDataType = '';
  protected $gcsSourceType = ImportGoogetArtifactsGcsSource::class;
  protected $gcsSourceDataType = '';

  /**
   * @param Status
   */
  public function setError(Status $error)
  {
    $this->error = $error;
  }
  /**
   * @return Status
   */
  public function getError()
  {
    return $this->error;
  }
  /**
   * @param ImportGoogetArtifactsGcsSource
   */
  public function setGcsSource(ImportGoogetArtifactsGcsSource $gcsSource)
  {
    $this->gcsSource = $gcsSource;
  }
  /**
   * @return ImportGoogetArtifactsGcsSource
   */
  public function getGcsSource()
  {
    return $this->gcsSource;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ImportGoogetArtifactsErrorInfo::class, 'Google_Service_ArtifactRegistry_ImportGoogetArtifactsErrorInfo');
