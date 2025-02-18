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

namespace Google\Service\Compute;

class HttpRouteRuleMatch extends \Google\Collection
{
  protected $collection_key = 'queryParameterMatches';
  /**
   * @var string
   */
  public $fullPathMatch;
  protected $headerMatchesType = HttpHeaderMatch::class;
  protected $headerMatchesDataType = 'array';
  /**
   * @var bool
   */
  public $ignoreCase;
  protected $metadataFiltersType = MetadataFilter::class;
  protected $metadataFiltersDataType = 'array';
  /**
   * @var string
   */
  public $pathTemplateMatch;
  /**
   * @var string
   */
  public $prefixMatch;
  protected $queryParameterMatchesType = HttpQueryParameterMatch::class;
  protected $queryParameterMatchesDataType = 'array';
  /**
   * @var string
   */
  public $regexMatch;

  /**
   * @param string
   */
  public function setFullPathMatch($fullPathMatch)
  {
    $this->fullPathMatch = $fullPathMatch;
  }
  /**
   * @return string
   */
  public function getFullPathMatch()
  {
    return $this->fullPathMatch;
  }
  /**
   * @param HttpHeaderMatch[]
   */
  public function setHeaderMatches($headerMatches)
  {
    $this->headerMatches = $headerMatches;
  }
  /**
   * @return HttpHeaderMatch[]
   */
  public function getHeaderMatches()
  {
    return $this->headerMatches;
  }
  /**
   * @param bool
   */
  public function setIgnoreCase($ignoreCase)
  {
    $this->ignoreCase = $ignoreCase;
  }
  /**
   * @return bool
   */
  public function getIgnoreCase()
  {
    return $this->ignoreCase;
  }
  /**
   * @param MetadataFilter[]
   */
  public function setMetadataFilters($metadataFilters)
  {
    $this->metadataFilters = $metadataFilters;
  }
  /**
   * @return MetadataFilter[]
   */
  public function getMetadataFilters()
  {
    return $this->metadataFilters;
  }
  /**
   * @param string
   */
  public function setPathTemplateMatch($pathTemplateMatch)
  {
    $this->pathTemplateMatch = $pathTemplateMatch;
  }
  /**
   * @return string
   */
  public function getPathTemplateMatch()
  {
    return $this->pathTemplateMatch;
  }
  /**
   * @param string
   */
  public function setPrefixMatch($prefixMatch)
  {
    $this->prefixMatch = $prefixMatch;
  }
  /**
   * @return string
   */
  public function getPrefixMatch()
  {
    return $this->prefixMatch;
  }
  /**
   * @param HttpQueryParameterMatch[]
   */
  public function setQueryParameterMatches($queryParameterMatches)
  {
    $this->queryParameterMatches = $queryParameterMatches;
  }
  /**
   * @return HttpQueryParameterMatch[]
   */
  public function getQueryParameterMatches()
  {
    return $this->queryParameterMatches;
  }
  /**
   * @param string
   */
  public function setRegexMatch($regexMatch)
  {
    $this->regexMatch = $regexMatch;
  }
  /**
   * @return string
   */
  public function getRegexMatch()
  {
    return $this->regexMatch;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(HttpRouteRuleMatch::class, 'Google_Service_Compute_HttpRouteRuleMatch');
