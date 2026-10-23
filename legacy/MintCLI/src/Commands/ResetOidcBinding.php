<?php

namespace MintHCM\MintCLI\Commands;

require_once __DIR__ . '/../../../modules/Users/authentication/OIDCAuthenticate/OIDCSubjectBinder.php';

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * Removes OIDC subject bindings (#193558), e.g. after switching to another IdP.
 * The next OIDC login of each affected user binds the account again.
 */
class ResetOidcBinding extends Command
{
    protected static $defaultName = 'users:resetOidcBinding';
    protected static $defaultDescription = 'Reset the OIDC (SSO) subject binding of one user or of all users';

    protected function configure()
    {
        $this
            ->addOption('user', null, InputOption::VALUE_REQUIRED, 'User name (user_name) whose binding is reset')
            ->addOption('all', null, InputOption::VALUE_NONE, 'Reset the bindings of all users')
            ->addOption('force', null, InputOption::VALUE_NONE, 'Do not ask for confirmation with --all')
            ->setHelp(
                "Each user is bound to the OIDC \"sub\" claim on the first single sign-on login.\n"
                . "After the account was recreated in the IdP, or after switching to another IdP, reset the binding:\n"
                . "  php MintCLI users:resetOidcBinding --user=jsmith\n"
                . "  php MintCLI users:resetOidcBinding --all"
            );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $user_name = (string) $input->getOption('user');
        $reset_all = (bool) $input->getOption('all');

        if (($user_name === '') === !$reset_all) {
            $io->error('Pass exactly one of --user=<user_name> or --all.');
            return Command::INVALID;
        }

        $binder = new \OIDCSubjectBinder();

        if ($reset_all) {
            if (!$input->getOption('force') && !$io->confirm('Reset the OIDC binding of ALL users?', false)) {
                $io->note('Cancelled.');
                return Command::SUCCESS;
            }
            $io->success('Reset OIDC bindings: ' . $binder->unbindAll());
            return Command::SUCCESS;
        }

        $user_id = (new \User())->retrieve_user_id($user_name);
        if (empty($user_id)) {
            $io->error('User not found: ' . $user_name);
            return Command::FAILURE;
        }

        if ($binder->unbind($user_id)) {
            $io->success('Reset the OIDC binding of ' . $user_name . '.');
        } else {
            $io->note($user_name . ' has no OIDC binding.');
        }
        return Command::SUCCESS;
    }
}
