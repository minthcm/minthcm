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

namespace Google\Service\Spanner;

class GetDatabaseDdlResponse extends \Google\Collection
{
  protected $collection_key = 'statements';
  /**
   * @var string
   */
  public $protoDescriptors;
  /**
   * @var string[]
   */
  public $statements;

  /**
   * @param string
   */
  public function setProtoDescriptors($protoDescriptors)
  {
    $this->protoDescriptors = $protoDescriptors;
  }
  /**
   * @return string
   */
  public function getProtoDescriptors()
  {
    return $this->protoDescriptors;
  }
  /**
   * @param string[]
   */
  public function setStatements($statements)
  {
    $this->statements = $statements;
  }
  /**
   * @return string[]
   */
  public function getStatements()
  {
    return $this->statements;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GetDatabaseDdlResponse::class, 'Google_Service_Spanner_GetDatabaseDdlResponse');
