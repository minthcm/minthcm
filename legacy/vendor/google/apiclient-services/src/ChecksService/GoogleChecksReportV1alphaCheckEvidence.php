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

namespace Google\Service\ChecksService;

class GoogleChecksReportV1alphaCheckEvidence extends \Google\Collection
{
  protected $collection_key = 'sdks';
  protected $dataSecurityType = GoogleChecksReportV1alphaCheckDataSecurityEvidence::class;
  protected $dataSecurityDataType = '';
  protected $dataTypesType = GoogleChecksReportV1alphaCheckDataTypeEvidence::class;
  protected $dataTypesDataType = 'array';
  protected $endpointRestrictionViolationsType = GoogleChecksReportV1alphaCheckEndpointRestrictionViolationEvidence::class;
  protected $endpointRestrictionViolationsDataType = 'array';
  protected $endpointsType = GoogleChecksReportV1alphaCheckEndpointEvidence::class;
  protected $endpointsDataType = 'array';
  protected $permissionRestrictionViolationsType = GoogleChecksReportV1alphaCheckPermissionRestrictionViolationEvidence::class;
  protected $permissionRestrictionViolationsDataType = 'array';
  protected $permissionsType = GoogleChecksReportV1alphaCheckPermissionEvidence::class;
  protected $permissionsDataType = 'array';
  protected $privacyPolicyTextsType = GoogleChecksReportV1alphaCheckPrivacyPolicyTextEvidence::class;
  protected $privacyPolicyTextsDataType = 'array';
  protected $sdkIssuesType = GoogleChecksReportV1alphaCheckSdkIssueEvidence::class;
  protected $sdkIssuesDataType = 'array';
  protected $sdkRestrictionViolationsType = GoogleChecksReportV1alphaCheckSdkRestrictionViolationEvidence::class;
  protected $sdkRestrictionViolationsDataType = 'array';
  protected $sdksType = GoogleChecksReportV1alphaCheckSdkEvidence::class;
  protected $sdksDataType = 'array';

  /**
   * @param GoogleChecksReportV1alphaCheckDataSecurityEvidence
   */
  public function setDataSecurity(GoogleChecksReportV1alphaCheckDataSecurityEvidence $dataSecurity)
  {
    $this->dataSecurity = $dataSecurity;
  }
  /**
   * @return GoogleChecksReportV1alphaCheckDataSecurityEvidence
   */
  public function getDataSecurity()
  {
    return $this->dataSecurity;
  }
  /**
   * @param GoogleChecksReportV1alphaCheckDataTypeEvidence[]
   */
  public function setDataTypes($dataTypes)
  {
    $this->dataTypes = $dataTypes;
  }
  /**
   * @return GoogleChecksReportV1alphaCheckDataTypeEvidence[]
   */
  public function getDataTypes()
  {
    return $this->dataTypes;
  }
  /**
   * @param GoogleChecksReportV1alphaCheckEndpointRestrictionViolationEvidence[]
   */
  public function setEndpointRestrictionViolations($endpointRestrictionViolations)
  {
    $this->endpointRestrictionViolations = $endpointRestrictionViolations;
  }
  /**
   * @return GoogleChecksReportV1alphaCheckEndpointRestrictionViolationEvidence[]
   */
  public function getEndpointRestrictionViolations()
  {
    return $this->endpointRestrictionViolations;
  }
  /**
   * @param GoogleChecksReportV1alphaCheckEndpointEvidence[]
   */
  public function setEndpoints($endpoints)
  {
    $this->endpoints = $endpoints;
  }
  /**
   * @return GoogleChecksReportV1alphaCheckEndpointEvidence[]
   */
  public function getEndpoints()
  {
    return $this->endpoints;
  }
  /**
   * @param GoogleChecksReportV1alphaCheckPermissionRestrictionViolationEvidence[]
   */
  public function setPermissionRestrictionViolations($permissionRestrictionViolations)
  {
    $this->permissionRestrictionViolations = $permissionRestrictionViolations;
  }
  /**
   * @return GoogleChecksReportV1alphaCheckPermissionRestrictionViolationEvidence[]
   */
  public function getPermissionRestrictionViolations()
  {
    return $this->permissionRestrictionViolations;
  }
  /**
   * @param GoogleChecksReportV1alphaCheckPermissionEvidence[]
   */
  public function setPermissions($permissions)
  {
    $this->permissions = $permissions;
  }
  /**
   * @return GoogleChecksReportV1alphaCheckPermissionEvidence[]
   */
  public function getPermissions()
  {
    return $this->permissions;
  }
  /**
   * @param GoogleChecksReportV1alphaCheckPrivacyPolicyTextEvidence[]
   */
  public function setPrivacyPolicyTexts($privacyPolicyTexts)
  {
    $this->privacyPolicyTexts = $privacyPolicyTexts;
  }
  /**
   * @return GoogleChecksReportV1alphaCheckPrivacyPolicyTextEvidence[]
   */
  public function getPrivacyPolicyTexts()
  {
    return $this->privacyPolicyTexts;
  }
  /**
   * @param GoogleChecksReportV1alphaCheckSdkIssueEvidence[]
   */
  public function setSdkIssues($sdkIssues)
  {
    $this->sdkIssues = $sdkIssues;
  }
  /**
   * @return GoogleChecksReportV1alphaCheckSdkIssueEvidence[]
   */
  public function getSdkIssues()
  {
    return $this->sdkIssues;
  }
  /**
   * @param GoogleChecksReportV1alphaCheckSdkRestrictionViolationEvidence[]
   */
  public function setSdkRestrictionViolations($sdkRestrictionViolations)
  {
    $this->sdkRestrictionViolations = $sdkRestrictionViolations;
  }
  /**
   * @return GoogleChecksReportV1alphaCheckSdkRestrictionViolationEvidence[]
   */
  public function getSdkRestrictionViolations()
  {
    return $this->sdkRestrictionViolations;
  }
  /**
   * @param GoogleChecksReportV1alphaCheckSdkEvidence[]
   */
  public function setSdks($sdks)
  {
    $this->sdks = $sdks;
  }
  /**
   * @return GoogleChecksReportV1alphaCheckSdkEvidence[]
   */
  public function getSdks()
  {
    return $this->sdks;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(GoogleChecksReportV1alphaCheckEvidence::class, 'Google_Service_ChecksService_GoogleChecksReportV1alphaCheckEvidence');
