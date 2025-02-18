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

namespace Google\Service\Books;

class DictlayerdataDict extends \Google\Collection
{
  protected $collection_key = 'words';
  protected $sourceType = DictlayerdataDictSource::class;
  protected $sourceDataType = '';
  protected $wordsType = DictlayerdataDictWords::class;
  protected $wordsDataType = 'array';

  /**
   * @param DictlayerdataDictSource
   */
  public function setSource(DictlayerdataDictSource $source)
  {
    $this->source = $source;
  }
  /**
   * @return DictlayerdataDictSource
   */
  public function getSource()
  {
    return $this->source;
  }
  /**
   * @param DictlayerdataDictWords[]
   */
  public function setWords($words)
  {
    $this->words = $words;
  }
  /**
   * @return DictlayerdataDictWords[]
   */
  public function getWords()
  {
    return $this->words;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DictlayerdataDict::class, 'Google_Service_Books_DictlayerdataDict');
