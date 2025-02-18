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

namespace Google\Service\Pubsub\Resource;

use Google\Service\Pubsub\CommitSchemaRequest;
use Google\Service\Pubsub\ListSchemaRevisionsResponse;
use Google\Service\Pubsub\ListSchemasResponse;
use Google\Service\Pubsub\Policy;
use Google\Service\Pubsub\PubsubEmpty;
use Google\Service\Pubsub\RollbackSchemaRequest;
use Google\Service\Pubsub\Schema;
use Google\Service\Pubsub\SetIamPolicyRequest;
use Google\Service\Pubsub\TestIamPermissionsRequest;
use Google\Service\Pubsub\TestIamPermissionsResponse;
use Google\Service\Pubsub\ValidateMessageRequest;
use Google\Service\Pubsub\ValidateMessageResponse;
use Google\Service\Pubsub\ValidateSchemaRequest;
use Google\Service\Pubsub\ValidateSchemaResponse;

/**
 * The "schemas" collection of methods.
 * Typical usage is:
 *  <code>
 *   $pubsubService = new Google\Service\Pubsub(...);
 *   $schemas = $pubsubService->projects_schemas;
 *  </code>
 */
class ProjectsSchemas extends \Google\Service\Resource
{
  /**
   * Commits a new schema revision to an existing schema. (schemas.commit)
   *
   * @param string $name Required. The name of the schema we are revising. Format
   * is `projects/{project}/schemas/{schema}`.
   * @param CommitSchemaRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Schema
   * @throws \Google\Service\Exception
   */
  public function commit($name, CommitSchemaRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('commit', [$params], Schema::class);
  }
  /**
   * Creates a schema. (schemas.create)
   *
   * @param string $parent Required. The name of the project in which to create
   * the schema. Format is `projects/{project-id}`.
   * @param Schema $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string schemaId The ID to use for the schema, which will become
   * the final component of the schema's resource name. See
   * https://cloud.google.com/pubsub/docs/pubsub-basics#resource_names for
   * resource name constraints.
   * @return Schema
   * @throws \Google\Service\Exception
   */
  public function create($parent, Schema $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Schema::class);
  }
  /**
   * Deletes a schema. (schemas.delete)
   *
   * @param string $name Required. Name of the schema to delete. Format is
   * `projects/{project}/schemas/{schema}`.
   * @param array $optParams Optional parameters.
   * @return PubsubEmpty
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], PubsubEmpty::class);
  }
  /**
   * Deletes a specific schema revision. (schemas.deleteRevision)
   *
   * @param string $name Required. The name of the schema revision to be deleted,
   * with a revision ID explicitly included. Example: `projects/123/schemas/my-
   * schema@c7cfa2a8`
   * @param array $optParams Optional parameters.
   *
   * @opt_param string revisionId Optional. This field is deprecated and should
   * not be used for specifying the revision ID. The revision ID should be
   * specified via the `name` parameter.
   * @return Schema
   * @throws \Google\Service\Exception
   */
  public function deleteRevision($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('deleteRevision', [$params], Schema::class);
  }
  /**
   * Gets a schema. (schemas.get)
   *
   * @param string $name Required. The name of the schema to get. Format is
   * `projects/{project}/schemas/{schema}`.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string view The set of fields to return in the response. If not
   * set, returns a Schema with all fields filled out. Set to `BASIC` to omit the
   * `definition`.
   * @return Schema
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Schema::class);
  }
  /**
   * Gets the access control policy for a resource. Returns an empty policy if the
   * resource exists and does not have a policy set. (schemas.getIamPolicy)
   *
   * @param string $resource REQUIRED: The resource for which the policy is being
   * requested. See [Resource
   * names](https://cloud.google.com/apis/design/resource_names) for the
   * appropriate value for this field.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int options.requestedPolicyVersion Optional. The maximum policy
   * version that will be used to format the policy. Valid values are 0, 1, and 3.
   * Requests specifying an invalid value will be rejected. Requests for policies
   * with any conditional role bindings must specify version 3. Policies with no
   * conditional role bindings may specify any valid value or leave the field
   * unset. The policy in the response might use the policy version that you
   * specified, or it might use a lower policy version. For example, if you
   * specify version 3, but the policy has no conditional role bindings, the
   * response uses version 1. To learn which resources support conditions in their
   * IAM policies, see the [IAM
   * documentation](https://cloud.google.com/iam/help/conditions/resource-
   * policies).
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
   * Lists schemas in a project. (schemas.listProjectsSchemas)
   *
   * @param string $parent Required. The name of the project in which to list
   * schemas. Format is `projects/{project-id}`.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int pageSize Maximum number of schemas to return.
   * @opt_param string pageToken The value returned by the last
   * `ListSchemasResponse`; indicates that this is a continuation of a prior
   * `ListSchemas` call, and that the system should return the next page of data.
   * @opt_param string view The set of Schema fields to return in the response. If
   * not set, returns Schemas with `name` and `type`, but not `definition`. Set to
   * `FULL` to retrieve all fields.
   * @return ListSchemasResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsSchemas($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListSchemasResponse::class);
  }
  /**
   * Lists all schema revisions for the named schema. (schemas.listRevisions)
   *
   * @param string $name Required. The name of the schema to list revisions for.
   * @param array $optParams Optional parameters.
   *
   * @opt_param int pageSize The maximum number of revisions to return per page.
   * @opt_param string pageToken The page token, received from a previous
   * ListSchemaRevisions call. Provide this to retrieve the subsequent page.
   * @opt_param string view The set of Schema fields to return in the response. If
   * not set, returns Schemas with `name` and `type`, but not `definition`. Set to
   * `FULL` to retrieve all fields.
   * @return ListSchemaRevisionsResponse
   * @throws \Google\Service\Exception
   */
  public function listRevisions($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('listRevisions', [$params], ListSchemaRevisionsResponse::class);
  }
  /**
   * Creates a new schema revision that is a copy of the provided revision_id.
   * (schemas.rollback)
   *
   * @param string $name Required. The schema being rolled back with revision id.
   * @param RollbackSchemaRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Schema
   * @throws \Google\Service\Exception
   */
  public function rollback($name, RollbackSchemaRequest $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('rollback', [$params], Schema::class);
  }
  /**
   * Sets the access control policy on the specified resource. Replaces any
   * existing policy. Can return `NOT_FOUND`, `INVALID_ARGUMENT`, and
   * `PERMISSION_DENIED` errors. (schemas.setIamPolicy)
   *
   * @param string $resource REQUIRED: The resource for which the policy is being
   * specified. See [Resource
   * names](https://cloud.google.com/apis/design/resource_names) for the
   * appropriate value for this field.
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
   * Returns permissions that a caller has on the specified resource. If the
   * resource does not exist, this will return an empty set of permissions, not a
   * `NOT_FOUND` error. Note: This operation is designed to be used for building
   * permission-aware UIs and command-line tools, not for authorization checking.
   * This operation may "fail open" without warning. (schemas.testIamPermissions)
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
   * Validates a schema. (schemas.validate)
   *
   * @param string $parent Required. The name of the project in which to validate
   * schemas. Format is `projects/{project-id}`.
   * @param ValidateSchemaRequest $postBody
   * @param array $optParams Optional parameters.
   * @return ValidateSchemaResponse
   * @throws \Google\Service\Exception
   */
  public function validate($parent, ValidateSchemaRequest $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('validate', [$params], ValidateSchemaResponse::class);
  }
  /**
   * Validates a message against a schema. (schemas.validateMessage)
   *
   * @param string $parent Required. The name of the project in which to validate
   * schemas. Format is `projects/{project-id}`.
   * @param ValidateMessageRequest $postBody
   * @param array $optParams Optional parameters.
   * @return ValidateMessageResponse
   * @throws \Google\Service\Exception
   */
  public function validateMessage($parent, ValidateMessageRequest $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('validateMessage', [$params], ValidateMessageResponse::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsSchemas::class, 'Google_Service_Pubsub_Resource_ProjectsSchemas');
