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

class SideInputInfo extends \Google\Collection
{
  protected $collection_key = 'sources';
  /**
   * @var array[]
   */
  public $kind;
  protected $sourcesType = Source::class;
  protected $sourcesDataType = 'array';
  /**
   * @var string
   */
  public $tag;

  /**
   * @param array[]
   */
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  /**
   * @return array[]
   */
  public function getKind()
  {
    return $this->kind;
  }
  /**
   * @param Source[]
   */
  public function setSources($sources)
  {
    $this->sources = $sources;
  }
  /**
   * @return Source[]
   */
  public function getSources()
  {
    return $this->sources;
  }
  /**
   * @param string
   */
  public function setTag($tag)
  {
    $this->tag = $tag;
  }
  /**
   * @return string
   */
  public function getTag()
  {
    return $this->tag;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SideInputInfo::class, 'Google_Service_Dataflow_SideInputInfo');
