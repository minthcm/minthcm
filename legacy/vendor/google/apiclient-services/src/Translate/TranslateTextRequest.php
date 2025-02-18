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

namespace Google\Service\Translate;

class TranslateTextRequest extends \Google\Collection
{
  protected $collection_key = 'contents';
  /**
   * @var string[]
   */
  public $contents;
  protected $glossaryConfigType = TranslateTextGlossaryConfig::class;
  protected $glossaryConfigDataType = '';
  /**
   * @var string[]
   */
  public $labels;
  /**
   * @var string
   */
  public $mimeType;
  /**
   * @var string
   */
  public $model;
  /**
   * @var string
   */
  public $sourceLanguageCode;
  /**
   * @var string
   */
  public $targetLanguageCode;
  protected $transliterationConfigType = TransliterationConfig::class;
  protected $transliterationConfigDataType = '';

  /**
   * @param string[]
   */
  public function setContents($contents)
  {
    $this->contents = $contents;
  }
  /**
   * @return string[]
   */
  public function getContents()
  {
    return $this->contents;
  }
  /**
   * @param TranslateTextGlossaryConfig
   */
  public function setGlossaryConfig(TranslateTextGlossaryConfig $glossaryConfig)
  {
    $this->glossaryConfig = $glossaryConfig;
  }
  /**
   * @return TranslateTextGlossaryConfig
   */
  public function getGlossaryConfig()
  {
    return $this->glossaryConfig;
  }
  /**
   * @param string[]
   */
  public function setLabels($labels)
  {
    $this->labels = $labels;
  }
  /**
   * @return string[]
   */
  public function getLabels()
  {
    return $this->labels;
  }
  /**
   * @param string
   */
  public function setMimeType($mimeType)
  {
    $this->mimeType = $mimeType;
  }
  /**
   * @return string
   */
  public function getMimeType()
  {
    return $this->mimeType;
  }
  /**
   * @param string
   */
  public function setModel($model)
  {
    $this->model = $model;
  }
  /**
   * @return string
   */
  public function getModel()
  {
    return $this->model;
  }
  /**
   * @param string
   */
  public function setSourceLanguageCode($sourceLanguageCode)
  {
    $this->sourceLanguageCode = $sourceLanguageCode;
  }
  /**
   * @return string
   */
  public function getSourceLanguageCode()
  {
    return $this->sourceLanguageCode;
  }
  /**
   * @param string
   */
  public function setTargetLanguageCode($targetLanguageCode)
  {
    $this->targetLanguageCode = $targetLanguageCode;
  }
  /**
   * @return string
   */
  public function getTargetLanguageCode()
  {
    return $this->targetLanguageCode;
  }
  /**
   * @param TransliterationConfig
   */
  public function setTransliterationConfig(TransliterationConfig $transliterationConfig)
  {
    $this->transliterationConfig = $transliterationConfig;
  }
  /**
   * @return TransliterationConfig
   */
  public function getTransliterationConfig()
  {
    return $this->transliterationConfig;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(TranslateTextRequest::class, 'Google_Service_Translate_TranslateTextRequest');
