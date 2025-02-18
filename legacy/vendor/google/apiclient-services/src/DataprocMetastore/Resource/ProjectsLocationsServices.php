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

namespace Google\Service\DataprocMetastore\Resource;

use Google\Service\DataprocMetastore\AlterMetadataResourceLocationRequest;
use Google\Service\DataprocMetastore\AlterTablePropertiesRequest;
use Google\Service\DataprocMetastore\CancelMigrationRequest;
use Google\Service\DataprocMetastore\CompleteMigrationRequest;
use Google\Service\DataprocMetastore\ExportMetadataRequest;
use Google\Service\DataprocMetastore\ListServicesResponse;
use Google\Service\DataprocMetastore\MoveTableToDatabaseRequest;
use Google\Service\DataprocMetastore\Operation;
use Google\Service\DataprocMetastore\Policy;
use Google\Service\DataprocMetastore\QueryMetadataRequest;
use Google\Service\DataprocMetastore\RestoreServiceRequest;
use Google\Service\DataprocMetastore\Service;
use Google\Service\DataprocMetastore\SetIamPolicyRequest;
use Google\Service\DataprocMetastore\StartMigrationRequest;
use Google\Service\DataprocMetastore\TestIamPermissionsRequest;
use Google\Service\DataprocMetastore\TestIamPermissionsResponse;

/**
 * The "services" collection of methods.
 * Typical usage is:
 *  <code>
 *   $metastoreService = new Google\Service\DataprocMetastore(...);
 *   $services = $metastoreService->projects_locations_services;
 *  </code>
 */
