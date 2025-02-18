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

namespace Google\Service\DLP;

class GooglePrivacyDlpV2InspectConfig extends \Google\Collection
{
  protected $collection_key = 'ruleSet';
  /**
   * @var string[]
   */
  public $contentOptions;
  protected $customInfoTypesType = GooglePrivacyDlpV2CustomInfoType::class;
  protected $customInfoTypesDataType = 'array';
  /**
   * @var bool
   */
  public $excludeInfoTypes;
  /**
   * @var bool
   */
  public $includeQuote;
  protected $infoTypesType = GooglePrivacyDlpV2InfoType::class;
  protected $infoTypesDataType = 'array';
  protected $limitsType = GooglePrivacyDlpV2FindingLimits::class;
  protected $limitsDataType = '';
  /**
   * @var string
   */
  public $minLikelihood;
  protected $minLikelihoodPerInfoTypeType = GooglePrivacyDlpV2InfoTypeLikelihood::class;
  protected $minLikelihoodPerInfoTypeDataType = 'array';
  protected $ruleSetType = GooglePrivacyDlpV2InspectionRuleSet::class;
  protected $ruleSetDataType = 'array';

  /**
   * @param string[]
   */
  public function setContentOptions($contentOptions)
  {
    $this->contentOptions = $contentOptions;
  }
  /**
   * @return string[]
   */
  public function getContentOptions()
  {
    return $this->contentOptions;
  }
  /**
   * @param GooglePrivacyDlpV2CustomInfoType[]
   */
  public function setCustomInfoTypes($customInfoTypes)
  {
    $this->customInfoTypes = $customInfoTypes;
  }
  /**
   * @return GooglePrivacyDlpV2CustomInfoType[]
   */
  public function getCustomInfoTypes()
  {
    return $this->customInfoTypes;
  }
  /**
   * @param bool
   */
  public function setExcludeInfoTypes($excludeInfoTypes)
  {
    $this->excludeInfoTypes = $excludeInfoTypes;
  }
  /**
   * @return bool
   */
  public function getExcludeInfoTypes()
  {
    return $this->excludeInfoTypes;
  }
  /**
   * @param bool
   */
  public function setIncludeQuote($includeQuote)
  {
    $this->includeQuote = $includeQuote;
  }
  /**
   * @return bool
   */
  public function getIncludeQuote()
  {
    return $this->includeQuote;
  }
  /**
   * @param GooglePrivacyDlpV2InfoType[]
   */
  public function setInfoTypes($infoTypes)
  {
    $this->infoTypes = $infoTypes;
  }
  /**
   * @return GooglePrivacyDlpV2InfoType[]
   */
  public function getInfoTypes()
  {
    return $this->infoTypes;
  }
  /**
   * @param GooglePrivacyDlpV2FindingLimits
   */
  public function setLimits(GooglePrivacyDlpV2FindingLimits $limits)
  {
    $this->limits = $limits;
  }
  /**
   * @return GooglePrivacyDlpV2FindingLimits
   */
  public function getLimits()
  {
    return $this->limits;
  }
  /**
   * @param string
   */
  public function setMinLikelihood($minLikelihood)
  {
    $this->minLikelihood = $minLikelihood;
  }
  /**
   * @return string
   */
  public function getMinLikelihood()
  {
    return $this->minLikelihood;
  }
  /**
   * @param GooglePrivacyDlpV2InfoTypeLikelihood[]
   */
  public function setMinLikelihoodPerInfoType($minLikelihoodPerInfoType)
  {
    $this->minLikelihoodPerInfoType = $minLikelihoodPerInfoType;
  }
  /**
   * @return GooglePrivacyDlpV2InfoTypeLikelihood[]
   */
  public function getMinLikelihoodPerInfoType()
  {
    return $this->minLikelihoodPerInfoType;
  }
  /**
   * @param GooglePrivacyDlpV2InspectionRuleSet[]
   */
  public function setRuleSet($ruleSet)
  {
    $this->ruleSet = $ruleSet;
  }
  /**
   * @return GooglePrivacyDlpV2InspectionRuleSet[]
   */
  public function getRuleSet()
  {
    return $this->ruleSet;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GooglePrivacyDlpV2InspectConfig::class, 'Google_Service_DLP_GooglePrivacyDlpV2InspectConfig');
