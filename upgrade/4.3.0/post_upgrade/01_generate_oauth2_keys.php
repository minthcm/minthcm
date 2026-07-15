<?php
/**
 * Post-upgrade script: Generate OAuth2 private/public keys and regenerate client secret.
 * Skips generation if keys already exist (idempotent).
 * Scripts in post_upgrade/ are executed after git checkout + composer + permissions.
 * Must define a run(OutputInterface $output): bool function.
 * Return true on success, false on failure.
 */

use Symfony\Component\Console\Output\OutputInterface;

return function (OutputInterface $output): bool
{
    $root        = __DIR__ . '/../../..';
    $mint_cli    = $root . '/MintCLI';
    $keys_dir    = $root . '/api/configs';
    $private_key = $keys_dir . '/private.key';
    $public_key  = $keys_dir . '/public.key';

    if (file_exists($private_key) && file_exists($public_key)) {
        $output->writeln('  <info>OAuth2 key files already exist. Skipping key generation.</info>');
        return true;
    }

    $output->writeln('  Generating OAuth2 private/public key pair...');
    exec('php ' . escapeshellarg($mint_cli) . ' oauth2:createKeys 2>&1', $out, $code);
    if ($code !== 0) {
        $output->writeln('  <error>oauth2:create-keys failed: ' . implode(' ', $out) . '</error>');
        return false;
    }
    $output->writeln('  <info>OAuth2 keys generated successfully.</info>');

    $output->writeln('  Regenerating OAuth2 client secret...');
    exec('php ' . escapeshellarg($mint_cli) . ' oauth2:regenerateClientSecret 2>&1', $out, $code);
    if ($code !== 0) {
        $output->writeln('  <error>oauth2:regenerateClientSecret failed: ' . implode(' ', $out) . '</error>');
        return false;
    }
    $output->writeln('  <info>OAuth2 client secret regenerated successfully.</info>');

    return true;
};
