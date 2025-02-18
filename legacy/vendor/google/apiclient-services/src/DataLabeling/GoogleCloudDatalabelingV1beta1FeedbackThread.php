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

namespace Google\Service\DataLabeling;

class GoogleCloudDatalabelingV1beta1FeedbackThread extends \Google\Model
{
  protected $feedbackThreadMetadataType = GoogleCloudDatalabelingV1beta1FeedbackThreadMetadata::class;
  protected $feedbackThreadMetadataDataType = '';
  /**
   * @var string
   */
  public $name;

  /**
   * @param GoogleCloudDatalabelingV1beta1FeedbackThreadMetadata
   */
  public function setFeedbackThreadMetadata(GoogleCloudDatalabelingV1beta1FeedbackThreadMetadata $feedbackThreadMetadata)
  {
    $this->feedbackThreadMetadata = $feedbackThreadMetadata;
  }
  /**
   * @return GoogleCloudDatalabelingV1beta1FeedbackThreadMetadata
   */
  public function getFeedbackThreadMetadata()
  {
    return $this->feedbackThreadMetadata;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDatalabelingV1beta1FeedbackThread::class, 'Google_Service_DataLabeling_GoogleCloudDatalabelingV1beta1FeedbackThread');
