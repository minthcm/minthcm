<?php

if (!defined('sugarEntry')) {
    define('sugarEntry', true);
}

require 'InstallManager.php';
(new InstallManager)->run();
