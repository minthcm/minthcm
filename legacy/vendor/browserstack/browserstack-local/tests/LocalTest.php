<?php

namespace BrowserStack;

use BrowserStack\Local;
use BrowserStack\LocalBinary;
use BrowserStack\LocalException;

require_once __DIR__ . '/../vendor/autoload.php';

class LocalTest extends \PHPUnit_Framework_TestCase {

  private $bs_local;

  public function setUp(){
    $this->bs_local = new Local();
  }

  public function tearDown(){
    $this->bs_local->stop();
  }

  public function test_verbose() {
    $this->bs_local->add_args('v');
    $this->assertContains('-v',$this->bs_local->start_command());
  }

  public function test_set_folder() {
    $this->bs_local->add_args('f', "/");
    $this->assertContains('-f',$this->bs_local->start_command());
    $this->assertContains('/',$this->bs_local->start_command());
  }

  public function test_enable_force() {
    $this->bs_local->add_args("force");
  }

  public function test_set_local_identifier() {
    $this->bs_local->add_args("localIdentifier", "randomString");
    $this->assertContains('-localIdentifier randomString',$this->bs_local->start_command());
  }

  public function test_enable_only() {
    $this->bs_local->add_args("only");
    $this->assertContains('-only',$this->bs_local->start_command());
  }

  public function test_enable_only_automate() {
    $this->bs_local->add_args("onlyAutomate");
    $this->assertContains('-onlyAutomate', $this->bs_local->start_command()); 
  }

  public function test_enable_force_local() {
    $this->bs_local->add_args("forcelocal");
    $this->assertContains('-forcelocal',$this->bs_local->start_command());
  }

  public function test_custom_boolean_argument() {
    $this->bs_local->add_args("boolArg1", true);
    $this->bs_local->add_args("boolArg2", true);
    $this->assertContains('-boolArg1',$this->bs_local->start_command());
    $this->assertContains('-boolArg2',$this->bs_local->start_command());
  }

  public function test_custom_keyval() {
    $this->bs_local->add_args("customKey1", "custom value1");
    $this->bs_local->add_args("customKey2", "custom value2");
    $this->assertContains('-customKey1 \'custom value1\'',$this->bs_local->start_command());
    $this->assertContains('-customKey2 \'custom value2\'',$this->bs_local->start_command());
  }

  public function test_set_proxy() {
    $this->bs_local->add_args("proxyHost", "localhost");
    $this->bs_local->add_args("proxyPort", 8080);
    $this->bs_local->add_args("proxyUser", "user");
    $this->bs_local->add_args("proxyPass", "pass");
    $this->assertContains('-proxyHost localhost -proxyPort 8080 -proxyUser user -proxyPass pass',$this->bs_local->start_command());
  }

  public function test_enable_force_proxy() {
    $this->bs_local->add_args("-forceproxy");
    $this->assertContains('-forceproxy',$this->bs_local->start_command());
  }

  public function test_hosts() {
    $this->bs_local->add_args("-hosts", "localhost,8080,0");
    $this->assertContains('localhost,8080,0',$this->bs_local->start_command());
  }

  public function test_isRunning() {
    $this->assertFalse($this->bs_local->isRunning());
    $this->bs_local->start(array('v' => true));
    $this->assertTrue($this->bs_local->isRunning());
    $this->bs_local->stop();
    $this->assertFalse($this->bs_local->isRunning());
    $this->bs_local->start(array('v' => true));
    $this->assertTrue($this->bs_local->isRunning());
  }

  public function test_checkPid() {
    $this->assertFalse($this->bs_local->isRunning());
    $this->bs_local->start(array('v' => true));
    $this->assertTrue($this->bs_local->pid > 0);
  }

  public function test_multiple_binary() {
    $this->bs_local->start(array('v' => true));
    $bs_local_2 = new Local();  
    $log_file2 = getcwd(). '/log2.log';
    print($log_file2);
    try {
      $bs_local_2->start(array('v' => true, 'logfile' => $log_file2));
      $this->fail("Expected Exception has not been raised.");
    } catch (LocalException $ex) {
      $emessage = $ex->getMessage();
      $this->assertEquals(trim($emessage), 'Either another browserstack local client is running on your machine or some server is listening on port 45691');
      unlink($log_file2);
      return;
    }
  }
}
