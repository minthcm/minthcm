<?php

namespace MintHCM\MintCLI\Services;

#[\AllowDynamicProperties]
class ElasticsearchService
{
    public function testConnection(string $host, string $port, ?string $username, ?string $password)
    {
        $ch = curl_init();
        curl_setopt_array($ch, $this->setupCurlOptions($host, $port, $username, $password));
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            return $this->error(': ' . curl_error($ch));
        }
        curl_close($ch);

        $response = json_decode($response, true);

        if (is_array($response) && isset($response['status']) && $response['status'] == 401){
            return $this->error("Wrong credentials. Cannot access ElasticSearch");
        } 

        if (empty($response['version']) || empty($response['version']['number'])) {
            return $this->error("Invalid response from ElasticSearch");
        }

        $version = $response['version']['number'];
        $major_version = explode('.', $version)[0];
        if ($major_version !== '7') {
            return $this->error("MintHCM currently supports only Elasticsearch 7.x, you tried to connect with $major_version");
        }
        if (version_compare($version, '7.10.0', '<')) {
            return $this->error("MintHCM requires Elasticsearch 7.10 or higher, you have $version");
        }

        return $this->ok();
    }

    public function reindexElastic(){
        try {
            chdir('legacy');
            require_once 'include/entryPoint.php';
            $indexer = new \SuiteCRM\Search\ElasticSearch\ElasticSearchIndexer;
            $indexer->index();
        } catch (\Exception $e) {
            
        }
        finally
        {
            chdir('..');
        }
    }

    public function isModueEnabled(string $moduleName): bool
    {
        require 'legacy/custom/modules/unified_search_modules_display.php';
        if (!isset($unified_search_modules_display[$moduleName])) {
            return false;
        }
        if (isset($unified_search_modules_display[$moduleName]['visible']) && $unified_search_modules_display[$moduleName]['visible'] == false) {
            return false;
        }
        return true;
    }

    public function checkShardCapacity(string $host, string $port, ?string $username, ?string $password, int $modulesCount = 100): array
    {
        $baseOptions = $this->setupCurlOptions($host, $port, $username, $password);

        $ch = curl_init();
        $statsOptions = $baseOptions;
        $statsOptions[CURLOPT_URL] = "$host:$port/_cluster/stats";
        curl_setopt_array($ch, $statsOptions);
        $statsResponse = curl_exec($ch);
        if (curl_errno($ch)) {
            return $this->error('Could not retrieve cluster stats: ' . curl_error($ch));
        }
        curl_close($ch);

        $ch = curl_init();
        $settingsOptions = $baseOptions;
        $settingsOptions[CURLOPT_URL] = "$host:$port/_cluster/settings?include_defaults=true";
        curl_setopt_array($ch, $settingsOptions);
        $settingsResponse = curl_exec($ch);
        if (curl_errno($ch)) {
            return $this->error('Could not retrieve cluster settings: ' . curl_error($ch));
        }
        curl_close($ch);

        $stats = json_decode($statsResponse, true);
        $settings = json_decode($settingsResponse, true);

        $currentShards = (int)($stats['indices']['shards']['total'] ?? 0);
        $nodeCount = (int)($stats['nodes']['count']['data'] ?? 1);
        $maxShardsPerNode = (int)(
            $settings['defaults']['cluster']['max_shards_per_node']
            ?? $settings['persistent']['cluster']['max_shards_per_node']
            ?? $settings['transient']['cluster']['max_shards_per_node']
            ?? 1000
        );
        $maxShardsTotal = $maxShardsPerNode * $nodeCount;

        // Each index uses 2 shards by default (1 primary + 1 replica); add 20% safety buffer
        $neededShards = (int)ceil($modulesCount * 2 * 1.2);
        $available = $maxShardsTotal - $currentShards;

        if ($available < $neededShards) {
            return $this->error(
                "Not enough Elasticsearch shard capacity. " .
                "Available: $available shards, needed (with 20% buffer): $neededShards. " .
                "Current: $currentShards / $maxShardsTotal. " .
                "Increase cluster.max_shards_per_node in Elasticsearch settings."
            );
        }

        return $this->ok();
    }

    protected function setupCurlOptions(string $host, string $port, ?string $username, ?string $password)
    {
        $options = [
            CURLOPT_URL => "$host:$port",
            CURLOPT_RETURNTRANSFER => true,
        ];

        if (!empty($username) && !empty($password)) {
            $options = [
                CURLOPT_HTTPAUTH => CURLAUTH_BASIC,
                CURLOPT_USERPWD => "$username:$password",
            ] + $options;
        }

        return $options;
    }

    private function ok(): array
    {
        return [
            'status' => true,
        ];
    }

    private function error(string $message): array
    {
        return [
            'status' => false,
            'message' => $message,
        ];
    }
}
