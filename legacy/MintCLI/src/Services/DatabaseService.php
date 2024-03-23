<?php

namespace MintHCM\MintCLI\Services;

class DatabaseService
{
    public function testConnection($host, $port, $username, $password)
    {
        exec("mysql -h $host -P $port -u $username -p$password --connect-timeout=10 -e 'quit' 2>&1", $connectionResult, $status);
        foreach($connectionResult as $resultLine) {
            if(strpos($resultLine, 'ERROR') !== false) {
                return [
                    'status' => false,
                    'message' => $resultLine
                ];
            }
        }
        return [
            'status' => true,
            'message' => ''
        ];

    }

    public function testDatabaseExistance($host, $port, $username, $password, $name)
    {
        exec("mysql -s -N -h $host -P $port -u $username -p$password -e 'SELECT schema_name FROM information_schema.schemata WHERE schema_name = \"$name\"' information_schema 2>&1", $result, $status);
        foreach($result as $resultLine) {
            if(strpos($resultLine, $name) !== false) {
                return [
                    'status' => false,
                ];
            }
        }
        return [
            'status' => true,
        ];
    }
}
