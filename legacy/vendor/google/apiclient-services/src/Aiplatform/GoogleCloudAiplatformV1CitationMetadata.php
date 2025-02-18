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

namespace Google\Service\Aiplatform;

class GoogleCloudAiplatformV1CitationMetadata extends \Google\Collection
{
  protected $collection_key = 'citations';
  protected $citationsType = GoogleCloudAiplatformV1Citation::class;
  protected $citationsDataType = 'array';

  /**
   * @param GoogleCloudAiplatformV1Citation[]
   */
  public function setCitations($citations)
  {
    $this->citations = $citations;
  }
  /**
   * @return GoogleCloudAiplatformV1Citation[]
   */
  public function getCitations()
  {
    return $this->citations;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAiplatformV1CitationMetadata::class, 'Google_Service_Aiplatform_GoogleCloudAiplatformV1CitationMetadata');
