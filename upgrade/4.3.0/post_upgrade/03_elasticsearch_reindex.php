<?php
/**
 * Post-upgrade script: Reindex Elasticsearch.
 * Clears existing indices and reindexes all modules.
 * Emits a warning on failure but does not abort the upgrade.
 * Scripts in post_upgrade/ are executed after git checkout + composer + permissions.
 * Must define a run(OutputInterface $output): bool function.
 * Return true on success, false on failure.
 */

use Symfony\Component\Console\Output\OutputInterface;

return function (OutputInterface $output): bool
{
    $mint_cli = __DIR__ . '/../../../MintCLI';

    $output->writeln('  Reindexing Elasticsearch (this may take several minutes depending on database size)...');
    exec('php ' . escapeshellarg($mint_cli) . ' elasticsearch:reindex 2>&1', $out, $code);

    foreach ($out as $line) {
        $output->writeln('    ' . $line);
    }

    if ($code !== 0) {
        $output->writeln('  <comment>WARNING: Elasticsearch reindex failed. Search functionality may be affected.</comment>');
        $output->writeln('  <comment>You can reindex manually by running: ./MintCLI elasticsearch:reindex</comment>');
        return true;
    }

    $output->writeln('  <info>Elasticsearch reindex completed successfully.</info>');
    return true;
};
