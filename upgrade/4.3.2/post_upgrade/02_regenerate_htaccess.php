<?php
/**
 * Post-upgrade script: Update the instance root .htaccess to the 4.3.2 frontend
 * serving model.
 *
 * 4.3.2 changes how frontend files are reached: assets and the SPA entry now come
 * from vue/dist/ (RewriteRule ^assets/(.*)$ vue/dist/assets/$1, RewriteRule ^ vue/dist/index.html,
 * DirectoryIndex vue/dist/index.html), with no-cache headers on index.html and the
 * MCP / OAuth well-known rules. The shipped template
 * legacy/MintCLI/src/Assets/.htaccess is authoritative; this regenerates the root
 * .htaccess from it while preserving the instance RewriteBase.
 *
 * Runs after checkout (post_upgrade) so the NEW template is on disk. This
 * regeneration also contains the MCP rules, so it supersedes
 * pre_upgrade/01_add_mcp_htaccess_rules.php (which stays harmless: it guards on
 * its own marker and this step overwrites the file afterwards).
 *
 * WARN-only: if the base path cannot be determined it prints manual steps and
 * returns true rather than writing an .htaccess with a wrong RewriteBase.
 * Idempotent: skips when the file already uses the 4.3.2 model.
 */

use Symfony\Component\Console\Output\OutputInterface;
use MintHCM\MintCLI\Services\HtaccessService;

return function (OutputInterface $output): bool
{
    // upgrade/4.3.2/post_upgrade/ -> project root is three levels up.
    $root_htaccess = __DIR__ . '/../../../.htaccess';
    $template      = __DIR__ . '/../../../legacy/MintCLI/src/Assets/.htaccess';

    if (!file_exists($template)) {
        $output->writeln('  <comment>.htaccess template not found (' . $template . '). Skipping frontend update; update it manually.</comment>');
        return true;
    }

    if (!file_exists($root_htaccess)) {
        $output->writeln('  <comment>No root .htaccess found. Skipping (a fresh install generates it).</comment>');
        return true;
    }

    $current = file_get_contents($root_htaccess);

    // Idempotency: the 4.3.2 template routes the SPA through vue/dist/index.html.
    if (strpos($current, 'vue/dist/index.html') !== false) {
        $output->writeln('  <info>Root .htaccess already uses the 4.3.2 frontend serving model. Skipping.</info>');
        return true;
    }

    // Preserve the instance base path (RewriteBase) from the current file.
    if (!preg_match('/^\s*RewriteBase\s+(\S+)/mi', $current, $m)) {
        $output->writeln('  <comment>WARNING: Could not read RewriteBase from root .htaccess.</comment>');
        $output->writeln('  <comment>Frontend serving rules were NOT changed (avoiding a wrong RewriteBase).</comment>');
        $output->writeln('  <comment>Regenerate manually from legacy/MintCLI/src/Assets/.htaccess (replace __BASE_PATH__).</comment>');
        return true;
    }
    $base_path = $m[1];

    // Back up the current file once before overwriting.
    $backup = $root_htaccess . '.bak.4.3.2';
    if (!file_exists($backup) && !@copy($root_htaccess, $backup)) {
        $output->writeln('  <error>Could not back up root .htaccess to ' . $backup . '. Aborting update.</error>');
        return false;
    }
    $output->writeln('  Backed up current root .htaccess to: ' . basename($backup));

    // Regenerate from the authoritative template, preserving RewriteBase.
    // Prefer the shipped service; fall back to its documented logic if unavailable.
    if (class_exists(HtaccessService::class)) {
        (new HtaccessService())->setupApplicationHtaccess($base_path);
    } else {
        $rendered = str_replace('__BASE_PATH__', $base_path, file_get_contents($template));
        if (file_put_contents($root_htaccess, $rendered) === false) {
            $output->writeln('  <error>Failed to write updated root .htaccess.</error>');
            return false;
        }
    }

    $output->writeln('  <info>Root .htaccess updated to the 4.3.2 frontend serving model (RewriteBase ' . $base_path . ').</info>');
    return true;
};
