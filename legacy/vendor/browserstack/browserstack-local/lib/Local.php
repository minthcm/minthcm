<?php

namespace BrowserStack;

use Exception;
use BrowserStack\LocalBinary;
use BrowserStack\LocalException;

error_reporting(1);

class Local {

  public $pid = NULL;
  
  public function __construct() {
    $this->key = getenv("BROWSERSTACK_ACCESS_KEY");
    $this->logfile = getcwd() . "/local.log";
    $this->user_args = array();
    $this->binary_path = "";
    $this->folder_flag = "";
    $this->folder_path = "";
    $this->force_local_flag = "";
    $this->local_identifier_flag = "";
    $this->only_flag = "";
    $this->only_automate_flag = "";
    $this->proxy_host = "";
    $this->proxy_port = "";
    $this->proxy_user = "";
    $this->proxy_pass = "";
    $this->force_proxy_flag = "";
    $this->force_flag = "";
    $this->verbose_flag = "";
    $this->hosts = "";
  }

  public function __destruct() {
  }

  public function isRunning() {
    if ($this->pid == NULL)
      return False;
    if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
    {
      $processes = explode( "\n", shell_exec( "tasklist.exe" ));
      foreach( $processes as $process )
      {
        if( strpos( "Image Name", $process ) === 0 || strpos( "===", $process ) === 0 )
          continue;
        $matches = false;
        preg_match( "/(.*?)\s+(\d+).*$/", $process, $matches );
        $this->pid = $matches[ 2 ];
        return True;
      }
      return False;
    }
    else {
      $return_message = shell_exec("ps -" . "$this->pid " . "| wc -l");
      if (intval($return_message) > 1)
      {
        return True;
      }
      return False;
    }
  }

  public function add_args($arg_key, $value = NULL) {
    if ($arg_key == "key")
      $this->key = $value;
    elseif ($arg_key == "binaryPath")
      $this->binary_path = $value;
    elseif ($arg_key == "logfile")
      $this->logfile = $value;
    elseif ($arg_key == "v")
      $this->verbose_flag = "-vvv";
    elseif ($arg_key == "force")
      $this->force_flag = "-force";
    elseif ($arg_key == "only")
      $this->only_flag = "-only";
    elseif ($arg_key == "onlyAutomate")
      $this->only_automate_flag = "-onlyAutomate";
    elseif ($arg_key == "forcelocal")
      $this->force_local_flag = "-forcelocal";
    elseif ($arg_key == "localIdentifier")
      $this->local_identifier_flag = "-localIdentifier $value";
    elseif ($arg_key == "proxyHost")
      $this->proxy_host = "-proxyHost $value";
    elseif ($arg_key == "proxyPort")
      $this->proxy_port = "-proxyPort $value";
    elseif ($arg_key == "proxyUser")
      $this->proxy_user = "-proxyUser $value";
    elseif ($arg_key == "proxyPass")
      $this->proxy_pass = "-proxyPass $value";
    elseif ($arg_key == "forceproxy")
      $this->force_proxy_flag = "-forceproxy";
    elseif ($arg_key == "hosts")
      $this->hosts = $value;
    elseif ($arg_key == "f") {
      $this->folder_flag = "-f";
      $this->folder_path = $value;
    }
    elseif (strtolower($value) == "true"){
      array_push($this->user_args, "-$arg_key");
    }
    else {
      array_push($this->user_args, "-$arg_key '$value'");
    }
  }

  public function start($arguments) {
    foreach($arguments as $key => $value)
      $this->add_args($key,$value);

    $this->binary = new LocalBinary();
    $this->binary_path = $this->binary->binary_path();
    
    $call = $this->start_command();
    if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
      system('echo "" > '. '$this->logfile');
    else
      system("echo \"\" > '$this->logfile' ");
    $call = $call . "2>&1";
    $return_message = shell_exec($call);
    $data = json_decode($return_message,true);
    if ($data["state"] != "connected") {
      throw new LocalException($data['message']['message']);
    }
    $this->pid = $data['pid'];
  }

  public function stop() {
    if(!$this->pid) return;
    $call = $this->stop_command();
    shell_exec("$call");
    $this->pid = null;
  }

  public function start_command() {
    $exec = "exec";
    // TODO to test on windows
    if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
      $exec = "call";

    $user_args = join(' ', $this->user_args);
    $command = "$exec $this->binary_path -d start -logFile '$this->logfile' $this->folder_flag $this->key $this->folder_path $this->force_local_flag $this->local_identifier_flag $this->only_flag $this->only_automate_flag $this->proxy_host $this->proxy_port $this->proxy_user $this->proxy_pass $this->force_proxy_flag $this->force_flag $this->verbose_flag $this->hosts $user_args";
    $command = preg_replace('/\s+/S', " ", $command);
    return $command;
  }

  public function stop_command() {
    $exec = "exec";
    // TODO to test on windows
    if(strtoupper(substr(PHP_OS, 0, 3)) === 'WIN')
      $exec = "call";

    $user_args = join(' ', $this->user_args);
    $command = "$exec $this->binary_path -d stop $this->local_identifier_flag";
    $command = preg_replace('/\s+/S', " ", $command);
    return $command;
  }

}

?>
