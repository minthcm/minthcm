<?php

namespace MintHCM\MintCLI\Commands;

if (! defined('sugarEntry')) {
    define('sugarEntry', true);
}

chdir('legacy/');
include 'vendor/autoload.php';
require_once 'include/entryPoint.php';
chdir('../');

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Process\Process;

class CreateOAuth2Keys extends Command
{
    protected static $defaultName = 'oauth2:createKeys';
    protected static $defaultDescription = 'Create OAuth2 Keys';

    protected function configure()
    {
        $this
            ->setHelp('This command add oauth2 public and private key.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title("Create new OAuth2 keys\n");
        try {
            $service = new \MintHCM\MintCLI\Services\OAuth2Service();
            $service->generateNewKeys();
            $io->success('Createing new OAuth2 keys was successful.');
        } catch (\Exception $e) {
            $io->error("There was an error during execution: " . $e . "\n");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
