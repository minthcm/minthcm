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

namespace Google\Service\Document;

class GoogleCloudDocumentaiUiv1beta3ImportDocumentsMetadata extends \Google\Collection
{
  protected $collection_key = 'individualImportStatuses';
  protected $commonMetadataType = GoogleCloudDocumentaiUiv1beta3CommonOperationMetadata::class;
  protected $commonMetadataDataType = '';
  protected $importConfigValidationResultsType = GoogleCloudDocumentaiUiv1beta3ImportDocumentsMetadataImportConfigValidationResult::class;
  protected $importConfigValidationResultsDataType = 'array';
  protected $individualImportStatusesType = GoogleCloudDocumentaiUiv1beta3ImportDocumentsMetadataIndividualImportStatus::class;
  protected $individualImportStatusesDataType = 'array';
  /**
   * @var int
   */
  public $totalDocumentCount;

  /**
   * @param GoogleCloudDocumentaiUiv1beta3CommonOperationMetadata
   */
  public function setCommonMetadata(GoogleCloudDocumentaiUiv1beta3CommonOperationMetadata $commonMetadata)
  {
    $this->commonMetadata = $commonMetadata;
  }
  /**
   * @return GoogleCloudDocumentaiUiv1beta3CommonOperationMetadata
   */
  public function getCommonMetadata()
  {
    return $this->commonMetadata;
  }
  /**
   * @param GoogleCloudDocumentaiUiv1beta3ImportDocumentsMetadataImportConfigValidationResult[]
   */
  public function setImportConfigValidationResults($importConfigValidationResults)
  {
    $this->importConfigValidationResults = $importConfigValidationResults;
  }
  /**
   * @return GoogleCloudDocumentaiUiv1beta3ImportDocumentsMetadataImportConfigValidationResult[]
   */
  public function getImportConfigValidationResults()
  {
    return $this->importConfigValidationResults;
  }
  /**
   * @param GoogleCloudDocumentaiUiv1beta3ImportDocumentsMetadataIndividualImportStatus[]
   */
  public function setIndividualImportStatuses($individualImportStatuses)
  {
    $this->individualImportStatuses = $individualImportStatuses;
  }
  /**
   * @return GoogleCloudDocumentaiUiv1beta3ImportDocumentsMetadataIndividualImportStatus[]
   */
  public function getIndividualImportStatuses()
  {
    return $this->individualImportStatuses;
  }
  /**
   * @param int
   */
  public function setTotalDocumentCount($totalDocumentCount)
  {
    $this->totalDocumentCount = $totalDocumentCount;
  }
  /**
   * @return int
   */
  public function getTotalDocumentCount()
  {
    return $this->totalDocumentCount;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDocumentaiUiv1beta3ImportDocumentsMetadata::class, 'Google_Service_Document_GoogleCloudDocumentaiUiv1beta3ImportDocumentsMetadata');
