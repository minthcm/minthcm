<?php
/**
 * Post-upgrade script: Trigger Quick Repair & Rebuild via CLI.
 * Scripts in post_upgrade/ are executed after git checkout + composer + permissions.
 * Must define a run(OutputInterface $output): bool function.
 * Return true on success, false on failure.
 */

use Symfony\Component\Console\Output\OutputInterface;

return function (OutputInterface $output): bool
{
    $output->writeln('  Running Quick Repair & Rebuild...');

    $repairScript = __DIR__ . '/../../../../legacy/repair/index.php';
    if (!file_exists($repairScript)) {
        $output->writeln('  <comment>Repair script not found, skipping.</comment>');
        return true;
    }

    exec('php ' . escapeshellarg($repairScript) . ' repair_rebuild 2>&1', $result, $exitCode);

    if ($exitCode !== 0) {
        $output->writeln('  <error>Repair & Rebuild failed.</error>');
        return false;
    }

    $output->writeln('  <info>Repair & Rebuild completed.</info>');
    return true;
};
