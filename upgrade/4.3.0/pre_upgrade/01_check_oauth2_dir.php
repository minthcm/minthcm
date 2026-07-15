<?php
/**
 * Pre-upgrade script: Check OAuth2 keys directory and inform user about key generation.
 * Scripts in pre_upgrade/ are executed before git checkout.
 * Must define a run(OutputInterface $output): bool function.
 * Return true to continue, false to abort upgrade.
 */

use Symfony\Component\Console\Output\OutputInterface;

return function (OutputInterface $output): bool
{
    $keys_dir = __DIR__ . '/../../../../api/configs';
    $private_key = $keys_dir . '/private.key';
    $public_key  = $keys_dir . '/public.key';

    if (file_exists($private_key) && file_exists($public_key)) {
        $output->writeln('  <info>OAuth2 key files found. They will be left untouched during upgrade.</info>');
    } else {
        $output->writeln('  <comment>OAuth2 key files not found in api/configs/.</comment>');
        $output->writeln('  <comment>They will be generated automatically during post-upgrade.</comment>');
    }

    return true;
};
