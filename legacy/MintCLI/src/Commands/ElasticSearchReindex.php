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
use Symfony\Component\Console\Style\SymfonyStyle;

class ElasticSearchReindex extends Command
{
    protected static $defaultName = 'elasticsearch:reindex';
    protected static $defaultDescription = 'Reindex ElasticSearch';

    protected function configure()
    {
        $this
            ->setHelp('This command allows you to reindex record in ElasticSearch.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        chdir('legacy/');
        $io = new SymfonyStyle($input, $output);

        $io->title("Reindexing ElasticSearch\n");

        try {
            $indexer = new ElasticSearchIndexer();
            $indexer->index();
            chdir('../');
        } catch (\Exception $e) {
            $io->error("There was an error during execution: " . $e . "\n");
            chdir('../');
            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }

}
