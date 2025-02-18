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

namespace Google\Service\Advisorynotifications;

class GoogleCloudAdvisorynotificationsV1Subject extends \Google\Model
{
  protected $textType = GoogleCloudAdvisorynotificationsV1Text::class;
  protected $textDataType = '';

  /**
   * @param GoogleCloudAdvisorynotificationsV1Text
   */
  public function setText(GoogleCloudAdvisorynotificationsV1Text $text)
  {
    $this->text = $text;
  }
  /**
   * @return GoogleCloudAdvisorynotificationsV1Text
   */
  public function getText()
  {
    return $this->text;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudAdvisorynotificationsV1Subject::class, 'Google_Service_Advisorynotifications_GoogleCloudAdvisorynotificationsV1Subject');
