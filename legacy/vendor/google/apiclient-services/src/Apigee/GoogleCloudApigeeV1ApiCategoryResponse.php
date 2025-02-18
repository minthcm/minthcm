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

namespace Google\Service\Apigee;

class GoogleCloudApigeeV1ApiCategoryResponse extends \Google\Model
{
  protected $dataType = GoogleCloudApigeeV1ApiCategory::class;
  protected $dataDataType = '';
  /**
   * @var string
   */
  public $errorCode;
  /**
   * @var string
   */
  public $message;
  /**
   * @var string
   */
  public $requestId;
  /**
   * @var string
   */
  public $status;

  /**
   * @param GoogleCloudApigeeV1ApiCategory
   */
  public function setData(GoogleCloudApigeeV1ApiCategory $data)
  {
    $this->data = $data;
  }
  /**
   * @return GoogleCloudApigeeV1ApiCategory
   */
  public function getData()
  {
    return $this->data;
  }
  /**
   * @param string
   */
  public function setErrorCode($errorCode)
  {
    $this->errorCode = $errorCode;
  }
  /**
   * @return string
   */
  public function getErrorCode()
  {
    return $this->errorCode;
  }
  /**
   * @param string
   */
  public function setMessage($message)
  {
    $this->message = $message;
  }
  /**
   * @return string
   */
  public function getMessage()
  {
    return $this->message;
  }
  /**
   * @param string
   */
  public function setRequestId($requestId)
  {
    $this->requestId = $requestId;
  }
  /**
   * @return string
   */
  public function getRequestId()
  {
    return $this->requestId;
  }
  /**
   * @param string
   */
  public function setStatus($status)
  {
    $this->status = $status;
  }
  /**
   * @return string
   */
  public function getStatus()
  {
    return $this->status;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudApigeeV1ApiCategoryResponse::class, 'Google_Service_Apigee_GoogleCloudApigeeV1ApiCategoryResponse');
