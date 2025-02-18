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

namespace Google\Service\Dialogflow;

class GoogleCloudDialogflowV2IntentMessageTableCard extends \Google\Collection
{
  protected $collection_key = 'rows';
  protected $buttonsType = GoogleCloudDialogflowV2IntentMessageBasicCardButton::class;
  protected $buttonsDataType = 'array';
  protected $columnPropertiesType = GoogleCloudDialogflowV2IntentMessageColumnProperties::class;
  protected $columnPropertiesDataType = 'array';
  protected $imageType = GoogleCloudDialogflowV2IntentMessageImage::class;
  protected $imageDataType = '';
  protected $rowsType = GoogleCloudDialogflowV2IntentMessageTableCardRow::class;
  protected $rowsDataType = 'array';
  /**
   * @var string
   */
  public $subtitle;
  /**
   * @var string
   */
  public $title;

  /**
   * @param GoogleCloudDialogflowV2IntentMessageBasicCardButton[]
   */
  public function setButtons($buttons)
  {
    $this->buttons = $buttons;
  }
  /**
   * @return GoogleCloudDialogflowV2IntentMessageBasicCardButton[]
   */
  public function getButtons()
  {
    return $this->buttons;
  }
  /**
   * @param GoogleCloudDialogflowV2IntentMessageColumnProperties[]
   */
  public function setColumnProperties($columnProperties)
  {
    $this->columnProperties = $columnProperties;
  }
  /**
   * @return GoogleCloudDialogflowV2IntentMessageColumnProperties[]
   */
  public function getColumnProperties()
  {
    return $this->columnProperties;
  }
  /**
   * @param GoogleCloudDialogflowV2IntentMessageImage
   */
  public function setImage(GoogleCloudDialogflowV2IntentMessageImage $image)
  {
    $this->image = $image;
  }
  /**
   * @return GoogleCloudDialogflowV2IntentMessageImage
   */
  public function getImage()
  {
    return $this->image;
  }
  /**
   * @param GoogleCloudDialogflowV2IntentMessageTableCardRow[]
   */
  public function setRows($rows)
  {
    $this->rows = $rows;
  }
  /**
   * @return GoogleCloudDialogflowV2IntentMessageTableCardRow[]
   */
  public function getRows()
  {
    return $this->rows;
  }
  /**
   * @param string
   */
  public function setSubtitle($subtitle)
  {
    $this->subtitle = $subtitle;
  }
  /**
   * @return string
   */
  public function getSubtitle()
  {
    return $this->subtitle;
  }
  /**
   * @param string
   */
  public function setTitle($title)
  {
    $this->title = $title;
  }
  /**
   * @return string
   */
  public function getTitle()
  {
    return $this->title;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDialogflowV2IntentMessageTableCard::class, 'Google_Service_Dialogflow_GoogleCloudDialogflowV2IntentMessageTableCard');
