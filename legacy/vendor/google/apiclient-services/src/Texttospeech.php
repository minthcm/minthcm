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

namespace Google\Service;

use Google\Client;

/**
 * Service definition for Texttospeech (v1).
 *
 * <p>
 * Synthesizes natural-sounding speech by applying powerful neural network
 * models.</p>
 *
 * <p>
 * For more information about this service, see the API
 * <a href="https://cloud.google.com/text-to-speech/" target="_blank">Documentation</a>
 * </p>
 *
 * @author Google, Inc.
 */
class Texttospeech extends \Google\Service
{
  /** See, edit, configure, and delete your Google Cloud data and see the email address for your Google Account.. */
  const CLOUD_PLATFORM =
      "https://www.googleapis.com/auth/cloud-platform";

  public $operations;
  public $projects_locations;
  public $projects_locations_operations;
  public $text;
  public $voices;
  public $rootUrlTemplate;

  /**
   * Constructs the internal representation of the Texttospeech service.
   *
   * @param Client|array $clientOrConfig The client used to deliver requests, or a
   *                                     config array to pass to a new Client instance.
   * @param string $rootUrl The root URL used for requests to the service.
   */
  public function __construct($clientOrConfig = [], $rootUrl = null)
  {
    parent::__construct($clientOrConfig);
    $this->rootUrl = $rootUrl ?: 'https://texttospeech.googleapis.com/';
    $this->rootUrlTemplate = $rootUrl ?: 'https://texttospeech.UNIVERSE_DOMAIN/';
    $this->servicePath = '';
    $this->batchPath = 'batch';
    $this->version = 'v1';
    $this->serviceName = 'texttospeech';

    $this->operations = new Texttospeech\Resource\Operations(
        $this,
        $this->serviceName,
        'operations',
        [
          'methods' => [
            'cancel' => [
              'path' => 'v1/{+name}:cancel',
              'httpMethod' => 'POST',
              'parameters' => [
                'name' => [
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ],
              ],
            ],'delete' => [
              'path' => 'v1/{+name}',
              'httpMethod' => 'DELETE',
              'parameters' => [
                'name' => [
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ],
              ],
            ],
          ]
        ]
    );
    $this->projects_locations = new Texttospeech\Resource\ProjectsLocations(
        $this,
        $this->serviceName,
        'locations',
        [
          'methods' => [
            'synthesizeLongAudio' => [
              'path' => 'v1/{+parent}:synthesizeLongAudio',
              'httpMethod' => 'POST',
              'parameters' => [
                'parent' => [
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ],
              ],
            ],
          ]
        ]
    );
    $this->projects_locations_operations = new Texttospeech\Resource\ProjectsLocationsOperations(
        $this,
        $this->serviceName,
        'operations',
        [
          'methods' => [
            'get' => [
              'path' => 'v1/{+name}',
              'httpMethod' => 'GET',
              'parameters' => [
                'name' => [
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ],
              ],
            ],'list' => [
              'path' => 'v1/{+name}/operations',
              'httpMethod' => 'GET',
              'parameters' => [
                'name' => [
                  'location' => 'path',
                  'type' => 'string',
                  'required' => true,
                ],
                'filter' => [
                  'location' => 'query',
                  'type' => 'string',
                ],
                'pageSize' => [
                  'location' => 'query',
                  'type' => 'integer',
                ],
                'pageToken' => [
                  'location' => 'query',
                  'type' => 'string',
                ],
              ],
            ],
          ]
        ]
    );
    $this->text = new Texttospeech\Resource\Text(
        $this,
        $this->serviceName,
        'text',
        [
          'methods' => [
            'synthesize' => [
              'path' => 'v1/text:synthesize',
              'httpMethod' => 'POST',
              'parameters' => [],
            ],
          ]
        ]
    );
    $this->voices = new Texttospeech\Resource\Voices(
        $this,
        $this->serviceName,
        'voices',
        [
          'methods' => [
            'list' => [
              'path' => 'v1/voices',
              'httpMethod' => 'GET',
              'parameters' => [
                'languageCode' => [
                  'location' => 'query',
                  'type' => 'string',
                ],
              ],
            ],
          ]
        ]
    );
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Texttospeech::class, 'Google_Service_Texttospeech');
