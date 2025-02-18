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

class GrafeasV1beta1BuildDetails extends \Google\Model
{
  protected $provenanceType = BuildProvenance::class;
  protected $provenanceDataType = '';
  public $provenanceBytes;

  /**
   * @param BuildProvenance
   */
  public function setProvenance(BuildProvenance $provenance)
  {
    $this->provenance = $provenance;
  }
  /**
   * @return BuildProvenance
   */
  public function getProvenance()
  {
    return $this->provenance;
  }
  public function setProvenanceBytes($provenanceBytes)
  {
    $this->provenanceBytes = $provenanceBytes;
  }
  public function getProvenanceBytes()
  {
    return $this->provenanceBytes;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GrafeasV1beta1BuildDetails::class, 'Google_Service_ContainerAnalysis_GrafeasV1beta1BuildDetails');
