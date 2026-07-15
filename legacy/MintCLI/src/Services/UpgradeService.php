<?php

namespace MintHCM\MintCLI\Services;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Handles the actual upgrade operations: git, composer, permissions, migrations, custom scripts.
 */
class UpgradeService
{
    const INSTANCE_DIR   = './legacy';
    const UPGRADE_DIR    = './upgrade';
    const UPGRADE_LOG    = './upgrade.log';
    const UPGRADE_STATE  = './upgrade.state.json';

    /**
     * Paths whose local modifications are discarded before checkout — generated
     * artifacts (e.g. Doctrine entities rebuilt by Quick Repair) that upgrade
     * tags are expected to replace outright rather than merge.
     */
    const IGNORED_ON_CHECKOUT = [
        'api/app/Entities',
    ];

    private OutputInterface $output;
    private string $last_error = '';
    private string $git_user   = '';
    private string $git_pass   = '';

    public function __construct(OutputInterface $output)
    {
        $this->output = $output;
    }

    // -------------------------------------------------------------------------
    // State (resume support)
    // -------------------------------------------------------------------------

    /**
     * Persist the current upgrade state so the process can be resumed after failure.
     */
    public function saveState(string $tag, array $completed_steps, string $failed_step, string $effective_upgrade = ''): void
    {
        $state = [
            'tag'              => $tag,
            'effective_upgrade' => $effective_upgrade ?: $tag,
            'completed_steps'  => $completed_steps,
            'failed_step'      => $failed_step,
            'failed_at'        => date('Y-m-d H:i:s'),
            'error'            => $this->last_error,
        ];
        file_put_contents(self::UPGRADE_STATE, json_encode($state, JSON_PRETTY_PRINT) . PHP_EOL);
        $this->logError("Upgrade interrupted at step '{$failed_step}'. State saved to " . self::UPGRADE_STATE);
    }

    /**
     * Load state from a previous interrupted upgrade, or null if none exists.
     */
    public function loadState(): ?array
    {
        if (!file_exists(self::UPGRADE_STATE)) {
            return null;
        }
        $data = json_decode(file_get_contents(self::UPGRADE_STATE), true);
        return is_array($data) ? $data : null;
    }

    /**
     * Remove the state file after a successful (or deliberately discarded) upgrade.
     */
    public function clearState(): void
    {
        if (file_exists(self::UPGRADE_STATE)) {
            unlink(self::UPGRADE_STATE);
        }
    }

    public function getLastError(): string
    {
        return $this->last_error;
    }

    // -------------------------------------------------------------------------
    // Git
    // -------------------------------------------------------------------------

    /**
     * Fetch all tags and remote refs from origin.
     */
    public function gitFetch(): bool
    {
        if (!empty($this->git_user)) {
            return $this->execWithAuthUrl('git fetch --tags --prune origin', 'Fetching from remote');
        }

        return $this->exec('git fetch --tags --prune origin', 'Fetching from remote');
    }

    /**
     * Checkout the target tag.
     */
    public function gitCheckout(string $tag): bool
    {
        if (!$this->discardIgnoredPaths()) {
            return false;
        }

        return $this->exec("git checkout " . escapeshellarg($tag), "Checking out {$tag}");
    }

    /**
     * Reset local changes in IGNORED_ON_CHECKOUT paths so they never block or
     * survive a checkout — the incoming tag's version replaces them wholesale.
     */
    private function discardIgnoredPaths(): bool
    {
        foreach (self::IGNORED_ON_CHECKOUT as $path) {
            if (!file_exists($path)) {
                continue;
            }

            $this->log("Discarding local changes in {$path}...");
            exec('git checkout HEAD -- ' . escapeshellarg($path) . ' 2>&1', $out, $code);
            if ($code !== 0) {
                $error = implode("\n", $out);
                $this->last_error = "Could not reset local changes in {$path}:\n{$error}";
                $this->logError($this->last_error);
                return false;
            }

            exec('git clean -fd -- ' . escapeshellarg($path) . ' 2>&1');
        }

        return true;
    }

    /**
     * Return a list of version tags higher than the current version.
     */
    public function getAvailableTags(?string $current_version = null): array
    {
        exec("git tag --sort=version:refname 2>/dev/null", $tags);
        $tags = array_values(array_filter($tags, fn($t) => preg_match('/^\d+\.\d+/', $t)));

        if ($current_version !== null && $current_version !== 'unknown') {
            $tags = array_values(array_filter($tags, fn($t) => version_compare($t, $current_version, '>')));
        }

        return $tags;
    }

    /**
     * List upgrade subdirectory names present in upgrade/ on the given tag.
     * Uses git ls-tree — no checkout needed.
     * Returns e.g. ['4.3.0', '4.3.1']
     */
    public function getUpgradeVersionsOnTag(string $tag): array
    {
        $raw = shell_exec("git ls-tree --name-only " . escapeshellarg("{$tag}:upgrade/") . " 2>/dev/null");
        if (empty($raw)) {
            return [];
        }
        $entries = array_filter(array_map('trim', explode("\n", $raw)));
        return array_values(array_filter($entries, fn($e) => preg_match('/^\d+\.\d+/', $e)));
    }

