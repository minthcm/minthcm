<?php
/**
 * Post-upgrade script: Reindex Elasticsearch.
 * 4.3.2 adds full-text-searchable modules (AIPromptTemplates, MCPDocumentation,
 * MCPDocCategories) and changes the indexer. Quick Repair creates the database
 * tables but does not build/populate Elasticsearch indices, so a reindex is
 * required for the new modules to appear in search.
 *
 * Scripts in post_upgrade/ run after git checkout + instance:rebuild + migrations.
 * Must return a callable run(OutputInterface $output): bool.
 * Emits a warning on failure but does NOT abort the upgrade (search is not
 * upgrade-critical and can be rebuilt manually).
 */

use Symfony\Component\Console\Output\OutputInterface;

return function (OutputInterface $output): bool
{
    // upgrade/4.3.2/post_upgrade/ -> project root is three levels up.
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
