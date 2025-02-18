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

class InterconnectDiagnosticsLinkStatus extends \Google\Collection
{
  protected $collection_key = 'arpCaches';
  protected $arpCachesType = InterconnectDiagnosticsARPEntry::class;
  protected $arpCachesDataType = 'array';
  /**
   * @var string
   */
  public $circuitId;
  /**
   * @var string
   */
  public $googleDemarc;
  protected $lacpStatusType = InterconnectDiagnosticsLinkLACPStatus::class;
  protected $lacpStatusDataType = '';
  protected $macsecType = InterconnectDiagnosticsMacsecStatus::class;
  protected $macsecDataType = '';
  /**
   * @var string
   */
  public $operationalStatus;
  protected $receivingOpticalPowerType = InterconnectDiagnosticsLinkOpticalPower::class;
  protected $receivingOpticalPowerDataType = '';
  protected $transmittingOpticalPowerType = InterconnectDiagnosticsLinkOpticalPower::class;
  protected $transmittingOpticalPowerDataType = '';

  /**
   * @param InterconnectDiagnosticsARPEntry[]
   */
  public function setArpCaches($arpCaches)
  {
    $this->arpCaches = $arpCaches;
  }
  /**
   * @return InterconnectDiagnosticsARPEntry[]
   */
  public function getArpCaches()
  {
    return $this->arpCaches;
  }
  /**
   * @param string
   */
  public function setCircuitId($circuitId)
  {
    $this->circuitId = $circuitId;
  }
  /**
   * @return string
   */
  public function getCircuitId()
  {
    return $this->circuitId;
  }
  /**
   * @param string
   */
  public function setGoogleDemarc($googleDemarc)
  {
    $this->googleDemarc = $googleDemarc;
  }
  /**
   * @return string
   */
  public function getGoogleDemarc()
  {
    return $this->googleDemarc;
  }
  /**
   * @param InterconnectDiagnosticsLinkLACPStatus
   */
  public function setLacpStatus(InterconnectDiagnosticsLinkLACPStatus $lacpStatus)
  {
    $this->lacpStatus = $lacpStatus;
  }
  /**
   * @return InterconnectDiagnosticsLinkLACPStatus
   */
  public function getLacpStatus()
  {
    return $this->lacpStatus;
  }
  /**
   * @param InterconnectDiagnosticsMacsecStatus
   */
  public function setMacsec(InterconnectDiagnosticsMacsecStatus $macsec)
  {
    $this->macsec = $macsec;
  }
  /**
   * @return InterconnectDiagnosticsMacsecStatus
   */
  public function getMacsec()
  {
    return $this->macsec;
  }
  /**
   * @param string
   */
  public function setOperationalStatus($operationalStatus)
  {
    $this->operationalStatus = $operationalStatus;
  }
  /**
   * @return string
   */
  public function getOperationalStatus()
  {
    return $this->operationalStatus;
  }
  /**
   * @param InterconnectDiagnosticsLinkOpticalPower
   */
  public function setReceivingOpticalPower(InterconnectDiagnosticsLinkOpticalPower $receivingOpticalPower)
  {
    $this->receivingOpticalPower = $receivingOpticalPower;
  }
  /**
   * @return InterconnectDiagnosticsLinkOpticalPower
   */
  public function getReceivingOpticalPower()
  {
    return $this->receivingOpticalPower;
  }
  /**
   * @param InterconnectDiagnosticsLinkOpticalPower
   */
  public function setTransmittingOpticalPower(InterconnectDiagnosticsLinkOpticalPower $transmittingOpticalPower)
  {
    $this->transmittingOpticalPower = $transmittingOpticalPower;
  }
  /**
   * @return InterconnectDiagnosticsLinkOpticalPower
   */
  public function getTransmittingOpticalPower()
  {
    return $this->transmittingOpticalPower;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(InterconnectDiagnosticsLinkStatus::class, 'Google_Service_Compute_InterconnectDiagnosticsLinkStatus');
