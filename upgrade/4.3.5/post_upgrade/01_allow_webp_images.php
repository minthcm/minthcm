<?php
/**
 * Post-upgrade script: Allow WebP images in the instance configuration.
 *
 * 4.3.5 resizes uploaded Employees/Users profile photos and stores them as WebP
 * (resizeImage() in legacy/include/utils.php). The image upload validation chain
 * (has_valid_image_extension(), has_valid_image_mime_type(), verify_uploaded_image())
 * reads its allowlists from $sugar_config when present, and the installer persists
 * 'valid_image_ext' into legacy/config.php. On instances installed before 4.3.5 that
 * persisted list has no 'webp', so every resized profile photo would be rejected.
 *
 * This script appends 'webp' to 'valid_image_ext' and 'image/webp' to 'image_ext'
 * in legacy/config.php when those keys exist and lack it. Keys that are absent are
 * left untouched (the code defaults already include WebP).
 *
 * Values overridden in legacy/config_override.php are not rewritten automatically
 * (free-form PHP); the script prints manual steps instead.
 *
 * Also warns when the PHP GD extension has no WebP support, which resizeImage() requires.
 *
 * Idempotent: skips when WebP is already allowed.
 */

use Symfony\Component\Console\Output\OutputInterface;

return function (OutputInterface $output): bool
{
    // upgrade/4.3.5/post_upgrade/ -> project root is three levels up.
    $legacy_dir    = __DIR__ . '/../../../legacy';
    $config_file   = $legacy_dir . '/config.php';
    $override_file = $legacy_dir . '/config_override.php';

    // Config key => value that must be present in the list.
    $required_entries = [
        'valid_image_ext' => 'webp',
        'image_ext'       => 'image/webp',
    ];

    if (!function_exists('imagewebp')) {
        $output->writeln('  <comment>WARNING: PHP GD extension has no WebP support (imagewebp() is missing).</comment>');
        $output->writeln('  <comment>Profile photo upload requires it - install/enable GD with WebP support for the web server PHP.</comment>');
    }

    if (!file_exists($config_file)) {
        $output->writeln('  <comment>legacy/config.php not found. Skipping (a fresh install generates it with WebP allowed).</comment>');
        return true;
    }

    $read_config = function (string $file, array $sugar_config = []): array {
        if (!defined('sugarEntry')) {
            define('sugarEntry', true);
        }
        include $file;
        return is_array($sugar_config) ? $sugar_config : [];
    };

    $config = $read_config($config_file);
    if (empty($config)) {
        $output->writeln('  <error>Could not read $sugar_config from legacy/config.php.</error>');
        return false;
    }

    $changed = [];
    foreach ($required_entries as $key => $entry) {
        if (!isset($config[$key]) || !is_array($config[$key]) || in_array($entry, $config[$key], true)) {
            continue;
        }
        $config[$key][] = $entry;
        $changed[] = $key;
    }

    if (empty($changed)) {
        $output->writeln('  <info>legacy/config.php already allows WebP images. Skipping.</info>');
    } else {
        $backup = $config_file . '.bak.4.3.5';
        if (!file_exists($backup) && !@copy($config_file, $backup)) {
            $output->writeln('  <error>Could not back up legacy/config.php to ' . $backup . '. Aborting update.</error>');
            return false;
        }
        $output->writeln('  Backed up current legacy/config.php to: ' . basename($backup));

        // Same format as legacy write_array_to_file('sugar_config', ...).
        $content = "<?php\n// created: " . date('Y-m-d H:i:s') . "\n"
            . '$sugar_config = ' . var_export($config, true) . ";\n";
        if (file_put_contents($config_file, $content, LOCK_EX) === false) {
            $output->writeln('  <error>Failed to write updated legacy/config.php.</error>');
            return false;
        }
        $output->writeln('  <info>WebP allowed in legacy/config.php: ' . implode(', ', $changed) . '.</info>');
    }

    // config_override.php wins over config.php - report lists that still block WebP.
    if (file_exists($override_file)) {
        $effective = $read_config($override_file, $config);
        foreach ($required_entries as $key => $entry) {
            if (isset($effective[$key]) && is_array($effective[$key]) && !in_array($entry, $effective[$key], true)) {
                $output->writeln("  <comment>WARNING: legacy/config_override.php overrides '{$key}' without '{$entry}'.</comment>");
                $output->writeln("  <comment>Add '{$entry}' to \$sugar_config['{$key}'] there manually, otherwise WebP profile photos will be rejected.</comment>");
            }
        }
    }

    return true;
};
