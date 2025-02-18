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

namespace Google\Service\DataCatalog\Resource;

use Google\Service\DataCatalog\DatacatalogEmpty;
use Google\Service\DataCatalog\GetIamPolicyRequest;
use Google\Service\DataCatalog\GoogleCloudDatacatalogV1Contacts;
use Google\Service\DataCatalog\GoogleCloudDatacatalogV1Entry;
use Google\Service\DataCatalog\GoogleCloudDatacatalogV1EntryOverview;
use Google\Service\DataCatalog\GoogleCloudDatacatalogV1ImportEntriesRequest;
use Google\Service\DataCatalog\GoogleCloudDatacatalogV1ListEntriesResponse;
use Google\Service\DataCatalog\GoogleCloudDatacatalogV1ModifyEntryContactsRequest;
use Google\Service\DataCatalog\GoogleCloudDatacatalogV1ModifyEntryOverviewRequest;
use Google\Service\DataCatalog\GoogleCloudDatacatalogV1StarEntryRequest;
use Google\Service\DataCatalog\GoogleCloudDatacatalogV1StarEntryResponse;
use Google\Service\DataCatalog\GoogleCloudDatacatalogV1UnstarEntryRequest;
use Google\Service\DataCatalog\GoogleCloudDatacatalogV1UnstarEntryResponse;
use Google\Service\DataCatalog\Operation;
use Google\Service\DataCatalog\Policy;
use Google\Service\DataCatalog\TestIamPermissionsRequest;
use Google\Service\DataCatalog\TestIamPermissionsResponse;

/**
 * The "entries" collection of methods.
 * Typical usage is:
 *  <code>
 *   $datacatalogService = new Google\Service\DataCatalog(...);
 *   $entries = $datacatalogService->projects_locations_entryGroups_entries;
 *  </code>
 */
