<?php
/* * *******************************************************************************
* This file is part of KReporter. KReporter is an enhancement developed
* by aac services k.s.. All rights are (c) 2016 by aac services k.s.
*
* This Version of the KReporter is licensed software and may only be used in
* alignment with the License Agreement received with this Software.
* This Software is copyrighted and may not be further distributed without
* witten consent of aac services k.s.
*
* You can contact us at info@kreporter.org
******************************************************************************* */




if (!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');

$kreportColors = array(
    'default' => array(
        'name' => 'default KReporter Theme',
        'colors' => array(
            '#862C7E',
            '#99C21C',
            '#EA9150',
            '#81789E',
            '#353535'
        )
    ),
    'possible' => array(
        'name' => 'Possible',
        'colors' => array(
            '#F9F5F2',
            '#B1C5C3',
            '#DF822A',
            '#E3D39B',
            '#7E9249',
            '#7D8C89'
        )
    ),
    'twilight' => array(
        'name' => 'Twilight',
        'colors' => array(
            '#0052A3',
            '#48BCE7',
            '#00BEA1',
            '#D2D900',
            '#B7B300'
        )
    ),
    'polynesianparadise' => array(
        'name' => 'Polynesian Paradise',
        'colors' => array(
            '#E2EF79',
            '#BDD273',
            '#719126',
            '#76C4B6',
            '#419FA7',
            '#FFAE58',
            '#A94312',
            '#F578C8',
            '#B80360'
        )
    ),
    'mountainsunset' => array(
        'name' => 'Mountain Sunset',
        'colors' => array(
            '#6484B3',
            '#4D6388',
            '#4B5C63',
            '#915756',
            '#BC664F',
            '#FA984F',
            '#FEC864',
            '#FFE779'
        )
    ),
    'brightandneutral' => array(
        'name' => 'Bright & Neutral',
        'colors' => array(
            '#9AB854',
            '#09A4B8',
            '#F6273F',
            '#A7A7A0',
            '#454543'
        )
    ),
    'kmonov' => array(
        'name' => 'Monochrome KReporter Theme (violet)',
        'colors' => array(
            '#812C7A'
        )
    ),
    'kmonog' => array(
        'name' => 'Monochrome KReporter Theme (green)',
        'colors' => array(
            '#99c21c'
        )
    )
);

if(file_exists('custom/modules/KReports/config/KReportColors.php'))
    include('custom/modules/KReports/config/KReportColors.php');
