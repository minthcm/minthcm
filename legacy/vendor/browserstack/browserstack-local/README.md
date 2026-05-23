# browserstack-local-php

[![Build Status](https://travis-ci.org/browserstack/browserstack-local-php.svg?branch=master)](https://travis-ci.org/browserstack/browserstack-local-php)

PHP bindings for BrowserStack Local.

## Installation

Installation is possible using [Composer](https://getcomposer.org/).

If you don't already use Composer, you can download the `composer.phar` binary:

    curl -sS https://getcomposer.org/installer | php

Then install the library:

    `php composer.phar require browserstack/local:dev-master`

Install all depenedencies:
    `php composer.phar install`

Test the installation by running a simple test file, check out example.php in the main repository. 

## Example

```
require_once('vendor/autoload.php');
use BrowserStack\Local;

#creates an instance of Local
$bs_local = new Local();

#replace <browserstack-accesskey> with your key. You can also set an environment variable - "BROWSERSTACK_ACCESS_KEY".
$bs_local_args = array("key" => "<browserstack-accesskey>");

#starts the Local instance with the required arguments
$bs_local->start(bs_local_args);

#check if BrowserStack local instance is running
echo $bs_local->isRunning();

#stop the Local instance
$bs_local->stop();

```

## Arguments

Apart from the key, all other BrowserStack Local modifiers are optional. For the full list of modifiers, refer [BrowserStack Local modifiers](https://www.browserstack.com/local-testing#modifiers). For examples, refer below -  

#### Verbose Logging
To enable verbose logging - 
```
$bs_local_args = array("key" => "<browserstack-accesskey>", "v" => true);
```

#### Folder Testing
To test local folder rather internal server, provide path to folder as value of this option - 
```
$bs_local_args = array("key" => "<browserstack-accesskey>", "f" => "/my/awesome/folder");
```

#### Force Start 
To kill other running Browserstack Local instances - 
```
$bs_local_args = array("key" => "<browserstack-accesskey>", "force" => true);
```

#### Only Automate
To disable local testing for Live and Screenshots, and enable only Automate - 
```
$bs_local_args = array("key" => "<browserstack-accesskey>", "onlyAutomate" => true);
```

#### Force Local
To route all traffic via local(your) machine - 
```
$bs_local_args = array("key" => "<browserstack-accesskey>", "forcelocal" => true);
```

#### Proxy
To use a proxy for local testing -  

* proxyHost: Hostname/IP of proxy, remaining proxy options are ignored if this option is absent
* proxyPort: Port for the proxy, defaults to 3128 when -proxyHost is used
* proxyUser: Username for connecting to proxy (Basic Auth Only)
* proxyPass: Password for USERNAME, will be ignored if USERNAME is empty or not specified

```
$bs_local_args = array("key" => "<browserstack-accesskey>", "proxyHost" => "127.0.0.1", "proxyPort" => "8000", "proxyUser" => "user", "proxyPass" => "password");
```

#### Local Identifier
If doing simultaneous multiple local testing connections, set this uniquely for different processes - 
```
$bs_local_args = array("key" => "<browserstack-accesskey>", "localIdentifier" => "randomstring");
```

## Additional Arguments

#### Binary Path

By default, BrowserStack local wrappers try downloading and executing the latest version of BrowserStack binary in ~/.browserstack or the present working directory or the tmp folder by order. But you can override these by passing the -binarypath argument.
Path to specify local Binary path -
```
$bs_local_args = array("key" => "<browserstack-accesskey>", "binarypath" => "/browserstack/BrowserStackLocal");
```

#### Logfile
To save the logs to the file while running with the '-v' argument, you can specify the path of the file. By default the logs are saved in the local.log file in the present woring directory. 
To specify the path to file where the logs will be saved - 
```
$bs_local_args = array("key" => "<browserstack-accesskey>", "logfile" => "/browserstack/logs.txt");
```

## Contribute

Testing is possible using [PHPUnit](https://phpunit.de/).

To run the tests, run the command: `phpunit`

### Reporting bugs

You can submit bug reports either in the Github issue tracker.

Before submitting an issue please check if there is already an existing issue. If there is, please add any additional information give it a "+1" in the comments.

When submitting an issue please describe the issue clearly, including how to reproduce the bug, which situations it appears in, what you expect to happen, what actually happens, and what platform (operating system and version) you are using.

### Pull Requests

We love pull requests! We are very happy to work with you to get your changes merged in, however, please keep the following in mind.

* Adhere to the coding conventions you see in the surrounding code.
* Include tests, and make sure all tests pass.
* Before submitting a pull-request, clean up the git history by going over your commits and squashing together minor changes and fixes into the corresponding commits. You can do this using the interactive rebase command.
