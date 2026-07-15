<?php
/**
 * Pre-upgrade script: Check for custom module conflicts.
 * Scripts in pre_upgrade/ are executed before git checkout.
 * Must define a run(OutputInterface $output): bool function.
 * Return true to continue, false to abort upgrade.
 */

use Symfony\Component\Console\Output\OutputInterface;

return function (OutputInterface $output): bool
{
    $output->writeln('  Checking for custom module conflicts...');
    // Add custom conflict detection logic here if needed.
    $output->writeln('  <info>No conflicts detected.</info>');
    return true;
};
