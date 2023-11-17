<?php
// An example of using php-browserstacklocal

namespace BrowserStack;

require_once('vendor/autoload.php');

use BrowserStack\Local;
use BrowserStack\LocalException;

$me = new Local();
$me->isRunning();
echo "starting";
$args = array("-v" => 1);
$me->start($args);
echo "started";
echo $me->isRunning();
echo "stopping";
$me->stop();
echo "stopped";
echo $me->isRunning();
