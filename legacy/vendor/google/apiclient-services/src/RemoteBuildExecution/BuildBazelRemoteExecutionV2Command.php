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

class BuildBazelRemoteExecutionV2Command extends \Google\Collection
{
  protected $collection_key = 'outputPaths';
  public $arguments;
  protected $environmentVariablesType = BuildBazelRemoteExecutionV2CommandEnvironmentVariable::class;
  protected $environmentVariablesDataType = 'array';
  public $outputDirectories;
  public $outputFiles;
  public $outputNodeProperties;
  public $outputPaths;
  protected $platformType = BuildBazelRemoteExecutionV2Platform::class;
  protected $platformDataType = '';
  public $workingDirectory;

  public function setArguments($arguments)
  {
    $this->arguments = $arguments;
  }
  public function getArguments()
  {
    return $this->arguments;
  }
  /**
   * @param BuildBazelRemoteExecutionV2CommandEnvironmentVariable[]
   */
  public function setEnvironmentVariables($environmentVariables)
  {
    $this->environmentVariables = $environmentVariables;
  }
  /**
   * @return BuildBazelRemoteExecutionV2CommandEnvironmentVariable[]
   */
  public function getEnvironmentVariables()
  {
    return $this->environmentVariables;
  }
  public function setOutputDirectories($outputDirectories)
  {
    $this->outputDirectories = $outputDirectories;
  }
  public function getOutputDirectories()
  {
    return $this->outputDirectories;
  }
  public function setOutputFiles($outputFiles)
  {
    $this->outputFiles = $outputFiles;
  }
  public function getOutputFiles()
  {
    return $this->outputFiles;
  }
  public function setOutputNodeProperties($outputNodeProperties)
  {
    $this->outputNodeProperties = $outputNodeProperties;
  }
  public function getOutputNodeProperties()
  {
    return $this->outputNodeProperties;
  }
  public function setOutputPaths($outputPaths)
  {
    $this->outputPaths = $outputPaths;
  }
  public function getOutputPaths()
  {
    return $this->outputPaths;
  }
  /**
   * @param BuildBazelRemoteExecutionV2Platform
   */
  public function setPlatform(BuildBazelRemoteExecutionV2Platform $platform)
  {
    $this->platform = $platform;
  }
  /**
   * @return BuildBazelRemoteExecutionV2Platform
   */
  public function getPlatform()
  {
    return $this->platform;
  }
  public function setWorkingDirectory($workingDirectory)
  {
    $this->workingDirectory = $workingDirectory;
  }
  public function getWorkingDirectory()
  {
    return $this->workingDirectory;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(BuildBazelRemoteExecutionV2Command::class, 'Google_Service_RemoteBuildExecution_BuildBazelRemoteExecutionV2Command');
