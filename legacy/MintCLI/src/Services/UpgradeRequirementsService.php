<?php

namespace MintHCM\MintCLI\Services;

/**
 * Reads upgrade requirements from a target git tag without checking it out,
 * then verifies the current environment meets those requirements.
 */
class UpgradeRequirementsService
{
    private array $errors    = [];
    private array $warnings  = [];
    private array $successes = [];

    /**
     * Load requirements.json from the target tag using `git show` (no checkout needed).
     * Reads upgrade/{$tag}/requirements.json from the tag.
     */
    public function loadRequirementsFromTag(string $tag): ?array
    {
        return $this->loadRequirements($tag, $tag);
    }

    /**
     * Load requirements.json for a given upgrade version, read from the target tag.
     * Useful when the effective upgrade version differs from the checkout tag
     * (e.g. upgrading to 4.3.1 but running 4.3.0 scripts).
     */
    public function loadRequirements(string $tag, string $upgrade_version): ?array
    {
        $path = "upgrade/{$upgrade_version}/requirements.json";
        $json = shell_exec("git show " . escapeshellarg("{$tag}:{$path}") . " 2>/dev/null");

        if (empty($json)) {
            return null;
        }

        $data = json_decode($json, true);
        return is_array($data) ? $data : null;
    }

    /**
     * Run all environment checks against the given requirements.
     * Returns true if all pass, false otherwise. Collect errors via getErrors().
     */
    public function verify(array $requirements, string $current_version): bool
    {
        $this->errors    = [];
        $this->warnings  = [];
        $this->successes = [];

        if (!empty($requirements['from_versions'])) {
            $this->checkFromVersion($current_version, $requirements['from_versions']);
        }

        if (!empty($requirements['php'])) {
            $this->checkPhpVersion($requirements['php']);
        }

        if (!empty($requirements['mysql'])) {
            $this->checkMysqlVersion($requirements['mysql']);
        }

        if (!empty($requirements['elasticsearch'])) {
            $this->checkElasticsearchVersion($requirements['elasticsearch']);
        }

        if (!empty($requirements['disk_space_mb'])) {
            $this->checkDiskSpace((int) $requirements['disk_space_mb']);
        }

        if (!empty($requirements['node'])) {
            $this->checkNodeVersion($requirements['node']);
        }

        return empty($this->errors);
    }

    public function getErrors(): array
    {
        return $this->errors;
    }

    public function getWarnings(): array
    {
        return $this->warnings;
    }

    public function getSuccesses(): array
    {
        return $this->successes;
    }

    public function getSkipSteps(array $requirements): array
    {
        return $requirements['skip_steps'] ?? [];
    }

    public function getNotes(array $requirements): ?string
    {
        return $requirements['notes'] ?? null;
    }

    // -------------------------------------------------------------------------

    private function checkFromVersion(string $current_version, array $allowed_versions): void
    {
        foreach ($allowed_versions as $pattern) {
            if ($this->versionMatchesPattern($current_version, $pattern)) {
                return;
            }
        }

        $allowed = implode(', ', $allowed_versions);
        $this->errors[] = "Direct upgrade from version {$current_version} is not supported. Supported source versions: {$allowed}.";
    }

    /**
     * Match a version string against a pattern that may contain a wildcard (*).
     * Examples: "4.2.*" matches "4.2.0", "4.2.1", "4.2.11". "4.2.0" matches only "4.2.0".
     */
    private function versionMatchesPattern(string $version, string $pattern): bool
    {
        if (strpos($pattern, '*') === false) {
            return $version === $pattern;
        }

        $regex = '/^' . str_replace('\*', '\d+', preg_quote($pattern, '/')) . '$/';
        return (bool) preg_match($regex, $version);
    }

    private function checkPhpVersion(array $php_req): void
    {
        $current = PHP_VERSION;
        $min = $php_req['min'] ?? null;
        $max = $php_req['max'] ?? null;

        if ($min && version_compare($current, $min, '<')) {
            $this->errors[] = "PHP {$current} is below required minimum {$min}.";
            return;
        }

        if ($max && version_compare($current, $max, '>')) {
            $this->errors[] = "PHP {$current} exceeds maximum supported version {$max}.";
            return;
        }

        $range = $min && $max ? "{$min} – {$max}" : ($min ? ">= {$min}" : "<= {$max}");
        $this->successes[] = "PHP {$current} OK (required: {$range})";
    }

