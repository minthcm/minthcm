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

namespace Google\Service\ContainerAnalysis;

class Remediation extends \Google\Model
{
  /**
   * @var string
   */
  public $details;
  /**
   * @var string
   */
  public $remediationType;
  protected $remediationUriType = RelatedUrl::class;
  protected $remediationUriDataType = '';

  /**
   * @param string
   */
  public function setDetails($details)
  {
    $this->details = $details;
  }
  /**
   * @return string
   */
  public function getDetails()
  {
    return $this->details;
  }
  /**
   * @param string
   */
  public function setRemediationType($remediationType)
  {
    $this->remediationType = $remediationType;
  }
  /**
   * @return string
   */
  public function getRemediationType()
  {
    return $this->remediationType;
  }
  /**
   * @param RelatedUrl
   */
  public function setRemediationUri(RelatedUrl $remediationUri)
  {
    $this->remediationUri = $remediationUri;
  }
  /**
   * @return RelatedUrl
   */
  public function getRemediationUri()
  {
    return $this->remediationUri;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Remediation::class, 'Google_Service_ContainerAnalysis_Remediation');
