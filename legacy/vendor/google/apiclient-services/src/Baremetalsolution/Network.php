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

namespace Google\Service\Baremetalsolution;

class Network extends \Google\Collection
{
  protected $collection_key = 'reservations';
  /**
   * @var string
   */
  public $cidr;
  /**
   * @var string
   */
  public $gatewayIp;
  /**
   * @var string
   */
  public $id;
  /**
   * @var string
   */
  public $ipAddress;
  /**
   * @var bool
   */
  public $jumboFramesEnabled;
  /**
   * @var string[]
   */
  public $labels;
  /**
   * @var string[]
   */
  public $macAddress;
  protected $mountPointsType = NetworkMountPoint::class;
  protected $mountPointsDataType = 'array';
  /**
   * @var string
   */
  public $name;
  /**
   * @var string
   */
  public $pod;
  protected $reservationsType = NetworkAddressReservation::class;
  protected $reservationsDataType = 'array';
  /**
   * @var string
   */
  public $servicesCidr;
  /**
   * @var string
   */
  public $state;
  /**
   * @var string
   */
  public $type;
  /**
   * @var string
   */
  public $vlanId;
  protected $vrfType = VRF::class;
  protected $vrfDataType = '';
  /**
   * @var string
   */
  public $vrfAttachment;

  /**
   * @param string
   */
  public function setCidr($cidr)
  {
    $this->cidr = $cidr;
  }
  /**
   * @return string
   */
  public function getCidr()
  {
    return $this->cidr;
  }
  /**
   * @param string
   */
  public function setGatewayIp($gatewayIp)
  {
    $this->gatewayIp = $gatewayIp;
  }
  /**
   * @return string
   */
  public function getGatewayIp()
  {
    return $this->gatewayIp;
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
  public function setIpAddress($ipAddress)
  {
    $this->ipAddress = $ipAddress;
  }
  /**
   * @return string
   */
  public function getIpAddress()
  {
    return $this->ipAddress;
  }
  /**
   * @param bool
   */
  public function setJumboFramesEnabled($jumboFramesEnabled)
  {
    $this->jumboFramesEnabled = $jumboFramesEnabled;
  }
  /**
   * @return bool
   */
  public function getJumboFramesEnabled()
  {
    return $this->jumboFramesEnabled;
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
  public function setMacAddress($macAddress)
  {
    $this->macAddress = $macAddress;
  }
  /**
   * @return string[]
   */
  public function getMacAddress()
  {
    return $this->macAddress;
  }
  /**
   * @param NetworkMountPoint[]
   */
  public function setMountPoints($mountPoints)
  {
    $this->mountPoints = $mountPoints;
  }
  /**
   * @return NetworkMountPoint[]
   */
  public function getMountPoints()
  {
    return $this->mountPoints;
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
   * @param string
   */
  public function setPod($pod)
  {
    $this->pod = $pod;
  }
  /**
   * @return string
   */
  public function getPod()
  {
    return $this->pod;
  }
  /**
   * @param NetworkAddressReservation[]
   */
  public function setReservations($reservations)
  {
    $this->reservations = $reservations;
  }
  /**
   * @return NetworkAddressReservation[]
   */
  public function getReservations()
  {
    return $this->reservations;
  }
  /**
   * @param string
   */
  public function setServicesCidr($servicesCidr)
  {
    $this->servicesCidr = $servicesCidr;
  }
  /**
   * @return string
   */
  public function getServicesCidr()
  {
    return $this->servicesCidr;
  }
  /**
   * @param string
   */
  public function setState($state)
  {
    $this->state = $state;
  }
  /**
   * @return string
   */
  public function getState()
  {
    return $this->state;
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
  /**
   * @param string
   */
  public function setVlanId($vlanId)
  {
    $this->vlanId = $vlanId;
  }
  /**
   * @return string
   */
  public function getVlanId()
  {
    return $this->vlanId;
  }
  /**
   * @param VRF
   */
  public function setVrf(VRF $vrf)
  {
    $this->vrf = $vrf;
  }
  /**
   * @return VRF
   */
  public function getVrf()
  {
    return $this->vrf;
  }
  /**
   * @param string
   */
  public function setVrfAttachment($vrfAttachment)
  {
    $this->vrfAttachment = $vrfAttachment;
  }
  /**
   * @return string
   */
  public function getVrfAttachment()
  {
    return $this->vrfAttachment;
  }
}

// Adding a class alias for backwards compatibility with the previous class name.
class_alias(Network::class, 'Google_Service_Baremetalsolution_Network');
