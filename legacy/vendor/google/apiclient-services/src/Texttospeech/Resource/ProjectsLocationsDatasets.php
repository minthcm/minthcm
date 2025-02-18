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

namespace Google\Service\Texttospeech\Resource;

use Google\Service\Texttospeech\ImportDataRequest;
use Google\Service\Texttospeech\Operation;

/**
 * The "datasets" collection of methods.
 * Typical usage is:
 *  <code>
 *   $texttospeechService = new Google\Service\Texttospeech(...);
 *   $datasets = $texttospeechService->datasets;
 *  </code>
 */
class ProjectsLocationsDatasets extends \Google\Service\Resource
{
  /**
   * Imports audio+text data for training custom voice. (datasets.import)
   *
   * @param string $name The name of the Dataset resource. Format:
   * `projects/{project}/locations/{location}/datasets/{dataset}`
   * @param ImportDataRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   */
  public function import($name, ImportDataRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('import', [$params], Operation::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsDatasets::class, 'Google_Service_Texttospeech_Resource_ProjectsLocationsDatasets');
