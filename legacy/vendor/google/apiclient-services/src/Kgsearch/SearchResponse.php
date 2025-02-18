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

namespace Google\Service\Kgsearch;

class SearchResponse extends \Google\Collection
{
  protected $collection_key = 'itemListElement';
  protected $internal_gapi_mappings = [
        "context" => "@context",
        "type" => "@type",
  ];
  /**
   * @var array
   */
  public $context;
  /**
   * @var array
   */
  public $type;
  /**
   * @var array[]
   */
  public $itemListElement;

  /**
   * @param array
   */
  public function setContext($context)
  {
    $this->context = $context;
  }
  /**
   * @return array
   */
  public function getContext()
  {
    return $this->context;
  }
  /**
   * @param array
   */
  public function setType($type)
  {
    $this->type = $type;
  }
  /**
   * @return array
   */
  public function getType()
  {
    return $this->type;
  }
  /**
   * @param array[]
   */
  public function setItemListElement($itemListElement)
  {
    $this->itemListElement = $itemListElement;
  }
  /**
   * @return array[]
   */
  public function getItemListElement()
  {
    return $this->itemListElement;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(SearchResponse::class, 'Google_Service_Kgsearch_SearchResponse');
