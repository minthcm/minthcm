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

class GoogleCloudDocumentaiV1DocumentPageTable extends \Google\Collection
{
  protected $collection_key = 'headerRows';
  protected $bodyRowsType = GoogleCloudDocumentaiV1DocumentPageTableTableRow::class;
  protected $bodyRowsDataType = 'array';
  protected $detectedLanguagesType = GoogleCloudDocumentaiV1DocumentPageDetectedLanguage::class;
  protected $detectedLanguagesDataType = 'array';
  protected $headerRowsType = GoogleCloudDocumentaiV1DocumentPageTableTableRow::class;
  protected $headerRowsDataType = 'array';
  protected $layoutType = GoogleCloudDocumentaiV1DocumentPageLayout::class;
  protected $layoutDataType = '';
  protected $provenanceType = GoogleCloudDocumentaiV1DocumentProvenance::class;
  protected $provenanceDataType = '';

  /**
   * @param GoogleCloudDocumentaiV1DocumentPageTableTableRow[]
   */
  public function setBodyRows($bodyRows)
  {
    $this->bodyRows = $bodyRows;
  }
  /**
   * @return GoogleCloudDocumentaiV1DocumentPageTableTableRow[]
   */
  public function getBodyRows()
  {
    return $this->bodyRows;
  }
  /**
   * @param GoogleCloudDocumentaiV1DocumentPageDetectedLanguage[]
   */
  public function setDetectedLanguages($detectedLanguages)
  {
    $this->detectedLanguages = $detectedLanguages;
  }
  /**
   * @return GoogleCloudDocumentaiV1DocumentPageDetectedLanguage[]
   */
  public function getDetectedLanguages()
  {
    return $this->detectedLanguages;
  }
  /**
   * @param GoogleCloudDocumentaiV1DocumentPageTableTableRow[]
   */
  public function setHeaderRows($headerRows)
  {
    $this->headerRows = $headerRows;
  }
  /**
   * @return GoogleCloudDocumentaiV1DocumentPageTableTableRow[]
   */
  public function getHeaderRows()
  {
    return $this->headerRows;
  }
  /**
   * @param GoogleCloudDocumentaiV1DocumentPageLayout
   */
  public function setLayout(GoogleCloudDocumentaiV1DocumentPageLayout $layout)
  {
    $this->layout = $layout;
  }
  /**
   * @return GoogleCloudDocumentaiV1DocumentPageLayout
   */
  public function getLayout()
  {
    return $this->layout;
  }
  /**
   * @param GoogleCloudDocumentaiV1DocumentProvenance
   */
  public function setProvenance(GoogleCloudDocumentaiV1DocumentProvenance $provenance)
  {
    $this->provenance = $provenance;
  }
  /**
   * @return GoogleCloudDocumentaiV1DocumentProvenance
   */
  public function getProvenance()
  {
    return $this->provenance;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDocumentaiV1DocumentPageTable::class, 'Google_Service_Contentwarehouse_GoogleCloudDocumentaiV1DocumentPageTable');
