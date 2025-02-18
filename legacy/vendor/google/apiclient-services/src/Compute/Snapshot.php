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

class Snapshot extends \Google\Collection
{
  protected $collection_key = 'storageLocations';
  /**
   * @var string
   */
  public $architecture;
  /**
   * @var bool
   */
  public $autoCreated;
  /**
   * @var string
   */
  public $chainName;
  /**
   * @var string
   */
  public $creationSizeBytes;
  /**
   * @var string
   */
  public $creationTimestamp;
  /**
   * @var string
   */
  public $description;
  /**
   * @var string
   */
  public $diskSizeGb;
  /**
   * @var string
   */
  public $downloadBytes;
  /**
   * @var bool
   */
  public $enableConfidentialCompute;
  protected $guestOsFeaturesType = GuestOsFeature::class;
  protected $guestOsFeaturesDataType = 'array';
  /**
   * @var string
   */
  public $id;
  /**
   * @var string
   */
  public $kind;
  /**
   * @var string
   */
  public $labelFingerprint;
  /**
   * @var string[]
   */
  public $labels;
  /**
   * @var string[]
   */
  public $licenseCodes;
  /**
   * @var string[]
   */
  public $licenses;
  /**
   * @var string
   */
  public $locationHint;
  /**
   * @var string
   */
  public $name;
  /**
   * @var bool
   */
  public $satisfiesPzi;
  /**
   * @var bool
   */
  public $satisfiesPzs;
  /**
   * @var string
   */
  public $selfLink;
  protected $snapshotEncryptionKeyType = CustomerEncryptionKey::class;
  protected $snapshotEncryptionKeyDataType = '';
  /**
   * @var string
   */
  public $snapshotType;
  /**
   * @var string
   */
  public $sourceDisk;
  protected $sourceDiskEncryptionKeyType = CustomerEncryptionKey::class;
  protected $sourceDiskEncryptionKeyDataType = '';
  /**
   * @var string
   */
  public $sourceDiskForRecoveryCheckpoint;
  /**
   * @var string
   */
  public $sourceDiskId;
  /**
   * @var string
   */
  public $sourceInstantSnapshot;
  protected $sourceInstantSnapshotEncryptionKeyType = CustomerEncryptionKey::class;
  protected $sourceInstantSnapshotEncryptionKeyDataType = '';
  /**
   * @var string
   */
  public $sourceInstantSnapshotId;
  /**
   * @var string
   */
  public $sourceSnapshotSchedulePolicy;
  /**
   * @var string
   */
  public $sourceSnapshotSchedulePolicyId;
  /**
   * @var string
   */
  public $status;
  /**
   * @var string
   */
  public $storageBytes;
  /**
   * @var string
   */
  public $storageBytesStatus;
  /**
   * @var string[]
   */
  public $storageLocations;

