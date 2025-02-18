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

class Change extends \Google\Model
{
  /**
   * @var string
   */
  public $changeType;
  protected $driveType = Drive::class;
  protected $driveDataType = '';
  /**
   * @var string
   */
  public $driveId;
  protected $fileType = DriveFile::class;
  protected $fileDataType = '';
  /**
   * @var string
   */
  public $fileId;
  /**
   * @var string
   */
  public $kind;
  /**
   * @var bool
   */
  public $removed;
  protected $teamDriveType = TeamDrive::class;
  protected $teamDriveDataType = '';
  /**
   * @var string
   */
  public $teamDriveId;
  /**
   * @var string
   */
  public $time;
  /**
   * @var string
   */
  public $type;

  /**
   * @param string
   */
  public function setChangeType($changeType)
  {
    $this->changeType = $changeType;
  }
  /**
   * @return string
   */
  public function getChangeType()
  {
    return $this->changeType;
  }
  /**
   * @param Drive
   */
  public function setDrive(Drive $drive)
  {
    $this->drive = $drive;
  }
  /**
   * @return Drive
   */
  public function getDrive()
  {
    return $this->drive;
  }
  /**
   * @param string
   */
  public function setDriveId($driveId)
  {
    $this->driveId = $driveId;
  }
  /**
   * @return string
   */
  public function getDriveId()
  {
    return $this->driveId;
  }
  /**
   * @param DriveFile
   */
  public function setFile(DriveFile $file)
  {
    $this->file = $file;
  }
  /**
   * @return DriveFile
   */
  public function getFile()
  {
    return $this->file;
  }
  /**
   * @param string
   */
  public function setFileId($fileId)
  {
    $this->fileId = $fileId;
  }
  /**
   * @return string
   */
  public function getFileId()
  {
    return $this->fileId;
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
   * @param bool
   */
  public function setRemoved($removed)
  {
    $this->removed = $removed;
  }
  /**
   * @return bool
   */
  public function getRemoved()
  {
    return $this->removed;
  }
  /**
   * @param TeamDrive
   */
  public function setTeamDrive(TeamDrive $teamDrive)
  {
    $this->teamDrive = $teamDrive;
  }
  /**
   * @return TeamDrive
   */
  public function getTeamDrive()
  {
    return $this->teamDrive;
  }
  /**
   * @param string
   */
  public function setTeamDriveId($teamDriveId)
  {
    $this->teamDriveId = $teamDriveId;
  }
  /**
   * @return string
   */
  public function getTeamDriveId()
  {
    return $this->teamDriveId;
  }
  /**
   * @param string
   */
  public function setTime($time)
  {
    $this->time = $time;
  }
  /**
   * @return string
   */
  public function getTime()
  {
    return $this->time;
  }
  /**
   * @param string
   */
  public function setType($type)
  {
    $this->type = $type;
  }
  /**
   * @return string
   */
  public function getType()
  {
    return $this->type;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Change::class, 'Google_Service_Drive_Change');
