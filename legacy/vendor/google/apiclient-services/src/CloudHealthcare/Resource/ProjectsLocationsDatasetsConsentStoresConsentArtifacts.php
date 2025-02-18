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

namespace Google\Service\CloudHealthcare\Resource;

use Google\Service\CloudHealthcare\ConsentArtifact;
use Google\Service\CloudHealthcare\HealthcareEmpty;
use Google\Service\CloudHealthcare\ListConsentArtifactsResponse;

/**
 * The "consentArtifacts" collection of methods.
 * Typical usage is:
 *  <code>
 *   $healthcareService = new Google\Service\CloudHealthcare(...);
 *   $consentArtifacts = $healthcareService->projects_locations_datasets_consentStores_consentArtifacts;
 *  </code>
 */
class ProjectsLocationsDatasetsConsentStoresConsentArtifacts extends \Google\Service\Resource
{
  /**
   * Creates a new Consent artifact in the parent consent store.
   * (consentArtifacts.create)
   *
   * @param string $parent Required. The name of the consent store this Consent
   * artifact belongs to.
   * @param ConsentArtifact $postBody
   * @param array $optParams Optional parameters.
   * @return ConsentArtifact
   * @throws \Google\Service\Exception
   */
  public function create($parent, ConsentArtifact $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], ConsentArtifact::class);
  }
  /**
   * Deletes the specified Consent artifact. Fails if the artifact is referenced
   * by the latest revision of any Consent. (consentArtifacts.delete)
   *
   * @param string $name Required. The resource name of the Consent artifact to
   * delete. To preserve referential integrity, Consent artifacts referenced by
   * the latest revision of a Consent cannot be deleted.
   * @param array $optParams Optional parameters.
   * @return HealthcareEmpty
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], HealthcareEmpty::class);
  }
  /**
   * Gets the specified Consent artifact. (consentArtifacts.get)
   *
   * @param string $name Required. The resource name of the Consent artifact to
   * retrieve.
   * @param array $optParams Optional parameters.
   * @return ConsentArtifact
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], ConsentArtifact::class);
  }
  /**
   * Lists the Consent artifacts in the specified consent store.
   * (consentArtifacts.listProjectsLocationsDatasetsConsentStoresConsentArtifacts)
   *
   * @param string $parent Required. Name of the consent store to retrieve consent
   * artifacts from.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter Optional. Restricts the artifacts returned to those
   * matching a filter. The following syntax is available: * A string field value
   * can be written as text inside quotation marks, for example `"query text"`.
   * The only valid relational operation for text fields is equality (`=`), where
   * text is searched within the field, rather than having the field be equal to
   * the text. For example, `"Comment = great"` returns messages with `great` in
   * the comment field. * A number field value can be written as an integer, a
   * decimal, or an exponential. The valid relational operators for number fields
   * are the equality operator (`=`), along with the less than/greater than
   * operators (`<`, `<=`, `>`, `>=`). Note that there is no inequality (`!=`)
   * operator. You can prepend the `NOT` operator to an expression to negate it. *
   * A date field value must be written in `yyyy-mm-dd` form. Fields with date and
   * time use the RFC3339 time format. Leading zeros are required for one-digit
   * months and days. The valid relational operators for date fields are the
   * equality operator (`=`) , along with the less than/greater than operators
   * (`<`, `<=`, `>`, `>=`). Note that there is no inequality (`!=`) operator. You
   * can prepend the `NOT` operator to an expression to negate it. * Multiple
   * field query expressions can be combined in one query by adding `AND` or `OR`
   * operators between the expressions. If a boolean operator appears within a
   * quoted string, it is not treated as special, it's just another part of the
   * character string to be matched. You can prepend the `NOT` operator to an
   * expression to negate it. The fields available for filtering are: - user_id.
   * For example, `filter=user_id=\"user123\"`. - consent_content_version -
   * metadata. For example, `filter=Metadata(\"testkey\")=\"value\"` or
   * `filter=HasMetadata(\"testkey\")`.
   * @opt_param int pageSize Optional. Limit on the number of consent artifacts to
   * return in a single response. If not specified, 100 is used. May not be larger
   * than 1000.
   * @opt_param string pageToken Optional. The next_page_token value returned from
   * the previous List request, if any.
   * @return ListConsentArtifactsResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsLocationsDatasetsConsentStoresConsentArtifacts($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListConsentArtifactsResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsDatasetsConsentStoresConsentArtifacts::class, 'Google_Service_CloudHealthcare_Resource_ProjectsLocationsDatasetsConsentStoresConsentArtifacts');
