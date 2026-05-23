<?php 

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}
chdir('../legacy/');
require 'include/entryPoint.php';
chdir('../api/');

require __DIR__ . '/vendor/autoload.php';
global $test_login, $test_password;
$test_login = $_SERVER['argv'][1] ?? null;
$test_password =  $_SERVER['argv'][2] ?? null;

$_SERVER['argv'] = [
  './vendor/bin/phpunit',
];
$testFiles = glob('tests/*Test.php');
if (empty($testFiles)) {
    fwrite(STDERR, 'No test files found in tests directory.' . PHP_EOL);
    exit(1);
}
$_SERVER['argv'] = array_merge($_SERVER['argv'], $testFiles);

return include 'vendor/phpunit/phpunit/phpunit';