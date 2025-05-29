<?php
require_once('install/DemoDataInstallation/DemoDataInstallator.php');
use DemoDataInstallation\DemoDataInstallator;

$demoDataInstall = new DemoDataInstallator();
$demoDataInstall->run();
