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

namespace Google\Service\ServiceConsumerManagement\Resource;

use Google\Service\ServiceConsumerManagement\AddTenantProjectRequest;
use Google\Service\ServiceConsumerManagement\ApplyTenantProjectConfigRequest;
use Google\Service\ServiceConsumerManagement\AttachTenantProjectRequest;
use Google\Service\ServiceConsumerManagement\CreateTenancyUnitRequest;
use Google\Service\ServiceConsumerManagement\DeleteTenantProjectRequest;
use Google\Service\ServiceConsumerManagement\ListTenancyUnitsResponse;
use Google\Service\ServiceConsumerManagement\Operation;
use Google\Service\ServiceConsumerManagement\RemoveTenantProjectRequest;
use Google\Service\ServiceConsumerManagement\TenancyUnit;
use Google\Service\ServiceConsumerManagement\UndeleteTenantProjectRequest;

/**
 * The "tenancyUnits" collection of methods.
 * Typical usage is:
 *  <code>
 *   $serviceconsumermanagementService = new Google\Service\ServiceConsumerManagement(...);
 *   $tenancyUnits = $serviceconsumermanagementService->services_tenancyUnits;
 *  </code>
 */
class ServicesTenancyUnits extends \Google\Service\Resource
{
  /**
   * Add a new tenant project to the tenancy unit. There can be a maximum of 1024
   * tenant projects in a tenancy unit. If there are previously failed
   * `AddTenantProject` calls, you might need to call `RemoveTenantProject` first
   * to resolve them before you can make another call to `AddTenantProject` with
   * the same tag. Operation. (tenancyUnits.addProject)
   *
   * @param string $parent Required. Name of the tenancy unit. Such as
   * 'services/service.googleapis.com/projects/12345/tenancyUnits/abcd'.
   * @param AddTenantProjectRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function addProject($parent, AddTenantProjectRequest $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('addProject', [$params], Operation::class);
  }
  /**
   * Apply a configuration to an existing tenant project. This project must exist
   * in an active state and have the original owner account. The caller must have
   * permission to add a project to the given tenancy unit. The configuration is
   * applied, but any existing settings on the project aren't modified. Specified
   * policy bindings are applied. Existing bindings aren't modified. Specified
   * services are activated. No service is deactivated. If specified, new billing
   * configuration is applied. Omit a billing configuration to keep the existing
   * one. A service account in the project is created if previously non existed.
   * Specified labels will be appended to tenant project, note that the value of
   * existing label key will be updated if the same label key is requested. The
   * specified folder is ignored, as moving a tenant project to a different folder
   * isn't supported. The operation fails if any of the steps fail, but no
   * rollback of already applied configuration changes is attempted. Operation.
   * (tenancyUnits.applyProjectConfig)
   *
   * @param string $name Required. Name of the tenancy unit. Such as
   * 'services/service.googleapis.com/projects/12345/tenancyUnits/abcd'.
   * @param ApplyTenantProjectConfigRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function applyProjectConfig($name, ApplyTenantProjectConfigRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('applyProjectConfig', [$params], Operation::class);
  }
  /**
   * Attach an existing project to the tenancy unit as a new tenant resource. The
   * project could either be the tenant project reserved by calling
   * `AddTenantProject` under a tenancy unit of a service producer's project of a
   * managed service, or from a separate project. The caller is checked against a
   * set of permissions as if calling `AddTenantProject` on the same service
   * consumer. To trigger the attachment, the targeted tenant project must be in a
   * folder. Make sure the ServiceConsumerManagement service account is the owner
   * of that project. These two requirements are already met if the project is
   * reserved by calling `AddTenantProject`. Operation.
   * (tenancyUnits.attachProject)
   *
   * @param string $name Required. Name of the tenancy unit that the project will
   * be attached to. Such as
   * 'services/service.googleapis.com/projects/12345/tenancyUnits/abcd'.
   * @param AttachTenantProjectRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function attachProject($name, AttachTenantProjectRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('attachProject', [$params], Operation::class);
  }
  /**
   * Creates a tenancy unit with no tenant resources. If tenancy unit already
   * exists, it will be returned, however, in this case, returned TenancyUnit does
   * not have tenant_resources field set and ListTenancyUnits has to be used to
   * get a complete TenancyUnit with all fields populated. (tenancyUnits.create)
   *
   * @param string $parent Required. services/{service}/{collection id}/{resource
   * id} {collection id} is the cloud resource collection type representing the
   * service consumer, for example 'projects', or 'organizations'. {resource id}
   * is the consumer numeric id, such as project number: '123456'. {service} the
   * name of a managed service, such as 'service.googleapis.com'. Enables service
   * binding using the new tenancy unit.
   * @param CreateTenancyUnitRequest $postBody
   * @param array $optParams Optional parameters.
   * @return TenancyUnit
   * @throws \Google\Service\Exception
   */
  public function create($parent, CreateTenancyUnitRequest $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], TenancyUnit::class);
  }
  /**
   * Delete a tenancy unit. Before you delete the tenancy unit, there should be no
   * tenant resources in it that aren't in a DELETED state. Operation.
   * (tenancyUnits.delete)
   *
   * @param string $name Required. Name of the tenancy unit to be deleted.
   * @param array $optParams Optional parameters.
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
   * Deletes the specified project resource identified by a tenant resource tag.
   * The mothod removes a project lien with a 'TenantManager' origin if that was
   * added. It will then attempt to delete the project. If that operation fails,
   * this method also fails. After the project has been deleted, the tenant
   * resource state is set to DELETED. To permanently remove resource metadata,
   * call the `RemoveTenantProject` method. New resources with the same tag can't
   * be added if there are existing resources in a DELETED state. Operation.
   * (tenancyUnits.deleteProject)
   *
   * @param string $name Required. Name of the tenancy unit. Such as
   * 'services/service.googleapis.com/projects/12345/tenancyUnits/abcd'.
   * @param DeleteTenantProjectRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function deleteProject($name, DeleteTenantProjectRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('deleteProject', [$params], Operation::class);
  }
  /**
   * Find the tenancy unit for a managed service and service consumer. This method
   * shouldn't be used in a service producer's runtime path, for example to find
   * the tenant project number when creating VMs. Service producers must persist
   * the tenant project's information after the project is created.
   * (tenancyUnits.listServicesTenancyUnits)
   *
   * @param string $parent Required. Managed service and service consumer.
   * Required. services/{service}/{collection id}/{resource id} {collection id} is
   * the cloud resource collection type representing the service consumer, for
   * example 'projects', or 'organizations'. {resource id} is the consumer numeric
   * id, such as project number: '123456'. {service} the name of a service, such
   * as 'service.googleapis.com'.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string filter Optional. Filter expression over tenancy resources
   * field. Optional.
   * @opt_param int pageSize Optional. The maximum number of results returned by
   * this request.
   * @opt_param string pageToken Optional. The continuation token, which is used
   * to page through large result sets. To get the next page of results, set this
   * parameter to the value of `nextPageToken` from the previous response.
   * @return ListTenancyUnitsResponse
   * @throws \Google\Service\Exception
   */
  public function listServicesTenancyUnits($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListTenancyUnitsResponse::class);
  }
  /**
   * Removes the specified project resource identified by a tenant resource tag.
   * The method removes the project lien with 'TenantManager' origin if that was
   * added. It then attempts to delete the project. If that operation fails, this
   * method also fails. Calls to remove already removed or non-existent tenant
   * project succeed. After the project has been deleted, or if was already in a
   * DELETED state, resource metadata is permanently removed from the tenancy
   * unit. Operation. (tenancyUnits.removeProject)
   *
   * @param string $name Required. Name of the tenancy unit. Such as
   * 'services/service.googleapis.com/projects/12345/tenancyUnits/abcd'.
   * @param RemoveTenantProjectRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function removeProject($name, RemoveTenantProjectRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('removeProject', [$params], Operation::class);
  }
  /**
   * Attempts to undelete a previously deleted tenant project. The project must be
   * in a DELETED state. There are no guarantees that an undeleted project will be
   * in a fully restored and functional state. Call the `ApplyTenantProjectConfig`
   * method to update its configuration and then validate all managed service
   * resources. Operation. (tenancyUnits.undeleteProject)
   *
   * @param string $name Required. Name of the tenancy unit. Such as
   * 'services/service.googleapis.com/projects/12345/tenancyUnits/abcd'.
   * @param UndeleteTenantProjectRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function undeleteProject($name, UndeleteTenantProjectRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('undeleteProject', [$params], Operation::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ServicesTenancyUnits::class, 'Google_Service_ServiceConsumerManagement_Resource_ServicesTenancyUnits');
