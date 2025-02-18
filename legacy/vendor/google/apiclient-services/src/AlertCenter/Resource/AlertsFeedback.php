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

namespace Google\Service\AlertCenter\Resource;

use Google\Service\AlertCenter\AlertFeedback;
use Google\Service\AlertCenter\ListAlertFeedbackResponse;

/**
 * The "feedback" collection of methods.
 * Typical usage is:
 *  <code>
 *   $alertcenterService = new Google\Service\AlertCenter(...);
 *   $feedback = $alertcenterService->alerts_feedback;
 *  </code>
 */
class AlertsFeedback extends \Google\Service\Resource
{
  /**
   * Creates new feedback for an alert. Attempting to create a feedback for a non-
   * existent alert returns `NOT_FOUND` error. Attempting to create a feedback for
   * an alert that is marked for deletion returns `FAILED_PRECONDITION' error.
   * (feedback.create)
   *
   * @param string $alertId Required. The identifier of the alert this feedback
   * belongs to.
   * @param AlertFeedback $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string customerId Optional. The unique identifier of the Google
   * Workspace account of the customer the alert is associated with. The
   * `customer_id` must have the initial "C" stripped (for example, `046psxkn`).
   * Inferred from the caller identity if not provided. [Find your customer
   * ID](https://support.google.com/cloudidentity/answer/10070793).
   * @return AlertFeedback
   * @throws \Google\Service\Exception
   */
  public function create($alertId, AlertFeedback $postBody, $optParams = [])
  {
    $params = ['alertId' => $alertId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], AlertFeedback::class);
  }
  /**
   * Lists all the feedback for an alert. Attempting to list feedbacks for a non-
   * existent alert returns `NOT_FOUND` error. (feedback.listAlertsFeedback)
   *
   * @param string $alertId Required. The alert identifier. The "-" wildcard could
   * be used to represent all alerts.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string customerId Optional. The unique identifier of the Google
   * Workspace account of the customer the alert is associated with. The
   * `customer_id` must have the initial "C" stripped (for example, `046psxkn`).
   * Inferred from the caller identity if not provided. [Find your customer
   * ID](https://support.google.com/cloudidentity/answer/10070793).
   * @opt_param string filter Optional. A query string for filtering alert
   * feedback results. For more details, see [Query
   * filters](https://developers.google.com/admin-sdk/alertcenter/guides/query-
   * filters) and [Supported query filter
   * fields](https://developers.google.com/admin-sdk/alertcenter/reference/filter-
   * fields#alerts.feedback.list).
   * @return ListAlertFeedbackResponse
   * @throws \Google\Service\Exception
   */
  public function listAlertsFeedback($alertId, $optParams = [])
  {
    $params = ['alertId' => $alertId];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListAlertFeedbackResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(AlertsFeedback::class, 'Google_Service_AlertCenter_Resource_AlertsFeedback');
