<?php

namespace Evolpe\EvolpeCLI\Commands;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Completion\CompletionInput;
use Symfony\Component\Console\Completion\CompletionSuggestions;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class Test extends Command
{
    protected static $defaultName = 'test';
    protected static $defaultDescription = 'Install SugarCRM system';

    public function __construct(bool $requirePassword = false)
    {
        $this->requirePassword = $requirePassword;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setHelp('This is just a test command...')
            ->addArgument('username', InputArgument::REQUIRED, 'The username of the user.')
            // ->addArgument('last_name', InputArgument::OPTIONAL, 'Your last name?')
            ->addOption('instance', 'i', InputOption::VALUE_REQUIRED, 'Instance name', null)
        ;
    }

    public function complete(CompletionInput $input, CompletionSuggestions $suggestions): void
    {
        if ($input->mustSuggestArgumentValuesFor('username')) {
            $currentValue = $input->getCompletionValue();
            $availableUsernames = [
                'abc',
                'def',
                'afh',
            ];

            $suggestions->suggestValues($availableUsernames);
        }
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        exec('./src/scripts/pull_git_repository.sh', $result, $status);

        $io = new SymfonyStyle($input, $output);
        $io->title('Installing SugarCRM instance');

        $io->section('Downloading instance ZIP file');

        $io->text('Lorem ipsum dolor sit amet');

        $io->listing([
            'Element #1 Lorem ipsum dolor sit amet',
            'Element #2 Lorem ipsum dolor sit amet',
            'Element #3 Lorem ipsum dolor sit amet',
        ]);

        // $io->progressStart(100);
        // sleep(10);
        // $io->progressAdvance(33);
        // sleep(10);
        // $io->progressAdvance(33);
        // sleep(10);
        // $io->progressAdvance(34);
        
        // $io->newLine();

        // $x = $io->ask('What?"');
        $io->success('Success');
        $io->info('Success');
        $io->warning('Success');
        $io->error('Success');
        return Command::SUCCESS;
    }
}
