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

namespace Google\Service\Contentwarehouse;

class GoogleCloudContentwarehouseV1SearchDocumentsResponseMatchingDocument extends \Google\Collection
{
  protected $collection_key = 'matchedTokenPageIndices';
  protected $documentType = GoogleCloudContentwarehouseV1Document::class;
  protected $documentDataType = '';
  /**
   * @var string[]
   */
  public $matchedTokenPageIndices;
  protected $qaResultType = GoogleCloudContentwarehouseV1QAResult::class;
  protected $qaResultDataType = '';
  /**
   * @var string
   */
  public $searchTextSnippet;

  /**
   * @param GoogleCloudContentwarehouseV1Document
   */
  public function setDocument(GoogleCloudContentwarehouseV1Document $document)
  {
    $this->document = $document;
  }
  /**
   * @return GoogleCloudContentwarehouseV1Document
   */
  public function getDocument()
  {
    return $this->document;
  }
  /**
   * @param string[]
   */
  public function setMatchedTokenPageIndices($matchedTokenPageIndices)
  {
    $this->matchedTokenPageIndices = $matchedTokenPageIndices;
  }
  /**
   * @return string[]
   */
  public function getMatchedTokenPageIndices()
  {
    return $this->matchedTokenPageIndices;
  }
  /**
   * @param GoogleCloudContentwarehouseV1QAResult
   */
  public function setQaResult(GoogleCloudContentwarehouseV1QAResult $qaResult)
  {
    $this->qaResult = $qaResult;
  }
  /**
   * @return GoogleCloudContentwarehouseV1QAResult
   */
  public function getQaResult()
  {
    return $this->qaResult;
  }
  /**
   * @param string
   */
  public function setSearchTextSnippet($searchTextSnippet)
  {
    $this->searchTextSnippet = $searchTextSnippet;
  }
  /**
   * @return string
   */
  public function getSearchTextSnippet()
  {
    return $this->searchTextSnippet;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudContentwarehouseV1SearchDocumentsResponseMatchingDocument::class, 'Google_Service_Contentwarehouse_GoogleCloudContentwarehouseV1SearchDocumentsResponseMatchingDocument');