  /**
   * @param string
   */
  public function setArchitecture($architecture)
  {
    $this->architecture = $architecture;
  }
  /**
   * @return string
   */
  public function getArchitecture()
  {
    return $this->architecture;
  }
  /**
   * @param bool
   */
  public function setAutoCreated($autoCreated)
  {
    $this->autoCreated = $autoCreated;
  }
  /**
   * @return bool
   */
  public function getAutoCreated()
  {
    return $this->autoCreated;
  }
  /**
   * @param string
   */
  public function setChainName($chainName)
  {
    $this->chainName = $chainName;
  }
  /**
   * @return string
   */
  public function getChainName()
  {
    return $this->chainName;
  }
  /**
   * @param string
   */
  public function setCreationSizeBytes($creationSizeBytes)
  {
    $this->creationSizeBytes = $creationSizeBytes;
  }
  /**
   * @return string
   */
  public function getCreationSizeBytes()
  {
    return $this->creationSizeBytes;
  }
  /**
   * @param string
   */
  public function setCreationTimestamp($creationTimestamp)
  {
    $this->creationTimestamp = $creationTimestamp;
  }
  /**
   * @return string
   */
  public function getCreationTimestamp()
  {
    return $this->creationTimestamp;
  }
  /**
   * @param string
   */
  public function setDescription($description)
  {
    $this->description = $description;
  }
  /**
   * @return string
   */
  public function getDescription()
  {
    return $this->description;
  }
  /**
   * @param string
   */
  public function setDiskSizeGb($diskSizeGb)
  {
    $this->diskSizeGb = $diskSizeGb;
  }
  /**
   * @return string
   */
  public function getDiskSizeGb()
  {
    return $this->diskSizeGb;
  }
  /**
   * @param string
   */
  public function setDownloadBytes($downloadBytes)
  {
    $this->downloadBytes = $downloadBytes;
  }
  /**
   * @return string
   */
  public function getDownloadBytes()
  {
    return $this->downloadBytes;
  }
  /**
   * @param bool
   */
  public function setEnableConfidentialCompute($enableConfidentialCompute)
  {
    $this->enableConfidentialCompute = $enableConfidentialCompute;
  }
  /**
   * @return bool
   */
  public function getEnableConfidentialCompute()
  {
    return $this->enableConfidentialCompute;
  }
  /**
   * @param GuestOsFeature[]
   */
  public function setGuestOsFeatures($guestOsFeatures)
  {
    $this->guestOsFeatures = $guestOsFeatures;
  }
  /**
   * @return GuestOsFeature[]
   */
  public function getGuestOsFeatures()
  {
    return $this->guestOsFeatures;
  }
  /**
   * @param string
   */
  public function setId($id)
  {
    $this->id = $id;
  }
  /**
   * @return string
   */
  public function getId()
  {
    return $this->id;
  }
  /**
   * @param string
   */
  public function setKind($kind)
  {
    $this->kind = $kind;
  }
  /**
   * @return string
   */
  public function getKind()
  {
    return $this->kind;
  }
  /**
   * @param string
   */
  public function setLabelFingerprint($labelFingerprint)
  {
    $this->labelFingerprint = $labelFingerprint;
  }
  /**
   * @return string
   */
  public function getLabelFingerprint()
  {
    return $this->labelFingerprint;
  }
  /**
   * @param string[]
   */
  public function setLabels($labels)
  {
    $this->labels = $labels;
  }
  /**
   * @return string[]
   */
  public function getLabels()
  {
    return $this->labels;
  }
  /**
   * @param string[]
   */
  public function setLicenseCodes($licenseCodes)
  {
    $this->licenseCodes = $licenseCodes;
  }
  /**
   * @return string[]
   */
  public function getLicenseCodes()
  {
    return $this->licenseCodes;
  }
  /**
   * @param string[]
   */
  public function setLicenses($licenses)
  {
    $this->licenses = $licenses;
  }
  /**
   * @return string[]
   */
  public function getLicenses()
  {
    return $this->licenses;
  }
  /**
   * @param string
   */
  public function setLocationHint($locationHint)
  {
    $this->locationHint = $locationHint;
  }
  /**
   * @return string
   */
  public function getLocationHint()
  {
    return $this->locationHint;
  }
  /**
   * @param string
   */
  public function setName($name)
  {
    $this->name = $name;
  }
  /**
   * @return string
   */
  public function getName()
  {
    return $this->name;
  }
  /**
   * @param bool
   */
  public function setSatisfiesPzi($satisfiesPzi)
  {
    $this->satisfiesPzi = $satisfiesPzi;
  }
  /**
   * @return bool
   */
  public function getSatisfiesPzi()
  {
    return $this->satisfiesPzi;
  }
  /**
   * @param bool
   */
  public function setSatisfiesPzs($satisfiesPzs)
  {
    $this->satisfiesPzs = $satisfiesPzs;
  }
  /**
   * @return bool
   */
  public function getSatisfiesPzs()
  {
    return $this->satisfiesPzs;
  }
  /**
   * @param string
   */
  public function setSelfLink($selfLink)
  {
    $this->selfLink = $selfLink;
  }
  /**
   * @return string
   */
  public function getSelfLink()
  {
    return $this->selfLink;
  }
  /**
   * @param CustomerEncryptionKey
   */
  public function setSnapshotEncryptionKey(CustomerEncryptionKey $snapshotEncryptionKey)
  {
    $this->snapshotEncryptionKey = $snapshotEncryptionKey;
  }
  /**
   * @return CustomerEncryptionKey
   */
  public function getSnapshotEncryptionKey()
  {
    return $this->snapshotEncryptionKey;
  }
  /**
   * @param string
   */
  public function setSnapshotType($snapshotType)
  {
    $this->snapshotType = $snapshotType;
  }
  /**
   * @return string
   */
  public function getSnapshotType()
  {
    return $this->snapshotType;
  }
  /**
   * @param string
   */
  public function setSourceDisk($sourceDisk)
  {
    $this->sourceDisk = $sourceDisk;
  }
  /**
   * @return string
   */
  public function getSourceDisk()
  {
    return $this->sourceDisk;
  }
  /**
   * @param CustomerEncryptionKey
   */
  public function setSourceDiskEncryptionKey(CustomerEncryptionKey $sourceDiskEncryptionKey)
  {
    $this->sourceDiskEncryptionKey = $sourceDiskEncryptionKey;
  }
  /**
   * @return CustomerEncryptionKey
   */
  public function getSourceDiskEncryptionKey()
  {
    return $this->sourceDiskEncryptionKey;
  }
  /**
   * @param string
   */
  public function setSourceDiskForRecoveryCheckpoint($sourceDiskForRecoveryCheckpoint)
  {
    $this->sourceDiskForRecoveryCheckpoint = $sourceDiskForRecoveryCheckpoint;
  }
  /**
   * @return string
   */
  public function getSourceDiskForRecoveryCheckpoint()
  {
    return $this->sourceDiskForRecoveryCheckpoint;
  }
  /**
   * @param string
   */
  public function setSourceDiskId($sourceDiskId)
  {
    $this->sourceDiskId = $sourceDiskId;
  }
  /**
   * @return string
   */
  public function getSourceDiskId()
  {
    return $this->sourceDiskId;
  }
  /**
   * @param string
   */
  public function setSourceInstantSnapshot($sourceInstantSnapshot)
  {
    $this->sourceInstantSnapshot = $sourceInstantSnapshot;
  }
  /**
   * @return string
   */
  public function getSourceInstantSnapshot()
  {
    return $this->sourceInstantSnapshot;
  }
  /**
   * @param CustomerEncryptionKey
   */
  public function setSourceInstantSnapshotEncryptionKey(CustomerEncryptionKey $sourceInstantSnapshotEncryptionKey)
  {
    $this->sourceInstantSnapshotEncryptionKey = $sourceInstantSnapshotEncryptionKey;
  }
  /**
   * @return CustomerEncryptionKey
   */
  public function getSourceInstantSnapshotEncryptionKey()
  {
    return $this->sourceInstantSnapshotEncryptionKey;
  }
  /**
   * @param string
   */
  public function setSourceInstantSnapshotId($sourceInstantSnapshotId)
  {
    $this->sourceInstantSnapshotId = $sourceInstantSnapshotId;
  }
  /**
   * @return string
   */
  public function getSourceInstantSnapshotId()
  {
    return $this->sourceInstantSnapshotId;
  }
  /**
   * @param string
   */
  public function setSourceSnapshotSchedulePolicy($sourceSnapshotSchedulePolicy)
  {
    $this->sourceSnapshotSchedulePolicy = $sourceSnapshotSchedulePolicy;
  }
  /**
   * @return string
   */
  public function getSourceSnapshotSchedulePolicy()
  {
    return $this->sourceSnapshotSchedulePolicy;
  }
  /**
   * @param string
   */
  public function setSourceSnapshotSchedulePolicyId($sourceSnapshotSchedulePolicyId)
  {
    $this->sourceSnapshotSchedulePolicyId = $sourceSnapshotSchedulePolicyId;
  }
  /**
   * @return string
   */
  public function getSourceSnapshotSchedulePolicyId()
  {
    return $this->sourceSnapshotSchedulePolicyId;
  }
  /**
   * @param string
   */
  public function setStatus($status)
  {
    $this->status = $status;
  }
  /**
   * @return string
   */
  public function getStatus()
  {
    return $this->status;
  }
  /**
   * @param string
   */
  public function setStorageBytes($storageBytes)
  {
    $this->storageBytes = $storageBytes;
  }
  /**
   * @return string
   */
  public function getStorageBytes()
  {
    return $this->storageBytes;
  }
  /**
   * @param string
   */
  public function setStorageBytesStatus($storageBytesStatus)
  {
    $this->storageBytesStatus = $storageBytesStatus;
  }
  /**
   * @return string
   */
  public function getStorageBytesStatus()
  {
    return $this->storageBytesStatus;
  }
  /**
   * @param string[]
   */
  public function setStorageLocations($storageLocations)
  {
    $this->storageLocations = $storageLocations;
  }
  /**
   * @return string[]
   */
  public function getStorageLocations()
  {
    return $this->storageLocations;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Snapshot::class, 'Google_Service_Compute_Snapshot');