    private function checkMysqlVersion(array $mysql_req): void
    {
        $min = $mysql_req['min'] ?? null;
        if (!$min) {
            return;
        }

        $cfg = $this->resolveMysqlConfig();

        if (!$cfg) {
            $this->errors[] = "Could not read database configuration from config files.";
            return;
        }

        try {
            $dsn = "mysql:host={$cfg['host']};port={$cfg['port']};dbname={$cfg['dbname']};charset=utf8";
            $pdo = new \PDO($dsn, $cfg['user'], $cfg['password'], [
                \PDO::ATTR_TIMEOUT => 5,
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            ]);
            $current = $pdo->query("SELECT VERSION()")->fetchColumn();
        } catch (\Exception $e) {
            $config_hint = $this->mysqlConfigHint();
            $this->errors[] = "Could not connect to MySQL ({$cfg['host']}:{$cfg['port']}): {$e->getMessage()}. "
                . "Ensure the database is running and credentials are correct in:\n{$config_hint}";
            return;
        }

        // Strip distro suffix, e.g. "8.0.32-Percona..." → "8.0.32"
        if (preg_match('/^(\d+\.\d+\.\d+)/', $current, $m)) {
            $current = $m[1];
        }

        if (version_compare($current, $min, '<')) {
            $config_hint = $this->mysqlConfigHint();
            $this->errors[] = "MySQL/Percona {$current} is below required minimum {$min}. "
                . "Update the host to point to a compatible instance in:\n{$config_hint}";
            return;
        }

        $this->successes[] = "MySQL/Percona {$current} OK (required: >= {$min}) — {$cfg['host']}:{$cfg['port']}";
    }

    private function mysqlConfigHint(): string
    {
        $legacy_override = dirname(__DIR__, 3) . '/config_override.php';
        $api_override    = dirname(__DIR__, 4) . '/api/configs/mint/config_override.php';
        return "  - {$legacy_override} (\$sugar_config['dbconfig']['db_host_name'])\n"
             . "  - {$api_override} (\$mint_config['database']['host'])";
    }

    /**
     * Read MySQL connection parameters from application config_override files.
     * Tries legacy config first, then API config.
     *
     * @return array{host:string,port:string,user:string,password:string,dbname:string}|null
     */
    private function resolveMysqlConfig(): ?array
    {
        // Legacy config_override: $sugar_config['dbconfig']
        $legacy_override = dirname(__DIR__, 3) . '/config_override.php';
        if (is_readable($legacy_override)) {
            $sugar_config = [];
            @include $legacy_override;
            $db = $sugar_config['dbconfig'] ?? [];
            if (!empty($db['db_host_name']) && !empty($db['db_user_name'])) {
                return [
                    'host'     => $db['db_host_name'],
                    'port'     => $db['db_port'] ?? '3306',
                    'user'     => $db['db_user_name'],
                    'password' => $db['db_password'] ?? '',
                    'dbname'   => $db['db_name'] ?? '',
                ];
            }
        }

        // API config_override: $mint_config['database']
        $api_override = dirname(__DIR__, 4) . '/api/configs/mint/config_override.php';
        if (is_readable($api_override)) {
            $mint_config = [];
            @include $api_override;
            $db = $mint_config['database'] ?? [];
            if (!empty($db['host']) && !empty($db['user'])) {
                return [
                    'host'     => $db['host'],
                    'port'     => (string) ($db['port'] ?? '3306'),
                    'user'     => $db['user'],
                    'password' => $db['password'] ?? '',
                    'dbname'   => $db['dbname'] ?? '',
                ];
            }
        }

        return null;
    }

