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

namespace Google\Service\Container\Resource;

use Google\Service\Container\CreateNodePoolRequest;
use Google\Service\Container\ListNodePoolsResponse;
use Google\Service\Container\NodePool;
use Google\Service\Container\Operation;
use Google\Service\Container\RollbackNodePoolUpgradeRequest;
use Google\Service\Container\SetNodePoolAutoscalingRequest;
use Google\Service\Container\SetNodePoolManagementRequest;
use Google\Service\Container\SetNodePoolSizeRequest;
use Google\Service\Container\UpdateNodePoolRequest;

/**
 * The "nodePools" collection of methods.
 * Typical usage is:
 *  <code>
 *   $containerService = new Google\Service\Container(...);
 *   $nodePools = $containerService->projects_zones_clusters_nodePools;
 *  </code>
 */
class ProjectsZonesClustersNodePools extends \Google\Service\Resource
{
  /**
   * Sets the autoscaling settings for the specified node pool.
   * (nodePools.autoscaling)
   *
   * @param string $projectId Deprecated. The Google Developers Console [project
   * ID or project number](https://cloud.google.com/resource-
   * manager/docs/creating-managing-projects). This field has been deprecated and
   * replaced by the name field.
   * @param string $zone Deprecated. The name of the Google Compute Engine
   * [zone](https://cloud.google.com/compute/docs/zones#available) in which the
   * cluster resides. This field has been deprecated and replaced by the name
   * field.
   * @param string $clusterId Deprecated. The name of the cluster to upgrade. This
   * field has been deprecated and replaced by the name field.
   * @param string $nodePoolId Deprecated. The name of the node pool to upgrade.
   * This field has been deprecated and replaced by the name field.
   * @param SetNodePoolAutoscalingRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function autoscaling($projectId, $zone, $clusterId, $nodePoolId, SetNodePoolAutoscalingRequest $postBody, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'zone' => $zone, 'clusterId' => $clusterId, 'nodePoolId' => $nodePoolId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('autoscaling', [$params], Operation::class);
  }
  /**
   * Creates a node pool for a cluster. (nodePools.create)
   *
   * @param string $projectId Deprecated. The Google Developers Console [project
   * ID or project number](https://cloud.google.com/resource-
   * manager/docs/creating-managing-projects). This field has been deprecated and
   * replaced by the parent field.
   * @param string $zone Deprecated. The name of the Google Compute Engine
   * [zone](https://cloud.google.com/compute/docs/zones#available) in which the
   * cluster resides. This field has been deprecated and replaced by the parent
   * field.
   * @param string $clusterId Deprecated. The name of the cluster. This field has
   * been deprecated and replaced by the parent field.
   * @param CreateNodePoolRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function create($projectId, $zone, $clusterId, CreateNodePoolRequest $postBody, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'zone' => $zone, 'clusterId' => $clusterId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Operation::class);
  }
  /**
   * Deletes a node pool from a cluster. (nodePools.delete)
   *
   * @param string $projectId Deprecated. The Google Developers Console [project
   * ID or project number](https://cloud.google.com/resource-
   * manager/docs/creating-managing-projects). This field has been deprecated and
   * replaced by the name field.
   * @param string $zone Deprecated. The name of the Google Compute Engine
   * [zone](https://cloud.google.com/compute/docs/zones#available) in which the
   * cluster resides. This field has been deprecated and replaced by the name
   * field.
   * @param string $clusterId Deprecated. The name of the cluster. This field has
   * been deprecated and replaced by the name field.
   * @param string $nodePoolId Deprecated. The name of the node pool to delete.
   * This field has been deprecated and replaced by the name field.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string name The name (project, location, cluster, node pool id) of
   * the node pool to delete. Specified in the format
   * `projects/locations/clusters/nodePools`.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function delete($projectId, $zone, $clusterId, $nodePoolId, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'zone' => $zone, 'clusterId' => $clusterId, 'nodePoolId' => $nodePoolId];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], Operation::class);
  }
  /**
   * Retrieves the requested node pool. (nodePools.get)
   *
   * @param string $projectId Deprecated. The Google Developers Console [project
   * ID or project number](https://cloud.google.com/resource-
   * manager/docs/creating-managing-projects). This field has been deprecated and
   * replaced by the name field.
   * @param string $zone Deprecated. The name of the Google Compute Engine
   * [zone](https://cloud.google.com/compute/docs/zones#available) in which the
   * cluster resides. This field has been deprecated and replaced by the name
   * field.
   * @param string $clusterId Deprecated. The name of the cluster. This field has
   * been deprecated and replaced by the name field.
   * @param string $nodePoolId Deprecated. The name of the node pool. This field
   * has been deprecated and replaced by the name field.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string name The name (project, location, cluster, node pool id) of
   * the node pool to get. Specified in the format
   * `projects/locations/clusters/nodePools`.
   * @return NodePool
   * @throws \Google\Service\Exception
   */
  public function get($projectId, $zone, $clusterId, $nodePoolId, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'zone' => $zone, 'clusterId' => $clusterId, 'nodePoolId' => $nodePoolId];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], NodePool::class);
  }
  /**
   * Lists the node pools for a cluster.
   * (nodePools.listProjectsZonesClustersNodePools)
   *
   * @param string $projectId Deprecated. The Google Developers Console [project
   * ID or project number](https://cloud.google.com/resource-
   * manager/docs/creating-managing-projects). This field has been deprecated and
   * replaced by the parent field.
   * @param string $zone Deprecated. The name of the Google Compute Engine
   * [zone](https://cloud.google.com/compute/docs/zones#available) in which the
   * cluster resides. This field has been deprecated and replaced by the parent
   * field.
   * @param string $clusterId Deprecated. The name of the cluster. This field has
   * been deprecated and replaced by the parent field.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string parent The parent (project, location, cluster name) where
   * the node pools will be listed. Specified in the format
   * `projects/locations/clusters`.
   * @return ListNodePoolsResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsZonesClustersNodePools($projectId, $zone, $clusterId, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'zone' => $zone, 'clusterId' => $clusterId];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListNodePoolsResponse::class);
  }
  /**
   * Rolls back a previously Aborted or Failed NodePool upgrade. This makes no
   * changes if the last upgrade successfully completed. (nodePools.rollback)
   *
   * @param string $projectId Deprecated. The Google Developers Console [project
   * ID or project number](https://cloud.google.com/resource-
   * manager/docs/creating-managing-projects). This field has been deprecated and
   * replaced by the name field.
   * @param string $zone Deprecated. The name of the Google Compute Engine
   * [zone](https://cloud.google.com/compute/docs/zones#available) in which the
   * cluster resides. This field has been deprecated and replaced by the name
   * field.
   * @param string $clusterId Deprecated. The name of the cluster to rollback.
   * This field has been deprecated and replaced by the name field.
   * @param string $nodePoolId Deprecated. The name of the node pool to rollback.
   * This field has been deprecated and replaced by the name field.
   * @param RollbackNodePoolUpgradeRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function rollback($projectId, $zone, $clusterId, $nodePoolId, RollbackNodePoolUpgradeRequest $postBody, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'zone' => $zone, 'clusterId' => $clusterId, 'nodePoolId' => $nodePoolId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('rollback', [$params], Operation::class);
  }
  /**
   * Sets the NodeManagement options for a node pool. (nodePools.setManagement)
   *
   * @param string $projectId Deprecated. The Google Developers Console [project
   * ID or project number](https://cloud.google.com/resource-
   * manager/docs/creating-managing-projects). This field has been deprecated and
   * replaced by the name field.
   * @param string $zone Deprecated. The name of the Google Compute Engine
   * [zone](https://cloud.google.com/compute/docs/zones#available) in which the
   * cluster resides. This field has been deprecated and replaced by the name
   * field.
   * @param string $clusterId Deprecated. The name of the cluster to update. This
   * field has been deprecated and replaced by the name field.
   * @param string $nodePoolId Deprecated. The name of the node pool to update.
   * This field has been deprecated and replaced by the name field.
   * @param SetNodePoolManagementRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function setManagement($projectId, $zone, $clusterId, $nodePoolId, SetNodePoolManagementRequest $postBody, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'zone' => $zone, 'clusterId' => $clusterId, 'nodePoolId' => $nodePoolId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('setManagement', [$params], Operation::class);
  }
  /**
   * Sets the size for a specific node pool. The new size will be used for all
   * replicas, including future replicas created by modifying NodePool.locations.
   * (nodePools.setSize)
   *
   * @param string $projectId Deprecated. The Google Developers Console [project
   * ID or project number](https://cloud.google.com/resource-
   * manager/docs/creating-managing-projects). This field has been deprecated and
   * replaced by the name field.
   * @param string $zone Deprecated. The name of the Google Compute Engine
   * [zone](https://cloud.google.com/compute/docs/zones#available) in which the
   * cluster resides. This field has been deprecated and replaced by the name
   * field.
   * @param string $clusterId Deprecated. The name of the cluster to update. This
   * field has been deprecated and replaced by the name field.
   * @param string $nodePoolId Deprecated. The name of the node pool to update.
   * This field has been deprecated and replaced by the name field.
   * @param SetNodePoolSizeRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function setSize($projectId, $zone, $clusterId, $nodePoolId, SetNodePoolSizeRequest $postBody, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'zone' => $zone, 'clusterId' => $clusterId, 'nodePoolId' => $nodePoolId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('setSize', [$params], Operation::class);
  }
  /**
   * Updates the version and/or image type for the specified node pool.
   * (nodePools.update)
   *
   * @param string $projectId Deprecated. The Google Developers Console [project
   * ID or project number](https://cloud.google.com/resource-
   * manager/docs/creating-managing-projects). This field has been deprecated and
   * replaced by the name field.
   * @param string $zone Deprecated. The name of the Google Compute Engine
   * [zone](https://cloud.google.com/compute/docs/zones#available) in which the
   * cluster resides. This field has been deprecated and replaced by the name
   * field.
   * @param string $clusterId Deprecated. The name of the cluster to upgrade. This
   * field has been deprecated and replaced by the name field.
   * @param string $nodePoolId Deprecated. The name of the node pool to upgrade.
   * This field has been deprecated and replaced by the name field.
   * @param UpdateNodePoolRequest $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function update($projectId, $zone, $clusterId, $nodePoolId, UpdateNodePoolRequest $postBody, $optParams = [])
  {
    $params = ['projectId' => $projectId, 'zone' => $zone, 'clusterId' => $clusterId, 'nodePoolId' => $nodePoolId, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('update', [$params], Operation::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsZonesClustersNodePools::class, 'Google_Service_Container_Resource_ProjectsZonesClustersNodePools');
