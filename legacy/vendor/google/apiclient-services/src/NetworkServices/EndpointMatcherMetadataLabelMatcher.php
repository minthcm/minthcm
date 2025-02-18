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

namespace Google\Service\NetworkServices;

class EndpointMatcherMetadataLabelMatcher extends \Google\Collection
{
  protected $collection_key = 'metadataLabels';
  /**
   * @var string
   */
  public $metadataLabelMatchCriteria;
  protected $metadataLabelsType = EndpointMatcherMetadataLabelMatcherMetadataLabels::class;
  protected $metadataLabelsDataType = 'array';

  /**
   * @param string
   */
  public function setMetadataLabelMatchCriteria($metadataLabelMatchCriteria)
  {
    $this->metadataLabelMatchCriteria = $metadataLabelMatchCriteria;
  }
  /**
   * @return string
   */
  public function getMetadataLabelMatchCriteria()
  {
    return $this->metadataLabelMatchCriteria;
  }
  /**
   * @param EndpointMatcherMetadataLabelMatcherMetadataLabels[]
   */
  public function setMetadataLabels($metadataLabels)
  {
    $this->metadataLabels = $metadataLabels;
  }
  /**
   * @return EndpointMatcherMetadataLabelMatcherMetadataLabels[]
   */
  public function getMetadataLabels()
  {
    return $this->metadataLabels;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(EndpointMatcherMetadataLabelMatcher::class, 'Google_Service_NetworkServices_EndpointMatcherMetadataLabelMatcher');