class ProjectsLocationsEntryGroupsEntries extends \Google\Service\Resource
{
  /**
   * Creates an entry. You can create entries only with 'FILESET', 'CLUSTER',
   * 'DATA_STREAM', or custom types. Data Catalog automatically creates entries
   * with other types during metadata ingestion from integrated systems. You must
   * enable the Data Catalog API in the project identified by the `parent`
   * parameter. For more information, see [Data Catalog resource
   * project](https://cloud.google.com/data-catalog/docs/concepts/resource-
   * project). An entry group can have a maximum of 100,000 entries.
   * (entries.create)
   *
   * @param string $parent Required. The name of the entry group this entry
   * belongs to. Note: The entry itself and its child resources might not be
   * stored in the location specified in its name.
   * @param GoogleCloudDatacatalogV1Entry $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string entryId Required. The ID of the entry to create. The ID
   * must contain only letters (a-z, A-Z), numbers (0-9), and underscores (_). The
   * maximum size is 64 bytes when encoded in UTF-8.
   * @return GoogleCloudDatacatalogV1Entry
   * @throws \Google\Service\Exception
   */
  public function create($parent, GoogleCloudDatacatalogV1Entry $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], GoogleCloudDatacatalogV1Entry::class);
  }
  /**
   * Deletes an existing entry. You can delete only the entries created by the
   * CreateEntry method. You must enable the Data Catalog API in the project
   * identified by the `name` parameter. For more information, see [Data Catalog
   * resource project](https://cloud.google.com/data-
   * catalog/docs/concepts/resource-project). (entries.delete)
   *
   * @param string $name Required. The name of the entry to delete.
   * @param array $optParams Optional parameters.
   * @return DatacatalogEmpty
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], DatacatalogEmpty::class);
  }
  /**
   * Gets an entry. (entries.get)
   *
   * @param string $name Required. The name of the entry to get.
   * @param array $optParams Optional parameters.
   * @return GoogleCloudDatacatalogV1Entry
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], GoogleCloudDatacatalogV1Entry::class);
  }
  /**
   * Gets the access control policy for a resource. May return: * A`NOT_FOUND`
   * error if the resource doesn't exist or you don't have the permission to view
   * it. * An empty policy if the resource exists but doesn't have a set policy.
   * Supported resources are: - Tag templates - Entry groups Note: This method
   * doesn't get policies from Google Cloud Platform resources ingested into Data
   * Catalog. To call this method, you must have the following Google IAM
   * permissions: - `datacatalog.tagTemplates.getIamPolicy` to get policies on tag
   * templates. - `datacatalog.entryGroups.getIamPolicy` to get policies on entry
   * groups. (entries.getIamPolicy)
   *
   * @param string $resource REQUIRED: The resource for which the policy is being
   * requested. See [Resource
   * names](https://cloud.google.com/apis/design/resource_names) for the
   * appropriate value for this field.
   * @param GetIamPolicyRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Policy
   * @throws \Google\Service\Exception
   */
  public function getIamPolicy($resource, GetIamPolicyRequest $postBody, $optParams = [])
  {
    $params = ['resource' => $resource, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('getIamPolicy', [$params], Policy::class);
  }
  /**
   * Imports entries from a source, such as data previously dumped into a Cloud
   * Storage bucket, into Data Catalog. Import of entries is a sync operation that
   * reconciles the state of the third-party system with the Data Catalog.
   * `ImportEntries` accepts source data snapshots of a third-party system.
   * Snapshot should be delivered as a .wire or base65-encoded .txt file
   * containing a sequence of Protocol Buffer messages of DumpItem type.
   * `ImportEntries` returns a long-running operation resource that can be queried
   * with Operations.GetOperation to return ImportEntriesMetadata and an
   * ImportEntriesResponse message. (entries.import)
   *
   * @param string $parent Required. Target entry group for ingested entries.
   * @param GoogleCloudDatacatalogV1ImportEntriesRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function import($parent, GoogleCloudDatacatalogV1ImportEntriesRequest $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('import', [$params], Operation::class);
  }
  /**
   * Lists entries. Note: Currently, this method can list only custom entries. To
   * get a list of both custom and automatically created entries, use
   * SearchCatalog. (entries.listProjectsLocationsEntryGroupsEntries)
   *
   * @param string $parent Required. The name of the entry group that contains the
   * entries to list. Can be provided in URL format.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int pageSize The maximum number of items to return. Default is 10.
   * Maximum limit is 1000. Throws an invalid argument if `page_size` is more than
   * 1000.
   * @opt_param string pageToken Pagination token that specifies the next page to
   * return. If empty, the first page is returned.
   * @opt_param string readMask The fields to return for each entry. If empty or
   * omitted, all fields are returned. For example, to return a list of entries
   * with only the `name` field, set `read_mask` to only one path with the `name`
   * value.
   * @return GoogleCloudDatacatalogV1ListEntriesResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsLocationsEntryGroupsEntries($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], GoogleCloudDatacatalogV1ListEntriesResponse::class);
  }
  /**
   * Modifies contacts, part of the business context of an Entry. To call this
   * method, you must have the `datacatalog.entries.updateContacts` IAM permission
   * on the corresponding project. (entries.modifyEntryContacts)
   *
   * @param string $name Required. The full resource name of the entry.
   * @param GoogleCloudDatacatalogV1ModifyEntryContactsRequest $postBody
   * @param array $optParams Optional parameters.
   * @return GoogleCloudDatacatalogV1Contacts
   * @throws \Google\Service\Exception
   */
  public function modifyEntryContacts($name, GoogleCloudDatacatalogV1ModifyEntryContactsRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('modifyEntryContacts', [$params], GoogleCloudDatacatalogV1Contacts::class);
  }
  /**
   * Modifies entry overview, part of the business context of an Entry. To call
   * this method, you must have the `datacatalog.entries.updateOverview` IAM
   * permission on the corresponding project. (entries.modifyEntryOverview)
   *
   * @param string $name Required. The full resource name of the entry.
   * @param GoogleCloudDatacatalogV1ModifyEntryOverviewRequest $postBody
   * @param array $optParams Optional parameters.
   * @return GoogleCloudDatacatalogV1EntryOverview
   * @throws \Google\Service\Exception
   */
  public function modifyEntryOverview($name, GoogleCloudDatacatalogV1ModifyEntryOverviewRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('modifyEntryOverview', [$params], GoogleCloudDatacatalogV1EntryOverview::class);
  }
  /**
   * Updates an existing entry. You must enable the Data Catalog API in the
   * project identified by the `entry.name` parameter. For more information, see
   * [Data Catalog resource project](https://cloud.google.com/data-
   * catalog/docs/concepts/resource-project). (entries.patch)
   *
   * @param string $name Output only. Identifier. The resource name of an entry in
   * URL format. Note: The entry itself and its child resources might not be
   * stored in the location specified in its name.
   * @param GoogleCloudDatacatalogV1Entry $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string updateMask Names of fields whose values to overwrite on an
   * entry. If this parameter is absent or empty, all modifiable fields are
   * overwritten. If such fields are non-required and omitted in the request body,
   * their values are emptied. You can modify only the fields listed below. For
   * entries with type `DATA_STREAM`: * `schema` For entries with type `FILESET`:
   * * `schema` * `display_name` * `description` * `gcs_fileset_spec` *
   * `gcs_fileset_spec.file_patterns` For entries with `user_specified_type`: *
   * `schema` * `display_name` * `description` * `user_specified_type` *
   * `user_specified_system` * `linked_resource` * `source_system_timestamps`
   * @return GoogleCloudDatacatalogV1Entry
   * @throws \Google\Service\Exception
   */
  public function patch($name, GoogleCloudDatacatalogV1Entry $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], GoogleCloudDatacatalogV1Entry::class);
  }
  /**
   * Marks an Entry as starred by the current user. Starring information is
   * private to each user. (entries.star)
   *
   * @param string $name Required. The name of the entry to mark as starred.
   * @param GoogleCloudDatacatalogV1StarEntryRequest $postBody
   * @param array $optParams Optional parameters.
   * @return GoogleCloudDatacatalogV1StarEntryResponse
   * @throws \Google\Service\Exception
   */
  public function star($name, GoogleCloudDatacatalogV1StarEntryRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('star', [$params], GoogleCloudDatacatalogV1StarEntryResponse::class);
  }
  /**
   * Gets your permissions on a resource. Returns an empty set of permissions if
   * the resource doesn't exist. Supported resources are: - Tag templates - Entry
   * groups Note: This method gets policies only within Data Catalog and can't be
   * used to get policies from BigQuery, Pub/Sub, Dataproc Metastore, and any
   * external Google Cloud Platform resources ingested into Data Catalog. No
   * Google IAM permissions are required to call this method.
   * (entries.testIamPermissions)
   *
   * @param string $resource REQUIRED: The resource for which the policy detail is
   * being requested. See [Resource
   * names](https://cloud.google.com/apis/design/resource_names) for the
   * appropriate value for this field.
   * @param TestIamPermissionsRequest $postBody
   * @param array $optParams Optional parameters.
   * @return TestIamPermissionsResponse
   * @throws \Google\Service\Exception
   */
  public function testIamPermissions($resource, TestIamPermissionsRequest $postBody, $optParams = [])
  {
    $params = ['resource' => $resource, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('testIamPermissions', [$params], TestIamPermissionsResponse::class);
  }
  /**
   * Marks an Entry as NOT starred by the current user. Starring information is
   * private to each user. (entries.unstar)
   *
   * @param string $name Required. The name of the entry to mark as **not**
   * starred.
   * @param GoogleCloudDatacatalogV1UnstarEntryRequest $postBody
   * @param array $optParams Optional parameters.
   * @return GoogleCloudDatacatalogV1UnstarEntryResponse
   * @throws \Google\Service\Exception
   */
  public function unstar($name, GoogleCloudDatacatalogV1UnstarEntryRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('unstar', [$params], GoogleCloudDatacatalogV1UnstarEntryResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsEntryGroupsEntries::class, 'Google_Service_DataCatalog_Resource_ProjectsLocationsEntryGroupsEntries');
