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

namespace Google\Service\Document;

class GoogleCloudDocumentaiUiv1beta3RevisionReference extends \Google\Model
{
  /**
   * @var string
   */
  public $latestProcessorVersion;
  /**
   * @var string
   */
  public $revisionCase;
  /**
   * @var string
   */
  public $revisionId;

  /**
   * @param string
   */
  public function setLatestProcessorVersion($latestProcessorVersion)
  {
    $this->latestProcessorVersion = $latestProcessorVersion;
  }
  /**
   * @return string
   */
  public function getLatestProcessorVersion()
  {
    return $this->latestProcessorVersion;
  }
  /**
   * @param string
   */
  public function setRevisionCase($revisionCase)
  {
    $this->revisionCase = $revisionCase;
  }
  /**
   * @return string
   */
  public function getRevisionCase()
  {
    return $this->revisionCase;
  }
  /**
   * @param string
   */
  public function setRevisionId($revisionId)
  {
    $this->revisionId = $revisionId;
  }
  /**
   * @return string
   */
  public function getRevisionId()
  {
    return $this->revisionId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDocumentaiUiv1beta3RevisionReference::class, 'Google_Service_Document_GoogleCloudDocumentaiUiv1beta3RevisionReference');
