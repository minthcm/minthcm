<?php

namespace MintHCM\MintCLI\Services;

#[\AllowDynamicProperties]
class DatabaseService
{
    public function testDBname($name)
    {
        // Check if name is empty
        if (empty($name) || !is_string($name)) {
            return [
                'status' => false,
                'message' => "Database name cannot be empty",
            ];
        }

        // Check length (MySQL limit is 64 characters)
        if (strlen($name) > 64) {
            return [
                'status' => false,
                'message' => "Database name cannot exceed 64 characters",
            ];
        }

        // Check for dangerous characters: /, \, ., spaces, and special characters
        // MySQL database names should only contain: a-z, A-Z, 0-9, _, $
        if (!preg_match('/^[a-zA-Z0-9_$]+$/', $name)) {
            return [
                'status' => false,
                'message' => "Database name can only contain letters, numbers, underscores (_), and dollar signs ($)",
            ];
        }

        // Check if name starts with a number (not recommended)
        if (preg_match('/^[0-9]/', $name)) {
            return [
                'status' => false,
                'message' => "Database name should not start with a number",
            ];
        }

        return [
            'status' => true,
            'message' => ''
        ];
    }
    
    public function testConnection($host, $port, $username, $password)
    {
        $conn = $this->getConnection($host, $username, $password, '', $port);
        if (!$conn['status']) {
            return [
                'status' => false,
                'message' => $conn['message'],
            ];
        }
        $conn['connection']->close();
        return [
            'status' => true,
            'message' => '',
        ];
    }

    public function testDatabaseExistance($host, $port, $username, $password, $name)
    {
        $conn = $this->getConnection($host, $username, $password, 'information_schema', $port);
        if (!$conn['status']) {
            return ['status' => false];
        }
        $stmt = $conn['connection']->prepare(
            "SELECT schema_name FROM information_schema.schemata WHERE schema_name = ?"
        );
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->store_result();
        $exists = $stmt->num_rows > 0;
        $stmt->close();
        $conn['connection']->close();
        return ['status' => !$exists];
    }

    public function getConnection($host, $username, $password, $database, $port){
        try {
            $connection = new \mysqli($host, $username, $password, $database, $port);
        } catch (\mysqli_sql_exception $e) {
            return [ 'status' => false, 'message' => $e->getMessage() ];
        }
        if ($connection->connect_error) {
            return [ 'status' => false, 'message' => $connection->connect_error ];
        }
        return [ 'status' => true, 'message' => '', 'connection' => $connection ];
    }
}
