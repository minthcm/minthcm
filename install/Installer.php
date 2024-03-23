<?php

use MintHCM\MintCLI\Installer\Installer as CLIInstaller;
use SuiteCRM\Search\ElasticSearch\ElasticSearchIndexer;

class Installer extends CLIInstaller
{
    const INSTANCE_DIR = '../legacy';
    const FRONTEND_DIR = '../vue';
    const CLI_DIR = '../legacy/MintCLI/src';
    const INSTALL_LOG_FILE = './install.log';
}
