<?php

namespace MintHCM\MintCLI\Commands;

use MintHCM\MintCLI\Services\ClearDirectoryService;
use MintHCM\MintCLI\Services\OAuth2RepairPermissionsService;
use MintHCM\MintCLI\Services\RepairAndRebuildJavascriptService;
use MintHCM\MintCLI\Services\RepairAndRebuildLegacyService;

if (! defined('sugarEntry')) {
    define('sugarEntry', true);
}

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RepairAndRebuild extends Command
{
    const API_CACHE_DIR = 'api/cache';
    protected static $defaultName = 'instance:rebuild';
    protected static $defaultDescription = 'Repair and Rebuild MintHCM Instance';

    protected function configure()
    {
        $this
            ->setHelp('This command will repair and rebuild MintHCM instance');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $previous_dir = getcwd();

        $io->title("Repairing and Rebuilding MintHCM Instance\n");

        $io->block("Rebuilding MintHCM instance...");
        chdir('legacy');
        (new RepairAndRebuildLegacyService())->run();

        $io->block("Rebuilding javascript...");
        (new RepairAndRebuildJavascriptService())->run();

        chdir($previous_dir);
        $io->block("Clearing api cache...");

        (new ClearDirectoryService())->run(static::API_CACHE_DIR);

        $io->block("Repairing oauth keys permissions...");
        (new OAuth2RepairPermissionsService())->repair();

        $io->success('The instance has been rebuilt successfully');
        return Command::SUCCESS;
    }
}
