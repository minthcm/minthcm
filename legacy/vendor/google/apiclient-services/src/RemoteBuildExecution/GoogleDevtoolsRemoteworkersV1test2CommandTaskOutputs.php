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

namespace Google\Service\RemoteBuildExecution;

class GoogleDevtoolsRemoteworkersV1test2CommandTaskOutputs extends \Google\Collection
{
  protected $collection_key = 'files';
  public $directories;
  public $files;
  public $stderrDestination;
  public $stdoutDestination;

  public function setDirectories($directories)
  {
    $this->directories = $directories;
  }
  public function getDirectories()
  {
    return $this->directories;
  }
  public function setFiles($files)
  {
    $this->files = $files;
  }
  public function getFiles()
  {
    return $this->files;
  }
  public function setStderrDestination($stderrDestination)
  {
    $this->stderrDestination = $stderrDestination;
  }
  public function getStderrDestination()
  {
    return $this->stderrDestination;
  }
  public function setStdoutDestination($stdoutDestination)
  {
    $this->stdoutDestination = $stdoutDestination;
  }
  public function getStdoutDestination()
  {
    return $this->stdoutDestination;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleDevtoolsRemoteworkersV1test2CommandTaskOutputs::class, 'Google_Service_RemoteBuildExecution_GoogleDevtoolsRemoteworkersV1test2CommandTaskOutputs');