class ProjectsLocationsServices extends \Google\Service\Resource
{
  /**
   * Alter metadata resource location. The metadata resource can be a database,
   * table, or partition. This functionality only updates the parent directory for
   * the respective metadata resource and does not transfer any existing data to
   * the new location. (services.alterLocation)
   *
   * @param string $service Required. The relative resource name of the metastore
   * service to mutate metadata, in the following
   * format:projects/{project_id}/locations/{location_id}/services/{service_id}.
   * @param AlterMetadataResourceLocationRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function alterLocation($service, AlterMetadataResourceLocationRequest $postBody, $optParams = [])
  {
    $params = ['service' => $service, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('alterLocation', [$params], Operation::class);
  }
  /**
   * Alter metadata table properties. (services.alterTableProperties)
   *
   * @param string $service Required. The relative resource name of the Dataproc
   * Metastore service that's being used to mutate metadata table properties, in
   * the following
   * format:projects/{project_id}/locations/{location_id}/services/{service_id}.
   * @param AlterTablePropertiesRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function alterTableProperties($service, AlterTablePropertiesRequest $postBody, $optParams = [])
  {
    $params = ['service' => $service, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('alterTableProperties', [$params], Operation::class);
  }
  /**
   * Cancels the ongoing Managed Migration process. (services.cancelMigration)
   *
   * @param string $service Required. The relative resource name of the metastore
   * service to cancel the ongoing migration to, in the following
   * format:projects/{project_id}/locations/{location_id}/services/{service_id}.
   * @param CancelMigrationRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function cancelMigration($service, CancelMigrationRequest $postBody, $optParams = [])
  {
    $params = ['service' => $service, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('cancelMigration', [$params], Operation::class);
  }
  /**
   * Completes the managed migration process. The Dataproc Metastore service will
   * switch to using its own backend database after successful migration.
   * (services.completeMigration)
   *
   * @param string $service Required. The relative resource name of the metastore
   * service to complete the migration to, in the following
   * format:projects/{project_id}/locations/{location_id}/services/{service_id}.
   * @param CompleteMigrationRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function completeMigration($service, CompleteMigrationRequest $postBody, $optParams = [])
  {
    $params = ['service' => $service, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('completeMigration', [$params], Operation::class);
  }
  /**
   * Creates a metastore service in a project and location. (services.create)
   *
   * @param string $parent Required. The relative resource name of the location in
   * which to create a metastore service, in the following
   * form:projects/{project_number}/locations/{location_id}.
   * @param Service $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string requestId Optional. A request ID. Specify a unique request
   * ID to allow the server to ignore the request if it has completed. The server
   * will ignore subsequent requests that provide a duplicate request ID for at
   * least 60 minutes after the first request.For example, if an initial request
   * times out, followed by another request with the same request ID, the server
   * ignores the second request to prevent the creation of duplicate
   * commitments.The request ID must be a valid UUID
   * (https://en.wikipedia.org/wiki/Universally_unique_identifier#Format) A zero
   * UUID (00000000-0000-0000-0000-000000000000) is not supported.
   * @opt_param string serviceId Required. The ID of the metastore service, which
   * is used as the final component of the metastore service's name.This value
   * must be between 2 and 63 characters long inclusive, begin with a letter, end
   * with a letter or number, and consist of alpha-numeric ASCII characters or
   * hyphens.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function create($parent, Service $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Operation::class);
  }
  /**
   * Deletes a single service. (services.delete)
   *
   * @param string $name Required. The relative resource name of the metastore
   * service to delete, in the following
   * form:projects/{project_number}/locations/{location_id}/services/{service_id}.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string requestId Optional. A request ID. Specify a unique request
   * ID to allow the server to ignore the request if it has completed. The server
   * will ignore subsequent requests that provide a duplicate request ID for at
   * least 60 minutes after the first request.For example, if an initial request
   * times out, followed by another request with the same request ID, the server
   * ignores the second request to prevent the creation of duplicate
   * commitments.The request ID must be a valid UUID
   * (https://en.wikipedia.org/wiki/Universally_unique_identifier#Format) A zero
   * UUID (00000000-0000-0000-0000-000000000000) is not supported.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], Operation::class);
  }
  /**
   * Exports metadata from a service. (services.exportMetadata)
   *
   * @param string $service Required. The relative resource name of the metastore
   * service to run export, in the following
   * form:projects/{project_id}/locations/{location_id}/services/{service_id}.
   * @param ExportMetadataRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function exportMetadata($service, ExportMetadataRequest $postBody, $optParams = [])
  {
    $params = ['service' => $service, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('exportMetadata', [$params], Operation::class);
  }
  /**
   * Gets the details of a single service. (services.get)
   *
   * @param string $name Required. The relative resource name of the metastore
   * service to retrieve, in the following
   * form:projects/{project_number}/locations/{location_id}/services/{service_id}.
   * @param array $optParams Optional parameters.
   * @return Service
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Service::class);
  }
  /**
   * Gets the access control policy for a resource. Returns an empty policy if the
   * resource exists and does not have a policy set. (services.getIamPolicy)
   *
   * @param string $resource REQUIRED: The resource for which the policy is being
   * requested. See Resource names
   * (https://cloud.google.com/apis/design/resource_names) for the appropriate
   * value for this field.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int options.requestedPolicyVersion Optional. The maximum policy
   * version that will be used to format the policy.Valid values are 0, 1, and 3.
   * Requests specifying an invalid value will be rejected.Requests for policies
   * with any conditional role bindings must specify version 3. Policies with no
   * conditional role bindings may specify any valid value or leave the field
   * unset.The policy in the response might use the policy version that you
   * specified, or it might use a lower policy version. For example, if you
   * specify version 3, but the policy has no conditional role bindings, the
   * response uses version 1.To learn which resources support conditions in their
   * IAM policies, see the IAM documentation
   * (https://cloud.google.com/iam/help/conditions/resource-policies).
   * @return Policy
   * @throws \Google\Service\Exception
   */
  public function getIamPolicy($resource, $optParams = [])
  {
    $params = ['resource' => $resource];
    $params = array_merge($params, $optParams);
    return $this->call('getIamPolicy', [$params], Policy::class);
  }
  /**
   * Lists services in a project and location.
   * (services.listProjectsLocationsServices)
   *
   * @param string $parent Required. The relative resource name of the location of
   * metastore services to list, in the following
   * form:projects/{project_number}/locations/{location_id}.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter Optional. The filter to apply to list results.
   * @opt_param string orderBy Optional. Specify the ordering of results as
   * described in Sorting Order
   * (https://cloud.google.com/apis/design/design_patterns#sorting_order). If not
   * specified, the results will be sorted in the default order.
   * @opt_param int pageSize Optional. The maximum number of services to return.
   * The response may contain less than the maximum number. If unspecified, no
   * more than 500 services are returned. The maximum value is 1000; values above
   * 1000 are changed to 1000.
   * @opt_param string pageToken Optional. A page token, received from a previous
   * DataprocMetastore.ListServices call. Provide this token to retrieve the
   * subsequent page.To retrieve the first page, supply an empty page token.When
   * paginating, other parameters provided to DataprocMetastore.ListServices must
   * match the call that provided the page token.
   * @return ListServicesResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsLocationsServices($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListServicesResponse::class);
  }
  /**
   * Move a table to another database. (services.moveTableToDatabase)
   *
   * @param string $service Required. The relative resource name of the metastore
   * service to mutate metadata, in the following
   * format:projects/{project_id}/locations/{location_id}/services/{service_id}.
   * @param MoveTableToDatabaseRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function moveTableToDatabase($service, MoveTableToDatabaseRequest $postBody, $optParams = [])
  {
    $params = ['service' => $service, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('moveTableToDatabase', [$params], Operation::class);
  }
  /**
   * Updates the parameters of a single service. (services.patch)
   *
   * @param string $name Immutable. The relative resource name of the metastore
   * service, in the following format:projects/{project_number}/locations/{locatio
   * n_id}/services/{service_id}.
   * @param Service $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string requestId Optional. A request ID. Specify a unique request
   * ID to allow the server to ignore the request if it has completed. The server
   * will ignore subsequent requests that provide a duplicate request ID for at
   * least 60 minutes after the first request.For example, if an initial request
   * times out, followed by another request with the same request ID, the server
   * ignores the second request to prevent the creation of duplicate
   * commitments.The request ID must be a valid UUID
   * (https://en.wikipedia.org/wiki/Universally_unique_identifier#Format) A zero
   * UUID (00000000-0000-0000-0000-000000000000) is not supported.
   * @opt_param string updateMask Required. A field mask used to specify the
   * fields to be overwritten in the metastore service resource by the update.
   * Fields specified in the update_mask are relative to the resource (not to the
   * full request). A field is overwritten if it is in the mask.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function patch($name, Service $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('patch', [$params], Operation::class);
  }
  /**
   * Query Dataproc Metastore metadata. (services.queryMetadata)
   *
   * @param string $service Required. The relative resource name of the metastore
   * service to query metadata, in the following
   * format:projects/{project_id}/locations/{location_id}/services/{service_id}.
   * @param QueryMetadataRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function queryMetadata($service, QueryMetadataRequest $postBody, $optParams = [])
  {
    $params = ['service' => $service, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('queryMetadata', [$params], Operation::class);
  }
  /**
   * Restores a service from a backup. (services.restore)
   *
   * @param string $service Required. The relative resource name of the metastore
   * service to run restore, in the following
   * form:projects/{project_id}/locations/{location_id}/services/{service_id}.
   * @param RestoreServiceRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function restore($service, RestoreServiceRequest $postBody, $optParams = [])
  {
    $params = ['service' => $service, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('restore', [$params], Operation::class);
  }
  /**
   * Sets the access control policy on the specified resource. Replaces any
   * existing policy.Can return NOT_FOUND, INVALID_ARGUMENT, and PERMISSION_DENIED
   * errors. (services.setIamPolicy)
   *
   * @param string $resource REQUIRED: The resource for which the policy is being
   * specified. See Resource names
   * (https://cloud.google.com/apis/design/resource_names) for the appropriate
   * value for this field.
   * @param SetIamPolicyRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Policy
   * @throws \Google\Service\Exception
   */
  public function setIamPolicy($resource, SetIamPolicyRequest $postBody, $optParams = [])
  {
    $params = ['resource' => $resource, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('setIamPolicy', [$params], Policy::class);
  }
  /**
   * Starts the Managed Migration process. (services.startMigration)
   *
   * @param string $service Required. The relative resource name of the metastore
   * service to start migrating to, in the following
   * format:projects/{project_id}/locations/{location_id}/services/{service_id}.
   * @param StartMigrationRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function startMigration($service, StartMigrationRequest $postBody, $optParams = [])
  {
    $params = ['service' => $service, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('startMigration', [$params], Operation::class);
  }
  /**
   * Returns permissions that a caller has on the specified resource. If the
   * resource does not exist, this will return an empty set of permissions, not a
   * NOT_FOUND error.Note: This operation is designed to be used for building
   * permission-aware UIs and command-line tools, not for authorization checking.
   * This operation may "fail open" without warning. (services.testIamPermissions)
   *
   * @param string $resource REQUIRED: The resource for which the policy detail is
   * being requested. See Resource names
   * (https://cloud.google.com/apis/design/resource_names) for the appropriate
   * value for this field.
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
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsLocationsServices::class, 'Google_Service_DataprocMetastore_Resource_ProjectsLocationsServices');
