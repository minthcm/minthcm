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

class DictlayerdataDictWordsSensesDefinitionsExamples extends \Google\Model
{
  protected $sourceType = DictlayerdataDictWordsSensesDefinitionsExamplesSource::class;
  protected $sourceDataType = '';
  /**
   * @var string
   */
  public $text;

  /**
   * @param DictlayerdataDictWordsSensesDefinitionsExamplesSource
   */
  public function setSource(DictlayerdataDictWordsSensesDefinitionsExamplesSource $source)
  {
    $this->source = $source;
  }
  /**
   * @return DictlayerdataDictWordsSensesDefinitionsExamplesSource
   */
  public function getSource()
  {
    return $this->source;
  }
  /**
   * @param string
   */
  public function setText($text)
  {
    $this->text = $text;
  }
  /**
   * @return string
   */
  public function getText()
  {
    return $this->text;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(DictlayerdataDictWordsSensesDefinitionsExamples::class, 'Google_Service_Books_DictlayerdataDictWordsSensesDefinitionsExamples');
