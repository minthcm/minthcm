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

namespace Google\Service\Drive;

class TeamDriveRestrictions extends \Google\Model
{
  /**
   * @var bool
   */
  public $adminManagedRestrictions;
  /**
   * @var bool
   */
  public $copyRequiresWriterPermission;
  /**
   * @var bool
   */
  public $domainUsersOnly;
  /**
   * @var bool
   */
  public $sharingFoldersRequiresOrganizerPermission;
  /**
   * @var bool
   */
  public $teamMembersOnly;

  /**
   * @param bool
   */
  public function setAdminManagedRestrictions($adminManagedRestrictions)
  {
    $this->adminManagedRestrictions = $adminManagedRestrictions;
  }
  /**
   * @return bool
   */
  public function getAdminManagedRestrictions()
  {
    return $this->adminManagedRestrictions;
  }
  /**
   * @param bool
   */
  public function setCopyRequiresWriterPermission($copyRequiresWriterPermission)
  {
    $this->copyRequiresWriterPermission = $copyRequiresWriterPermission;
  }
  /**
   * @return bool
   */
  public function getCopyRequiresWriterPermission()
  {
    return $this->copyRequiresWriterPermission;
  }
  /**
   * @param bool
   */
  public function setDomainUsersOnly($domainUsersOnly)
  {
    $this->domainUsersOnly = $domainUsersOnly;
  }
  /**
   * @return bool
   */
  public function getDomainUsersOnly()
  {
    return $this->domainUsersOnly;
  }
  /**
   * @param bool
   */
  public function setSharingFoldersRequiresOrganizerPermission($sharingFoldersRequiresOrganizerPermission)
  {
    $this->sharingFoldersRequiresOrganizerPermission = $sharingFoldersRequiresOrganizerPermission;
  }
  /**
   * @return bool
   */
  public function getSharingFoldersRequiresOrganizerPermission()
  {
    return $this->sharingFoldersRequiresOrganizerPermission;
  }
  /**
   * @param bool
   */
  public function setTeamMembersOnly($teamMembersOnly)
  {
    $this->teamMembersOnly = $teamMembersOnly;
  }
  /**
   * @return bool
   */
  public function getTeamMembersOnly()
  {
    return $this->teamMembersOnly;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(TeamDriveRestrictions::class, 'Google_Service_Drive_TeamDriveRestrictions');
