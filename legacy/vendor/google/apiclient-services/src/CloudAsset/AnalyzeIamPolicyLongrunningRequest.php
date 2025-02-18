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

namespace Google\Service\CloudAsset;

class AnalyzeIamPolicyLongrunningRequest extends \Google\Model
{
  protected $analysisQueryType = IamPolicyAnalysisQuery::class;
  protected $analysisQueryDataType = '';
  protected $outputConfigType = IamPolicyAnalysisOutputConfig::class;
  protected $outputConfigDataType = '';
  /**
   * @var string
   */
  public $savedAnalysisQuery;

  /**
   * @param IamPolicyAnalysisQuery
   */
  public function setAnalysisQuery(IamPolicyAnalysisQuery $analysisQuery)
  {
    $this->analysisQuery = $analysisQuery;
  }
  /**
   * @return IamPolicyAnalysisQuery
   */
  public function getAnalysisQuery()
  {
    return $this->analysisQuery;
  }
  /**
   * @param IamPolicyAnalysisOutputConfig
   */
  public function setOutputConfig(IamPolicyAnalysisOutputConfig $outputConfig)
  {
    $this->outputConfig = $outputConfig;
  }
  /**
   * @return IamPolicyAnalysisOutputConfig
   */
  public function getOutputConfig()
  {
    return $this->outputConfig;
  }
  /**
   * @param string
   */
  public function setSavedAnalysisQuery($savedAnalysisQuery)
  {
    $this->savedAnalysisQuery = $savedAnalysisQuery;
  }
  /**
   * @return string
   */
  public function getSavedAnalysisQuery()
  {
    return $this->savedAnalysisQuery;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AnalyzeIamPolicyLongrunningRequest::class, 'Google_Service_CloudAsset_AnalyzeIamPolicyLongrunningRequest');