    private function checkElasticsearchVersion(array $es_req): void
    {
        $min = $es_req['min'] ?? null;
        $max = $es_req['max'] ?? null;

        $configs = $this->resolveElasticsearchConfigs();

        $current      = null;
        $connected_url = null;
        $tried        = [];
        foreach ($configs as $cfg) {
            $tried[] = $cfg['url'];
            $cmd = "curl -s --max-time 5";
            if (!empty($cfg['user']) && !empty($cfg['pass'])) {
                $cmd .= " -u " . escapeshellarg($cfg['user'] . ':' . $cfg['pass']);
            }
            $cmd .= " " . escapeshellarg($cfg['url']) . " 2>/dev/null";

            $raw = shell_exec($cmd);
            if (empty($raw)) {
                continue;
            }

            $data = json_decode($raw, true);
            $current = $data['version']['number'] ?? null;
            if ($current) {
                $connected_url = $cfg['url'];
                break;
            }
        }

        if (!$current) {
            $config_hint = $this->elasticsearchConfigHint();
            $this->errors[] = "Could not reach Elasticsearch (tried: " . implode(', ', $tried) . "). "
                . "Ensure Elasticsearch is running and accessible, or update the host in:\n{$config_hint}";
            return;
        }

        $config_hint = $this->elasticsearchConfigHint();

        if ($min && version_compare($current, $min, '<')) {
            $this->errors[] = "Elasticsearch {$current} is below required minimum {$min}. "
                . "Update the host to point to a compatible instance in:\n{$config_hint}";
            return;
        }

        if ($max && version_compare($current, $max, '>')) {
            $this->errors[] = "Elasticsearch {$current} exceeds maximum supported version {$max}. "
                . "Update the host to point to a compatible instance in:\n{$config_hint}";
            return;
        }

        $range = $min && $max ? "{$min} – {$max}" : ($min ? ">= {$min}" : "<= {$max}");
        $this->successes[] = "Elasticsearch {$current} OK (required: {$range}) — {$connected_url}";
    }

    private function elasticsearchConfigHint(): string
    {
        $legacy_override = dirname(__DIR__, 3) . '/config_override.php';
        $api_override    = dirname(__DIR__, 4) . '/api/configs/mint/config_override.php';
        return "  - {$legacy_override} (\$sugar_config['search']['ElasticSearch']['host'])\n"
             . "  - {$api_override} (\$mint_config['search']['engines']['ElasticSearch'][0]['host'])";
    }

    /**
     * Build a list of Elasticsearch configs to try, each with url/user/pass,
     * starting with values from the application's config_override files.
     *
     * @return array<array{url:string,user:string,pass:string}>
     */
    private function resolveElasticsearchConfigs(): array
    {
        $configs = [];
        $seen    = [];

        // Legacy config_override: $sugar_config['search']['ElasticSearch']
        $legacy_override = dirname(__DIR__, 3) . '/config_override.php';
        if (is_readable($legacy_override)) {
            $sugar_config = [];
            @include $legacy_override;
            $es   = $sugar_config['search']['ElasticSearch'] ?? [];
            $host = $es['host'] ?? null;
            if ($host) {
                $scheme = str_contains($host, 'https://') ? '' : 'http://';
                $url = $scheme . $host;
                if (!str_contains($host, ':')) {
                    $url .= ':9200';
                }
                if (!in_array($url, $seen, true)) {
                    $seen[]    = $url;
                    $configs[] = ['url' => $url, 'user' => $es['user'] ?? '', 'pass' => $es['pass'] ?? ''];
                }
            }
        }

        // API config_override: $mint_config['search']['engines']['ElasticSearch'][0]
        $api_override = dirname(__DIR__, 4) . '/api/configs/mint/config_override.php';
        if (is_readable($api_override)) {
            $mint_config = [];
            @include $api_override;
            $es   = $mint_config['search']['engines']['ElasticSearch'][0] ?? [];
            $host = $es['host'] ?? null;
            $port = $es['port'] ?? '9200';
            if ($host) {
                $scheme = str_starts_with($host, 'https://') ? '' : 'http://';
                $url = $scheme . $host . ':' . $port;
                if (!in_array($url, $seen, true)) {
                    $seen[]    = $url;
                    $configs[] = ['url' => $url, 'user' => $es['user'] ?? '', 'pass' => $es['pass'] ?? ''];
                }
            }
        }

        return $configs;
    }

    private function checkDiskSpace(int $required_mb): void
    {
        $free_bytes = disk_free_space('.');
        if ($free_bytes === false) {
            return;
        }

        $free_mb = (int) ($free_bytes / 1024 / 1024);
        if ($free_mb < $required_mb) {
            $this->errors[] = "Insufficient disk space: {$free_mb} MB available, {$required_mb} MB required.";
        }
    }

    private function checkNodeVersion(array $node_req): void
    {
        $min = $node_req['min'] ?? null;
        if (!$min) {
            return;
        }

        $raw = shell_exec("node --version 2>/dev/null");
        if (empty($raw)) {
            $this->warnings[] = "Node.js not found. Version {$min}+ is recommended for frontend build.";
            return;
        }

        // node --version returns "v21.0.0" — strip the leading "v"
        $current = ltrim(trim($raw), 'v');
        if (version_compare($current, $min, '<')) {
            $this->warnings[] = "Node.js {$current} is below recommended version {$min}. Frontend build may fail.";
        }
    }
}
