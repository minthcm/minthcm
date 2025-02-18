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

namespace Google\Service\Slides;

class SheetsChart extends \Google\Model
{
  /**
   * @var int
   */
  public $chartId;
  /**
   * @var string
   */
  public $contentUrl;
  protected $sheetsChartPropertiesType = SheetsChartProperties::class;
  protected $sheetsChartPropertiesDataType = '';
  /**
   * @var string
   */
  public $spreadsheetId;

  /**
   * @param int
   */
  public function setChartId($chartId)
  {
    $this->chartId = $chartId;
  }
  /**
   * @return int
   */
  public function getChartId()
  {
    return $this->chartId;
  }
  /**
   * @param string
   */
  public function setContentUrl($contentUrl)
  {
    $this->contentUrl = $contentUrl;
  }
  /**
   * @return string
   */
  public function getContentUrl()
  {
    return $this->contentUrl;
  }
  /**
   * @param SheetsChartProperties
   */
  public function setSheetsChartProperties(SheetsChartProperties $sheetsChartProperties)
  {
    $this->sheetsChartProperties = $sheetsChartProperties;
  }
  /**
   * @return SheetsChartProperties
   */
  public function getSheetsChartProperties()
  {
    return $this->sheetsChartProperties;
  }
  /**
   * @param string
   */
  public function setSpreadsheetId($spreadsheetId)
  {
    $this->spreadsheetId = $spreadsheetId;
  }
  /**
   * @return string
   */
  public function getSpreadsheetId()
  {
    return $this->spreadsheetId;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SheetsChart::class, 'Google_Service_Slides_SheetsChart');
