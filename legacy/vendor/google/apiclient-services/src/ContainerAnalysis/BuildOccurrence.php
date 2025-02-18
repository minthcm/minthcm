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

class BuildOccurrence extends \Google\Model
{
  protected $inTotoSlsaProvenanceV1Type = InTotoSlsaProvenanceV1::class;
  protected $inTotoSlsaProvenanceV1DataType = '';
  protected $intotoProvenanceType = InTotoProvenance::class;
  protected $intotoProvenanceDataType = '';
  protected $intotoStatementType = InTotoStatement::class;
  protected $intotoStatementDataType = '';
  protected $provenanceType = BuildProvenance::class;
  protected $provenanceDataType = '';
  /**
   * @var string
   */
  public $provenanceBytes;

  /**
   * @param InTotoSlsaProvenanceV1
   */
  public function setInTotoSlsaProvenanceV1(InTotoSlsaProvenanceV1 $inTotoSlsaProvenanceV1)
  {
    $this->inTotoSlsaProvenanceV1 = $inTotoSlsaProvenanceV1;
  }
  /**
   * @return InTotoSlsaProvenanceV1
   */
  public function getInTotoSlsaProvenanceV1()
  {
    return $this->inTotoSlsaProvenanceV1;
  }
  /**
   * @param InTotoProvenance
   */
  public function setIntotoProvenance(InTotoProvenance $intotoProvenance)
  {
    $this->intotoProvenance = $intotoProvenance;
  }
  /**
   * @return InTotoProvenance
   */
  public function getIntotoProvenance()
  {
    return $this->intotoProvenance;
  }
  /**
   * @param InTotoStatement
   */
  public function setIntotoStatement(InTotoStatement $intotoStatement)
  {
    $this->intotoStatement = $intotoStatement;
  }
  /**
   * @return InTotoStatement
   */
  public function getIntotoStatement()
  {
    return $this->intotoStatement;
  }
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
  /**
   * @param string
   */
  public function setProvenanceBytes($provenanceBytes)
  {
    $this->provenanceBytes = $provenanceBytes;
  }
  /**
   * @return string
   */
  public function getProvenanceBytes()
  {
    return $this->provenanceBytes;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(BuildOccurrence::class, 'Google_Service_ContainerAnalysis_BuildOccurrence');
