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

namespace Google\Service\BigtableAdmin\Resource;

use Google\Service\BigtableAdmin\BigtableadminEmpty;
use Google\Service\BigtableAdmin\Cluster;
use Google\Service\BigtableAdmin\ListClustersResponse;
use Google\Service\BigtableAdmin\Operation;

/**
 * The "clusters" collection of methods.
 * Typical usage is:
 *  <code>
 *   $bigtableadminService = new Google\Service\BigtableAdmin(...);
 *   $clusters = $bigtableadminService->projects_instances_clusters;
 *  </code>
 */
class ProjectsInstancesClusters extends \Google\Service\Resource
{
  /**
   * Creates a cluster within an instance. Note that exactly one of
   * Cluster.serve_nodes and Cluster.cluster_config.cluster_autoscaling_config can
   * be set. If serve_nodes is set to non-zero, then the cluster is manually
   * scaled. If cluster_config.cluster_autoscaling_config is non-empty, then
   * autoscaling is enabled. (clusters.create)
   *
   * @param string $parent Required. The unique name of the instance in which to
   * create the new cluster. Values are of the form
   * `projects/{project}/instances/{instance}`.
   * @param Cluster $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string clusterId Required. The ID to be used when referring to the
   * new cluster within its instance, e.g., just `mycluster` rather than
   * `projects/myproject/instances/myinstance/clusters/mycluster`.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function create($parent, Cluster $postBody, $optParams = [])
  {
    $params = ['parent' => $parent, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('create', [$params], Operation::class);
  }
  /**
   * Deletes a cluster from an instance. (clusters.delete)
   *
   * @param string $name Required. The unique name of the cluster to be deleted.
   * Values are of the form
   * `projects/{project}/instances/{instance}/clusters/{cluster}`.
   * @param array $optParams Optional parameters.
   * @return BigtableadminEmpty
   * @throws \Google\Service\Exception
   */
  public function delete($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('delete', [$params], BigtableadminEmpty::class);
  }
  /**
   * Gets information about a cluster. (clusters.get)
   *
   * @param string $name Required. The unique name of the requested cluster.
   * Values are of the form
   * `projects/{project}/instances/{instance}/clusters/{cluster}`.
   * @param array $optParams Optional parameters.
   * @return Cluster
   * @throws \Google\Service\Exception
   */
  public function get($name, $optParams = [])
  {
    $params = ['name' => $name];
    $params = array_merge($params, $optParams);
    return $this->call('get', [$params], Cluster::class);
  }
  /**
   * Lists information about clusters in an instance.
   * (clusters.listProjectsInstancesClusters)
   *
   * @param string $parent Required. The unique name of the instance for which a
   * list of clusters is requested. Values are of the form
   * `projects/{project}/instances/{instance}`. Use `{instance} = '-'` to list
   * Clusters for all Instances in a project, e.g.,
   * `projects/myproject/instances/-`.
   * @param array $optParams Optional parameters.
   *
   * @opt_param string pageToken DEPRECATED: This field is unused and ignored.
   * @return ListClustersResponse
   * @throws \Google\Service\Exception
   */
  public function listProjectsInstancesClusters($parent, $optParams = [])
  {
    $params = ['parent' => $parent];
    $params = array_merge($params, $optParams);
    return $this->call('list', [$params], ListClustersResponse::class);
  }
  /**
   * Partially updates a cluster within a project. This method is the preferred
   * way to update a Cluster. To enable and update autoscaling, set
   * cluster_config.cluster_autoscaling_config. When autoscaling is enabled,
   * serve_nodes is treated as an OUTPUT_ONLY field, meaning that updates to it
   * are ignored. Note that an update cannot simultaneously set serve_nodes to
   * non-zero and cluster_config.cluster_autoscaling_config to non-empty, and also
   * specify both in the update_mask. To disable autoscaling, clear
   * cluster_config.cluster_autoscaling_config, and explicitly set a serve_node
   * count via the update_mask. (clusters.partialUpdateCluster)
   *
   * @param string $name The unique name of the cluster. Values are of the form
   * `projects/{project}/instances/{instance}/clusters/a-z*`.
   * @param Cluster $postBody
   * @param array $optParams Optional parameters.
   *
   * @opt_param string updateMask Required. The subset of Cluster fields which
   * should be replaced.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function partialUpdateCluster($name, Cluster $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('partialUpdateCluster', [$params], Operation::class);
  }
  /**
   * Updates a cluster within an instance. Note that UpdateCluster does not
   * support updating cluster_config.cluster_autoscaling_config. In order to
   * update it, you must use PartialUpdateCluster. (clusters.update)
   *
   * @param string $name The unique name of the cluster. Values are of the form
   * `projects/{project}/instances/{instance}/clusters/a-z*`.
   * @param Cluster $postBody
   * @param array $optParams Optional parameters.
   * @return Operation
   * @throws \Google\Service\Exception
   */
  public function update($name, Cluster $postBody, $optParams = [])
  {
    $params = ['name' => $name, 'postBody' => $postBody];
    $params = array_merge($params, $optParams);
    return $this->call('update', [$params], Operation::class);
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(ProjectsInstancesClusters::class, 'Google_Service_BigtableAdmin_Resource_ProjectsInstancesClusters');