    /**
     * Store git credentials to be used during HTTPS fetch.
     */
    public function setGitCredentials(string $user, string $pass): void
    {
        $this->git_user = $user;
        $this->git_pass = $pass;
    }

    /**
     * Return true if the origin remote uses an HTTPS URL.
     */
    public function isHttpsRemote(): bool
    {
        $url = trim(shell_exec('git remote get-url origin 2>/dev/null') ?? '');
        return str_starts_with($url, 'http://') || str_starts_with($url, 'https://');
    }

    /**
     * Read the current MintHCM version from minthcm_version.php without requiring sugarEntry.
     */
    public function getCurrentVersion(): string
    {
        $file = self::INSTANCE_DIR . '/minthcm_version.php';
        if (!file_exists($file)) {
            return 'unknown';
        }
        $content = file_get_contents($file);
        if (preg_match("/\\\$minthcm_version\s*=\s*'([^']+)'/", $content, $matches)) {
            return $matches[1];
        }
        return 'unknown';
    }

    // -------------------------------------------------------------------------
    // Composer
    // -------------------------------------------------------------------------

    public function composerInstall(): bool
    {
        return $this->exec(
            'composer install --no-dev --no-interaction --optimize-autoloader 2>&1',
            'Installing Composer dependencies'
        );
    }

    // -------------------------------------------------------------------------
    // Permissions
    // -------------------------------------------------------------------------

    public function setupPermissions(string $owner = 'www-data:www-data'): bool
    {
        $this->log("Setting file ownership ({$owner}) and permissions...");

        exec("chown -R " . escapeshellarg($owner) . " . 2>&1", $out, $code);
        if ($code !== 0) {
            $error = "Failed to set ownership to {$owner}: " . implode(' ', $out);
            $this->last_error = $error;
            $this->output->writeln("  <error>{$error}</error>");
            $this->logError($error);
            return false;
        }

        exec("chmod -R 755 . 2>&1", $out, $code);
        if ($code !== 0) {
            $error = "Failed to set permissions: " . implode(' ', $out);
            $this->last_error = $error;
            $this->output->writeln("  <error>{$error}</error>");
            $this->logError($error);
            return false;
        }

        $this->output->writeln("  <info>Ownership and permissions set successfully.</info>");
        return true;
    }

    // -------------------------------------------------------------------------
    // Instance rebuild
    // -------------------------------------------------------------------------

    /**
     * Run instance:rebuild via MintCLI (repair legacy, JS rebuild, clear api/cache, OAuth2 permissions).
     */
    public function runInstanceRebuild(): bool
    {
        return $this->exec('php MintCLI instance:rebuild', 'Running instance:rebuild');
    }

    // -------------------------------------------------------------------------
    // Pre / Post upgrade scripts
    // -------------------------------------------------------------------------

    /**
     * Execute all PHP scripts from upgrade/{tag}/pre_upgrade/ in alphabetical order.
     * Each script must define run(OutputInterface $output): bool.
     */
    public function runPreUpgradeScripts(string $tag): bool
    {
        return $this->runScriptDirectory($tag, 'pre_upgrade');
    }

    /**
     * Execute all PHP scripts from upgrade/{tag}/post_upgrade/ in alphabetical order.
     */
    public function runPostUpgradeScripts(string $tag): bool
    {
        return $this->runScriptDirectory($tag, 'post_upgrade');
    }

    // -------------------------------------------------------------------------
    // Migrations
    // -------------------------------------------------------------------------

    /**
     * Run SQL migration files from upgrade/{tag}/migrations/ in alphabetical order.
     * Reads DB credentials from legacy/config.php.
     */
    public function runMigrations(string $tag): bool
    {
        $dir = self::UPGRADE_DIR . "/{$tag}/migrations";
        if (!is_dir($dir)) {
            $this->output->writeln('  <comment>No migrations directory found, skipping.</comment>');
            return true;
        }

        $files = $this->getSortedFiles($dir, '*.sql');
        if (empty($files)) {
            $this->output->writeln('  <comment>No migration files found, skipping.</comment>');
            return true;
        }

        $db_config = $this->readDatabaseConfig();
        if (!$db_config) {
            $this->output->writeln('  <error>Could not read database configuration from legacy/config.php.</error>');
            return false;
        }

        foreach ($files as $file) {
            $this->output->writeln("  Running migration: " . basename($file));
            $cmd = sprintf(
                'mysql -h %s -P %s -u %s -p%s %s < %s 2>&1',
                escapeshellarg($db_config['host']),
                escapeshellarg($db_config['port']),
                escapeshellarg($db_config['user']),
                escapeshellarg($db_config['pass']),
                escapeshellarg($db_config['name']),
                escapeshellarg($file)
            );
            exec($cmd, $out, $code);
            if ($code !== 0) {
                $error = 'Migration ' . basename($file) . ' failed: ' . implode("\n", $out);
                $this->last_error = $error;
                $this->output->writeln("  <error>{$error}</error>");
                $this->logError($error);
                return false;
            }
        }

        $this->output->writeln('  <info>All migrations completed.</info>');
        return true;
    }

