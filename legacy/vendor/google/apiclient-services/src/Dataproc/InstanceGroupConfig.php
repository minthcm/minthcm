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

namespace Google\Service\Dataproc;

class InstanceGroupConfig extends \Google\Collection
{
  protected $collection_key = 'instanceReferences';
  protected $acceleratorsType = AcceleratorConfig::class;
  protected $acceleratorsDataType = 'array';
  protected $diskConfigType = DiskConfig::class;
  protected $diskConfigDataType = '';
  /**
   * @var string
   */
  public $imageUri;
  protected $instanceFlexibilityPolicyType = InstanceFlexibilityPolicy::class;
  protected $instanceFlexibilityPolicyDataType = '';
  /**
   * @var string[]
   */
  public $instanceNames;
  protected $instanceReferencesType = InstanceReference::class;
  protected $instanceReferencesDataType = 'array';
  /**
   * @var bool
   */
  public $isPreemptible;
  /**
   * @var string
   */
  public $machineTypeUri;
  protected $managedGroupConfigType = ManagedGroupConfig::class;
  protected $managedGroupConfigDataType = '';
  /**
   * @var string
   */
  public $minCpuPlatform;
  /**
   * @var int
   */
  public $minNumInstances;
  /**
   * @var int
   */
  public $numInstances;
  /**
   * @var string
   */
  public $preemptibility;
  protected $startupConfigType = StartupConfig::class;
  protected $startupConfigDataType = '';

  /**
   * @param AcceleratorConfig[]
   */
  public function setAccelerators($accelerators)
  {
    $this->accelerators = $accelerators;
  }
  /**
   * @return AcceleratorConfig[]
   */
  public function getAccelerators()
  {
    return $this->accelerators;
  }
  /**
   * @param DiskConfig
   */
  public function setDiskConfig(DiskConfig $diskConfig)
  {
    $this->diskConfig = $diskConfig;
  }
  /**
   * @return DiskConfig
   */
  public function getDiskConfig()
  {
    return $this->diskConfig;
  }
  /**
   * @param string
   */
  public function setImageUri($imageUri)
  {
    $this->imageUri = $imageUri;
  }
  /**
   * @return string
   */
  public function getImageUri()
  {
    return $this->imageUri;
  }
  /**
   * @param InstanceFlexibilityPolicy
   */
  public function setInstanceFlexibilityPolicy(InstanceFlexibilityPolicy $instanceFlexibilityPolicy)
  {
    $this->instanceFlexibilityPolicy = $instanceFlexibilityPolicy;
  }
  /**
   * @return InstanceFlexibilityPolicy
   */
  public function getInstanceFlexibilityPolicy()
  {
    return $this->instanceFlexibilityPolicy;
  }
  /**
   * @param string[]
   */
  public function setInstanceNames($instanceNames)
  {
    $this->instanceNames = $instanceNames;
  }
  /**
   * @return string[]
   */
  public function getInstanceNames()
  {
    return $this->instanceNames;
  }
  /**
   * @param InstanceReference[]
   */
  public function setInstanceReferences($instanceReferences)
  {
    $this->instanceReferences = $instanceReferences;
  }
  /**
   * @return InstanceReference[]
   */
  public function getInstanceReferences()
  {
    return $this->instanceReferences;
  }
  /**
   * @param bool
   */
  public function setIsPreemptible($isPreemptible)
  {
    $this->isPreemptible = $isPreemptible;
  }
  /**
   * @return bool
   */
  public function getIsPreemptible()
  {
    return $this->isPreemptible;
  }
  /**
   * @param string
   */
  public function setMachineTypeUri($machineTypeUri)
  {
    $this->machineTypeUri = $machineTypeUri;
  }
  /**
   * @return string
   */
  public function getMachineTypeUri()
  {
    return $this->machineTypeUri;
  }
  /**
   * @param ManagedGroupConfig
   */
  public function setManagedGroupConfig(ManagedGroupConfig $managedGroupConfig)
  {
    $this->managedGroupConfig = $managedGroupConfig;
  }
  /**
   * @return ManagedGroupConfig
   */
  public function getManagedGroupConfig()
  {
    return $this->managedGroupConfig;
  }
  /**
   * @param string
   */
  public function setMinCpuPlatform($minCpuPlatform)
  {
    $this->minCpuPlatform = $minCpuPlatform;
  }
  /**
   * @return string
   */
  public function getMinCpuPlatform()
  {
    return $this->minCpuPlatform;
  }
  /**
   * @param int
   */
  public function setMinNumInstances($minNumInstances)
  {
    $this->minNumInstances = $minNumInstances;
  }
  /**
   * @return int
   */
  public function getMinNumInstances()
  {
    return $this->minNumInstances;
  }
  /**
   * @param int
   */
  public function setNumInstances($numInstances)
  {
    $this->numInstances = $numInstances;
  }
  /**
   * @return int
   */
  public function getNumInstances()
  {
    return $this->numInstances;
  }
  /**
   * @param string
   */
  public function setPreemptibility($preemptibility)
  {
    $this->preemptibility = $preemptibility;
  }
  /**
   * @return string
   */
  public function getPreemptibility()
  {
    return $this->preemptibility;
  }
  /**
   * @param StartupConfig
   */
  public function setStartupConfig(StartupConfig $startupConfig)
  {
    $this->startupConfig = $startupConfig;
  }
  /**
   * @return StartupConfig
   */
  public function getStartupConfig()
  {
    return $this->startupConfig;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(InstanceGroupConfig::class, 'Google_Service_Dataproc_InstanceGroupConfig');
