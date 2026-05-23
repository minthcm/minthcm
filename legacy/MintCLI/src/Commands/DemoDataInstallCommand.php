<?php

namespace MintHCM\MintCLI\Commands;

require_once('legacy/install/DemoDataInstallation/Install/DemoDataInstall.php');
use DemoDataInstallation\Install\DemoDataInstall;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

use MintHCM\MintCLI\Services\DatabaseService;
use MintHCM\MintCLI\Services\DemoDataCommandService;

class DemoDataInstallCommand extends Command
{
    protected static $defaultName = 'demodata:install';
    protected static $defaultDescription = 'Demo Data Install';
    protected $io;
    protected $DDInstall;

    const DATE_TRESHOLD = '-3 days';
    const DEMO_DATA_STARTING_FILES_PATH = 'legacy/install/demo_data/files';
    const DEMO_DATA_DESTINATION_FILES_PATH = 'legacy/upload';

    protected function configure()
    {
        $this
            ->setHelp('This command allows you to install demo data from file to your system.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        
        $this->io = new SymfonyStyle($input, $output);

        $connection_detail = $this->getDatabaseConnection();
        if (!$connection_detail['status']) {
            $this->io->error($connection_detail['message']);
            return Command::FAILURE;
        }
        $this->DDInstall = new DemoDataInstall(
            $this->io, 
            new DemoDataCommandService(),
            $connection_detail['connection']
        );
        $this->io->section("Demo data is installing. It will take a while.");
        $this->installDemoData();
    
        $this->io->success('Demo Data has been installed successfuly');
        return Command::SUCCESS;
    }

    protected function getDatabaseConnection()
    {
        include 'legacy/config.php';
        include 'legacy/config_override.php';
        $DBService = new DatabaseService();
        return $DBService->getConnection($sugar_config['dbconfig']['db_host_name'], $sugar_config['dbconfig']['db_user_name'], $sugar_config['dbconfig']['db_password'], $sugar_config['dbconfig']['db_name'], $sugar_config['dbconfig']['db_port']);
    }

    protected function installDemoData()
    {
        $tables = $this->DDInstall->getTables();
        $step_count = count($tables);
        $this->io->progressStart($step_count);
        foreach ($tables as $table) {
            $this->io->progressAdvance();
            $this->DDInstall->installTable($table);
        }
        $this->DDInstall->installFiles(self::DEMO_DATA_STARTING_FILES_PATH, self::DEMO_DATA_DESTINATION_FILES_PATH);
    }

}