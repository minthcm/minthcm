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

namespace Google\Service\Speech;

class SpeechAdaptation extends \Google\Collection
{
  protected $collection_key = 'phraseSets';
  protected $abnfGrammarType = ABNFGrammar::class;
  protected $abnfGrammarDataType = '';
  protected $customClassesType = CustomClass::class;
  protected $customClassesDataType = 'array';
  /**
   * @var string[]
   */
  public $phraseSetReferences;
  protected $phraseSetsType = PhraseSet::class;
  protected $phraseSetsDataType = 'array';

  /**
   * @param ABNFGrammar
   */
  public function setAbnfGrammar(ABNFGrammar $abnfGrammar)
  {
    $this->abnfGrammar = $abnfGrammar;
  }
  /**
   * @return ABNFGrammar
   */
  public function getAbnfGrammar()
  {
    return $this->abnfGrammar;
  }
  /**
   * @param CustomClass[]
   */
  public function setCustomClasses($customClasses)
  {
    $this->customClasses = $customClasses;
  }
  /**
   * @return CustomClass[]
   */
  public function getCustomClasses()
  {
    return $this->customClasses;
  }
  /**
   * @param string[]
   */
  public function setPhraseSetReferences($phraseSetReferences)
  {
    $this->phraseSetReferences = $phraseSetReferences;
  }
  /**
   * @return string[]
   */
  public function getPhraseSetReferences()
  {
    return $this->phraseSetReferences;
  }
  /**
   * @param PhraseSet[]
   */
  public function setPhraseSets($phraseSets)
  {
    $this->phraseSets = $phraseSets;
  }
  /**
   * @return PhraseSet[]
   */
  public function getPhraseSets()
  {
    return $this->phraseSets;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SpeechAdaptation::class, 'Google_Service_Speech_SpeechAdaptation');
