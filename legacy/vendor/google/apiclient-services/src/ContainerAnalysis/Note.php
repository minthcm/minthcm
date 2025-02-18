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

namespace Google\Service\ContainerAnalysis;

class Note extends \Google\Collection
{
  protected $collection_key = 'relatedUrl';
  protected $attestationType = AttestationNote::class;
  protected $attestationDataType = '';
  protected $buildType = BuildNote::class;
  protected $buildDataType = '';
  protected $complianceType = ComplianceNote::class;
  protected $complianceDataType = '';
  /**
   * @var string
   */
  public $createTime;
  protected $deploymentType = DeploymentNote::class;
  protected $deploymentDataType = '';
  protected $discoveryType = DiscoveryNote::class;
  protected $discoveryDataType = '';
  protected $dsseAttestationType = DSSEAttestationNote::class;
  protected $dsseAttestationDataType = '';
  /**
   * @var string
   */
  public $expirationTime;
  protected $imageType = ImageNote::class;
  protected $imageDataType = '';
  /**
   * @var string
   */
  public $kind;
  /**
   * @var string
   */
  public $longDescription;
  /**
   * @var string
   */
  public $name;
  protected $packageType = PackageNote::class;
  protected $packageDataType = '';
  /**
   * @var string[]
   */
  public $relatedNoteNames;
  protected $relatedUrlType = RelatedUrl::class;
  protected $relatedUrlDataType = 'array';
  protected $sbomReferenceType = SBOMReferenceNote::class;
  protected $sbomReferenceDataType = '';
  /**
   * @var string
   */
  public $shortDescription;
  /**
   * @var string
   */
  public $updateTime;
  protected $upgradeType = UpgradeNote::class;
  protected $upgradeDataType = '';
  protected $vulnerabilityType = VulnerabilityNote::class;
  protected $vulnerabilityDataType = '';
  protected $vulnerabilityAssessmentType = VulnerabilityAssessmentNote::class;
  protected $vulnerabilityAssessmentDataType = '';

  /**
   * @param AttestationNote
   */
  public function setAttestation(AttestationNote $attestation)
  {
    $this->attestation = $attestation;
  }
  /**
   * @return AttestationNote
   */
  public function getAttestation()
  {
    return $this->attestation;
  }
  /**
   * @param BuildNote
   */
  public function setBuild(BuildNote $build)
  {
    $this->build = $build;
  }
  /**
   * @return BuildNote
   */
  public function getBuild()
  {
    return $this->build;
  }
  /**
   * @param ComplianceNote
   */
  public function setCompliance(ComplianceNote $compliance)
  {
    $this->compliance = $compliance;
  }
  /**
   * @return ComplianceNote
   */
  public function getCompliance()
  {
    return $this->compliance;
  }
  /**
   * @param string
   */
  public function setCreateTime($createTime)
  {
    $this->createTime = $createTime;
  }
  /**
   * @return string
   */
  public function getCreateTime()
  {
    return $this->createTime;
  }
  /**
   * @param DeploymentNote
   */
  public function setDeployment(DeploymentNote $deployment)
  {
    $this->deployment = $deployment;
  }
  /**
   * @return DeploymentNote
   */
  public function getDeployment()
  {
    return $this->deployment;
  }
  /**
   * @param DiscoveryNote
   */
  public function setDiscovery(DiscoveryNote $discovery)
  {
    $this->discovery = $discovery;
  }
  /**
   * @return DiscoveryNote
   */
  public function getDiscovery()
  {
    return $this->discovery;
  }
  /**
   * @param DSSEAttestationNote
   */
  public function setDsseAttestation(DSSEAttestationNote $dsseAttestation)
  {
    $this->dsseAttestation = $dsseAttestation;
  }
  /**
   * @return DSSEAttestationNote
   */
  public function getDsseAttestation()
  {
    return $this->dsseAttestation;
  }
  /**
   * @param string
   */
  public function setExpirationTime($expirationTime)
  {
    $this->expirationTime = $expirationTime;
  }
  /**
   * @return string
   */
  public function getExpirationTime()
  {
    return $this->expirationTime;
  }
  /**
   * @param ImageNote
   */
  public function setImage(ImageNote $image)
  {
    $this->image = $image;
  }
  /**
   * @return ImageNote
   */
  public function getImage()
  {
    return $this->image;
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
  public function setLongDescription($longDescription)
  {
    $this->longDescription = $longDescription;
  }
  /**
   * @return string
   */
  public function getLongDescription()
  {
    return $this->longDescription;
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
   * @param PackageNote
   */
  public function setPackage(PackageNote $package)
  {
    $this->package = $package;
  }
  /**
   * @return PackageNote
   */
  public function getPackage()
  {
    return $this->package;
  }
  /**
   * @param string[]
   */
  public function setRelatedNoteNames($relatedNoteNames)
  {
    $this->relatedNoteNames = $relatedNoteNames;
  }
  /**
   * @return string[]
   */
  public function getRelatedNoteNames()
  {
    return $this->relatedNoteNames;
  }
  /**
   * @param RelatedUrl[]
   */
  public function setRelatedUrl($relatedUrl)
  {
    $this->relatedUrl = $relatedUrl;
  }
  /**
   * @return RelatedUrl[]
   */
  public function getRelatedUrl()
  {
    return $this->relatedUrl;
  }
  /**
   * @param SBOMReferenceNote
   */
  public function setSbomReference(SBOMReferenceNote $sbomReference)
  {
    $this->sbomReference = $sbomReference;
  }
  /**
   * @return SBOMReferenceNote
   */
  public function getSbomReference()
  {
    return $this->sbomReference;
  }
  /**
   * @param string
   */
  public function setShortDescription($shortDescription)
  {
    $this->shortDescription = $shortDescription;
  }
  /**
   * @return string
   */
  public function getShortDescription()
  {
    return $this->shortDescription;
  }
  /**
   * @param string
   */
  public function setUpdateTime($updateTime)
  {
    $this->updateTime = $updateTime;
  }
  /**
   * @return string
   */
  public function getUpdateTime()
  {
    return $this->updateTime;
  }
  /**
   * @param UpgradeNote
   */
  public function setUpgrade(UpgradeNote $upgrade)
  {
    $this->upgrade = $upgrade;
  }
  /**
   * @return UpgradeNote
   */
  public function getUpgrade()
  {
    return $this->upgrade;
  }
  /**
   * @param VulnerabilityNote
   */
  public function setVulnerability(VulnerabilityNote $vulnerability)
  {
    $this->vulnerability = $vulnerability;
  }
  /**
   * @return VulnerabilityNote
   */
  public function getVulnerability()
  {
    return $this->vulnerability;
  }
  /**
   * @param VulnerabilityAssessmentNote
   */
  public function setVulnerabilityAssessment(VulnerabilityAssessmentNote $vulnerabilityAssessment)
  {
    $this->vulnerabilityAssessment = $vulnerabilityAssessment;
  }
  /**
   * @return VulnerabilityAssessmentNote
   */
  public function getVulnerabilityAssessment()
  {
    return $this->vulnerabilityAssessment;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Note::class, 'Google_Service_ContainerAnalysis_Note');
