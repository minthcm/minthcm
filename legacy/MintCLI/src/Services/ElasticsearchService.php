<?php

namespace MintHCM\MintCLI\Services;

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

        if ($response['status'] == 401){
            return $this->error("Wrong credentials. Cannot access ElasticSearch");
        } 

        if (empty($response['version']) || empty($response['version']['number'])) {
            return $this->error("Invalid response from ElasticSearch");
        }

        $major_version = explode('.', $response['version']['number'])[0];
        if ($major_version !== '7') {
            return $this->error("MintHCM currently supports only Elasticsearch 7, you tried to connect with $major_version");
        }

        return $this->ok();
    }

    public function reindexElastic(){
        try {
            chdir('legacy');
            require_once 'include/entryPoint.php';
            $indexer = new \SuiteCRM\Search\ElasticSearch\ElasticSearchIndexer;
            $indexer->index();
            chdir('..');
        } catch (\Exception $e) {
            
        }
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
