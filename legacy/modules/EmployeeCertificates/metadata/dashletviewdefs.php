<?php
$dashletData['EmployeeCertificatesDashlet']['searchFields'] = array (
  'date_entered' => 
  array (
    'default' => '',
  ),
  'date_modified' => 
  array (
    'default' => '',
  ),
  'status' => 
  array (
    'default' => '',
  ),
  'start_date' => 
  array (
    'default' => '',
  ),
  'end_date' => 
  array (
    'default' => '',
  ),
);
$dashletData['EmployeeCertificatesDashlet']['columns'] = array (
  'name' => 
  array (
    'width' => '40%',
    'label' => 'LBL_NAME',
    'link' => true,
    'default' => true,
    'name' => 'name',
  ),
  'start_date' => 
  array (
    'label' => 'LBL_START_DATE',
    'type' => 'date',
    'width' => '10%',
    'default' => true,
  ),
  'end_date' => 
  array (
    'label' => 'LBL_END_DATE',
    'type' => 'date',
    'width' => '10%',
    'default' => true,
  ),
  'certificate_name' => 
  array (
    'type' => 'relate',
    'link' => true,
    'label' => 'LBL_CERTIFICATE_NAME',
    'id' => 'CERTIFICATE_ID',
    'width' => '10%',
    'default' => true,
  ),
  'attempts_number' => 
  array (
    'label' => 'LBL_ATTEMPTS_NUMBER',
    'width' => '10%',
    'default' => false,
  ),
  'points_scored' => 
  array (
    'label' => 'LBL_POINTS_SCORED',
    'width' => '10%',
    'default' => false,
  ),
);
