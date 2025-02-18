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

namespace Google\Service\FirebaseRules\Resource;

use Google\Service\FirebaseRules\TestRulesetRequest;
use Google\Service\FirebaseRules\TestRulesetResponse;

/**
 * The "projects" collection of methods.
 * Typical usage is:
 *  <code>
 *   $firebaserulesService = new Google\Service\FirebaseRules(...);
 *   $projects = $firebaserulesService->projects;
 *  </code>
 */
class Projects extends \Google\Service\Resource
{
  /**
   * Test `Source` for syntactic and semantic correctness. Issues present, if any,
   * will be returned to the caller with a description, severity, and source
   * location. The test method may be executed with `Source` or a `Ruleset` name.
   * Passing `Source` is useful for unit testing new rules. Passing a `Ruleset`
   * name is useful for regression testing an existing rule. The following is an
   * example of `Source` that permits users to upload images to a bucket bearing
   * their user id and matching the correct metadata: _*Example*_ // Users are
   * allowed to subscribe and unsubscribe to the blog. service firebase.storage {
   * match /users/{userId}/images/{imageName} { allow write: if userId ==
   * request.auth.uid && (imageName.matches('*.png$') ||
   * imageName.matches('*.jpg$')) && resource.mimeType.matches('^image/') } }
   * (projects.test)
   *
   * @param string $name Required. Tests may either provide `source` or a
   * `Ruleset` resource name. For tests against `source`, the resource name must
   * refer to the project: Format: `projects/{project_id}` For tests against a
   * `Ruleset`, this must be the `Ruleset` resource name: Format:
   * `projects/{project_id}/rulesets/{ruleset_id}`
   * @param TestRulesetRequest $postBody
   * @param array $optParams Optional parameters.
   * @return TestRulesetResponse
   * @throws \Google\Service\Exception
   */
  public function test($name, TestRulesetRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('test', [$params], TestRulesetResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Projects::class, 'Google_Service_FirebaseRules_Resource_Projects');
