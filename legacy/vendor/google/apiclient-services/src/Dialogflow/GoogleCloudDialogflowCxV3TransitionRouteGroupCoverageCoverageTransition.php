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

class GoogleCloudDialogflowCxV3TransitionRouteGroupCoverageCoverageTransition extends \Google\Model
{
  /**
   * @var bool
   */
  public $covered;
  protected $transitionRouteType = GoogleCloudDialogflowCxV3TransitionRoute::class;
  protected $transitionRouteDataType = '';

  /**
   * @param bool
   */
  public function setCovered($covered)
  {
    $this->covered = $covered;
  }
  /**
   * @return bool
   */
  public function getCovered()
  {
    return $this->covered;
  }
  /**
   * @param GoogleCloudDialogflowCxV3TransitionRoute
   */
  public function setTransitionRoute(GoogleCloudDialogflowCxV3TransitionRoute $transitionRoute)
  {
    $this->transitionRoute = $transitionRoute;
  }
  /**
   * @return GoogleCloudDialogflowCxV3TransitionRoute
   */
  public function getTransitionRoute()
  {
    return $this->transitionRoute;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleCloudDialogflowCxV3TransitionRouteGroupCoverageCoverageTransition::class, 'Google_Service_Dialogflow_GoogleCloudDialogflowCxV3TransitionRouteGroupCoverageCoverageTransition');
