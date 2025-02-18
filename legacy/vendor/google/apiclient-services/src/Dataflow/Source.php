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

namespace Google\Service\Dataflow;

class Source extends \Google\Collection
{
  protected $collection_key = 'baseSpecs';
  /**
   * @var array[]
   */
  public $baseSpecs;
  /**
   * @var array[]
   */
  public $codec;
  /**
   * @var bool
   */
  public $doesNotNeedSplitting;
  protected $metadataType = SourceMetadata::class;
  protected $metadataDataType = '';
  /**
   * @var array[]
   */
  public $spec;

  /**
   * @param array[]
   */
  public function setBaseSpecs($baseSpecs)
  {
    $this->baseSpecs = $baseSpecs;
  }
  /**
   * @return array[]
   */
  public function getBaseSpecs()
  {
    return $this->baseSpecs;
  }
  /**
   * @param array[]
   */
  public function setCodec($codec)
  {
    $this->codec = $codec;
  }
  /**
   * @return array[]
   */
  public function getCodec()
  {
    return $this->codec;
  }
  /**
   * @param bool
   */
  public function setDoesNotNeedSplitting($doesNotNeedSplitting)
  {
    $this->doesNotNeedSplitting = $doesNotNeedSplitting;
  }
  /**
   * @return bool
   */
  public function getDoesNotNeedSplitting()
  {
    return $this->doesNotNeedSplitting;
  }
  /**
   * @param SourceMetadata
   */
  public function setMetadata(SourceMetadata $metadata)
  {
    $this->metadata = $metadata;
  }
  /**
   * @return SourceMetadata
   */
  public function getMetadata()
  {
    return $this->metadata;
  }
  /**
   * @param array[]
   */
  public function setSpec($spec)
  {
    $this->spec = $spec;
  }
  /**
   * @return array[]
   */
  public function getSpec()
  {
    return $this->spec;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Source::class, 'Google_Service_Dataflow_Source');
