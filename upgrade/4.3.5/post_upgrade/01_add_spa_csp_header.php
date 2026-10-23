<?php
/**
 * Post-upgrade script: add Content-Security-Policy and related security headers
 * for the SPA entry point (index.html) to the instance root .htaccess.
 *
 * Up to 4.3.4 only legacy PHP pages received CSP; the SPA is served statically by
 * Apache from vue/dist/index.html and got no such headers. The shipped template
 * legacy/MintCLI/src/Assets/.htaccess is authoritative: the header lines are copied
 * from it, so the policy is defined in one place.
 *
 * The static policy mirrors get_default_csp_directives() and does NOT honour
 * $sugar_config['csp_directives'] / ['csp_extra_sources']. Instances relying on
 * extra sources must edit the header in the root .htaccess manually.
 *
 * Idempotent: skips when the root .htaccess already sets a Content-Security-Policy.
 * WARN-only: when the file cannot be patched safely it prints manual steps and
 * returns true.
 */

use Symfony\Component\Console\Output\OutputInterface;

return function (OutputInterface $output): bool
{
    // upgrade/4.3.5/post_upgrade/ -> project root is three levels up.
    $root_htaccess = __DIR__ . '/../../../.htaccess';
    $template      = __DIR__ . '/../../../legacy/MintCLI/src/Assets/.htaccess';

    if (!file_exists($template)) {
        $output->writeln('  <comment>.htaccess template not found (' . $template . '). Skipping; add the CSP header manually.</comment>');
        return true;
    }

    if (!file_exists($root_htaccess)) {
        $output->writeln('  <comment>No root .htaccess found. Skipping (a fresh install generates it).</comment>');
        return true;
    }

    $current = file_get_contents($root_htaccess);

    if (stripos($current, 'Header set Content-Security-Policy') !== false) {
        $output->writeln('  <info>Root .htaccess already sets a Content-Security-Policy. Skipping.</info>');
        return true;
    }

    // Header lines to inject: from the CSP comment up to and including Referrer-Policy.
    $pattern = '/^[ \t]*# Keep in sync with get_default_csp_directives\(\).*?^[ \t]*Header set Referrer-Policy[^\r\n]*\R?/ms';
    if (!preg_match($pattern, file_get_contents($template), $m)) {
        $output->writeln('  <comment>CSP lines not found in the template. Skipping; add the CSP header manually.</comment>');
        return true;
    }
    $header_lines = rtrim($m[0]) . PHP_EOL;

    // Inject right after the Expires header of the index.html block (first occurrence).
    $anchor = '/^([ \t]*Header set Expires[^\r\n]*\R)/m';
    if (!preg_match($anchor, $current)) {
        $output->writeln('  <comment>WARNING: index.html header block not found in root .htaccess. CSP header NOT added.</comment>');
        $output->writeln('  <comment>Copy the CSP lines from legacy/MintCLI/src/Assets/.htaccess into the <Files "index.html"> block manually.</comment>');
        return true;
    }

    $backup = $root_htaccess . '.bak.4.3.5';
    if (!file_exists($backup) && !@copy($root_htaccess, $backup)) {
        $output->writeln('  <error>Could not back up root .htaccess to ' . $backup . '. Aborting update.</error>');
        return false;
    }
    $output->writeln('  Backed up current root .htaccess to: ' . basename($backup));

    $updated = preg_replace($anchor, '$1' . str_replace(['\\', '$'], ['\\\\', '\\$'], $header_lines), $current, 1);
    if ($updated === null || file_put_contents($root_htaccess, $updated) === false) {
        $output->writeln('  <error>Failed to write updated root .htaccess.</error>');
        return false;
    }

    $output->writeln('  <info>Content-Security-Policy header added to the SPA entry point in root .htaccess.</info>');
    return true;
};
