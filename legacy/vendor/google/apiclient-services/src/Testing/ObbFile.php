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

namespace Google\Service\Testing;

class ObbFile extends \Google\Model
{
  protected $obbType = FileReference::class;
  protected $obbDataType = '';
  /**
   * @var string
   */
  public $obbFileName;

  /**
   * @param FileReference
   */
  public function setObb(FileReference $obb)
  {
    $this->obb = $obb;
  }
  /**
   * @return FileReference
   */
  public function getObb()
  {
    return $this->obb;
  }
  /**
   * @param string
   */
  public function setObbFileName($obbFileName)
  {
    $this->obbFileName = $obbFileName;
  }
  /**
   * @return string
   */
  public function getObbFileName()
  {
    return $this->obbFileName;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ObbFile::class, 'Google_Service_Testing_ObbFile');
