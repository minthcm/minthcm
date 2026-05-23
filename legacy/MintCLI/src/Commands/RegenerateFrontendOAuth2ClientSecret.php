<?php

namespace MintHCM\MintCLI\Commands;

if (! defined('sugarEntry')) {
    define('sugarEntry', true);
}

chdir('legacy/');
include 'vendor/autoload.php';
include_once 'include/utils.php';
require_once 'include/entryPoint.php';
chdir('../');

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RegenerateFrontendOAuth2ClientSecret extends Command
{
    protected static $defaultName = 'oauth2:regenerateClientSecret';
    protected static $defaultDescription = 'Regenerate Frontend OAuth2 Client Secret';

    protected function configure()
    {
        $this
            ->setHelp('This command add oauth2 client and create new or regenerate client secret for him.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $io->title("Regenerate Frontend OAuth2 Client Secret\n");

        try {
            if (!$this->checkKeysExists()) {
                $io->warning("
                    Private and public keys for OAuth2 not found. Please generate them first in /api/configs.\n
                    You can generate them by MintCLI command: ./MintCLI oauth2client:createKeys\n
                    Or you can use commands:\n
                    cd api/configs\n
                    openssl genrsa -out private.key 2048\n
                    openssl rsa -in private.key -pubout -out public.key\n
                    sudo chmod 600 private.key public.key\n
                    sudo chown www-data:www-data private.key public.key\n
                ");

            }

            $service = new \MintHCM\MintCLI\Services\OAuth2Service();
            if ($service->repairFrontendToken()) {
                $io->success('Createing or updating OAuth2 client was successful.');
                return Command::SUCCESS;
            }

            $io->error('There was an error during repairing Frontend OAuth2 client.');
            return Command::FAILURE;
        } catch (\Exception $e) {
            $io->error("There was an error during execution: " . $e . "\n");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function checkKeysExists(): bool
    {
        $key_dir = 'api/configs';
        return file_exists($key_dir . '/private.key') && file_exists($key_dir . '/public.key');
    }
}
