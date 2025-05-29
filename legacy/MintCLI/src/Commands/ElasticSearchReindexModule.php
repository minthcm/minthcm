<?php

namespace MintHCM\MintCLI\Commands;

if (! defined('sugarEntry')) {
    define('sugarEntry', true);
}

chdir('legacy/');
include 'vendor/autoload.php';
require_once 'include/utils/sugar_file_utils.php';
require_once 'include/utils/file_utils.php';
include_once 'include/database/DBManagerFactory.php';
include_once 'include/utils.php';
require_once 'include/entryPoint.php';
chdir('../');

error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);

use SuiteCRM\Search\ElasticSearch\ElasticSearchIndexer;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use MintHCM\MintCLI\Services\ElasticsearchService;
use Symfony\Component\Console\Style\SymfonyStyle;

class ElasticSearchReindexModule extends Command
{
    protected static $defaultName = 'elasticsearch:reindexModule';
    protected static $defaultDescription = 'Reindex selected ElasticSearch Module';

    protected function configure()
    {
        $this->setHelp('This command allows you to reindex record in selected module.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // $moduleService = new ModuleService();
        $ESService = new ElasticsearchService();

        $io->title("Reindexing ElasticSearch\n");

        $userData = $this->collectUserData($input, $output);
        $module_name = $userData['moduleName'];

        $output->writeln('');

        if (!$ESService->isModueEnabled($module_name)) {
            $io->error('Module does not exist in ElasticSearch.');
            return Command::FAILURE;
        }

        try {
            chdir('legacy/');
            $indexer = new ElasticSearchIndexer();
            $indexer->setModulesToIndex([$module_name]);
            $indexer->index();
            chdir('../');
        } catch (\Exception $e) {
            $io->error("There was an error during execution: " . $e . "\n");
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

    private function collectUserData($input, $output)
    {
        $QH = $this->getHelper('question');

        $question = new \MintHCM\MintCLI\Questions\ModuleName($QH, $input, $output);
        $module_name = $question->ask();

        return [
            'moduleName' => $module_name
        ];
    }
}
