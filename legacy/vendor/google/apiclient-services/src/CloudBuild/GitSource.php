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

namespace Google\Service\CloudBuild;

class GitSource extends \Google\Model
{
  /**
   * @var string
   */
  public $dir;
  /**
   * @var string
   */
  public $revision;
  /**
   * @var string
   */
  public $url;

  /**
   * @param string
   */
  public function setDir($dir)
  {
    $this->dir = $dir;
  }
  /**
   * @return string
   */
  public function getDir()
  {
    return $this->dir;
  }
  /**
   * @param string
   */
  public function setRevision($revision)
  {
    $this->revision = $revision;
  }
  /**
   * @return string
   */
  public function getRevision()
  {
    return $this->revision;
  }
  /**
   * @param string
   */
  public function setUrl($url)
  {
    $this->url = $url;
  }
  /**
   * @return string
   */
  public function getUrl()
  {
    return $this->url;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GitSource::class, 'Google_Service_CloudBuild_GitSource');