    // -------------------------------------------------------------------------
    // Private helpers
    // -------------------------------------------------------------------------

    private function runScriptDirectory(string $tag, string $subdir): bool
    {
        $dir = self::UPGRADE_DIR . "/{$tag}/{$subdir}";
        if (!is_dir($dir)) {
            $this->output->writeln("  <comment>No {$subdir} directory found, skipping.</comment>");
            return true;
        }

        $files = $this->getSortedFiles($dir, '*.php');
        if (empty($files)) {
            $this->output->writeln("  <comment>No {$subdir} scripts found, skipping.</comment>");
            return true;
        }

        foreach ($files as $i => $file) {
            if ($i > 0) {
                $this->output->writeln('');
            }
            $this->output->writeln("  <options=bold>Running: " . basename($file) . "</options=bold>");
            // Each script must return a callable: return function(OutputInterface $output): bool { ... };
            // Using require (not require_once) + closure contract avoids global function redeclaration
            // when multiple scripts are loaded in the same process.
            $fn = require $file;
            if (!is_callable($fn)) {
                $this->output->writeln("  <comment>No callable returned from " . basename($file) . ", skipping.</comment>");
                continue;
            }
            $result = $fn($this->output);
            if (!$result) {
                $error = "Script " . basename($file) . " reported failure.";
                $this->last_error = $error;
                $this->output->writeln("  <error>{$error} Aborting.</error>");
                $this->logError($error);
                return false;
            }
        }

        return true;
    }

    private function getSortedFiles(string $dir, string $pattern): array
    {
        $files = glob(rtrim($dir, '/') . '/' . $pattern) ?: [];
        sort($files);
        return $files;
    }

    private function readDatabaseConfig(): ?array
    {
        $config_file = self::INSTANCE_DIR . '/config.php';
        if (!file_exists($config_file)) {
            return null;
        }

        // Parse config.php to extract DB credentials without including it (avoids sugarEntry check)
        $content = file_get_contents($config_file);
        $patterns = [
            'host' => "/['\"]db_host_name['\"]\s*=>\s*['\"]([^'\"]+)['\"]/",
            'port' => "/['\"]db_port['\"]\s*=>\s*['\"]?(\d+)['\"]?/",
            'user' => "/['\"]db_user_name['\"]\s*=>\s*['\"]([^'\"]+)['\"]/",
            'pass' => "/['\"]db_password['\"]\s*=>\s*['\"]([^'\"]*)['\"]/" ,
            'name' => "/['\"]db_name['\"]\s*=>\s*['\"]([^'\"]+)['\"]/",
        ];

        $result = [];
        foreach ($patterns as $key => $pattern) {
            if (!preg_match($pattern, $content, $m)) {
                return null;
            }
            $result[$key] = $m[1];
        }

        $result['port'] = $result['port'] ?: '3306';
        return $result;
    }

    /**
     * Temporarily inject credentials into the remote URL, run the command, then restore the original URL.
     * Credentials are never written to the log or console output.
     */
    private function execWithAuthUrl(string $cmd, string $label): bool
    {
        $original_url = trim(shell_exec('git remote get-url origin 2>/dev/null') ?? '');
        $auth_url     = preg_replace('#^(https?://)#', '$1' . rawurlencode($this->git_user) . ':' . rawurlencode($this->git_pass) . '@', $original_url);

        exec('git remote set-url origin ' . escapeshellarg($auth_url) . ' 2>&1', $out, $code);
        if ($code !== 0) {
            $this->last_error = 'Could not set authenticated remote URL.';
            $this->output->writeln('  <error>Could not configure git credentials.</error>');
            return false;
        }

        $result = $this->exec($cmd, $label);

        // Always restore original URL — even on failure
        exec('git remote set-url origin ' . escapeshellarg($original_url) . ' 2>/dev/null');

        return $result;
    }

    private function exec(string $cmd, string $label): bool
    {
        $this->log($label . '...');
        exec($cmd . ' 2>&1', $output, $code);
        if ($code !== 0) {
            $error = implode("\n", $output);
            $this->last_error = $error;
            $this->output->writeln("  <error>{$label} failed:</error>");
            $this->output->writeln('  ' . implode("\n  ", $output));
            $this->logError("{$label} failed:\n{$error}");
            return false;
        }
        $this->output->writeln("  <info>{$label} — done.</info>");
        return true;
    }

    private function log(string $message): void
    {
        $this->output->writeln("  {$message}");
        file_put_contents(self::UPGRADE_LOG, date('[Y-m-d H:i:s] ') . strip_tags($message) . PHP_EOL, FILE_APPEND);
    }

    private function logError(string $message): void
    {
        file_put_contents(self::UPGRADE_LOG, date('[Y-m-d H:i:s] ') . '[ERROR] ' . strip_tags($message) . PHP_EOL, FILE_APPEND);
    }
}
