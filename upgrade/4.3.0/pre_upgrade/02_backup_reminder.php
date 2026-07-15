<?php
/**
 * Pre-upgrade script: Final backup reminder.
 * Scripts in pre_upgrade/ are executed before git checkout.
 * Must define a run(OutputInterface $output): bool function.
 * Return true to continue, false to abort upgrade.
 */

use Symfony\Component\Console\Output\OutputInterface;

return function (OutputInterface $output): bool
{
    $output->writeln('  <comment>REMINDER: Ensure you have a full backup of your database and files before proceeding.</comment>');
    $output->writeln('  <comment>Backups should include: database dump, legacy/, api/, custom/ directories.</comment>');
    return true;
};
