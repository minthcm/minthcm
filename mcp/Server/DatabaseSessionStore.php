<?php

declare(strict_types=1);

namespace MintMCP\Server;

use DBManagerFactory;
use Mcp\Server\Session\SessionStoreInterface;
use Symfony\Component\Uid\Uuid;

class DatabaseSessionStore implements SessionStoreInterface
{
    private const TABLE = 'mcp_sessions';

    public function __construct(
        private readonly int $ttl = 3600,
    ) {}

    public function exists(Uuid $id): bool
    {
        $db = DBManagerFactory::getInstance();
        $idStr = $db->quote($id->toRfc4122());
        $cutoff = time() - $this->ttl;

        $queryResult = $db->query(
            "SELECT 1 FROM " . self::TABLE . " WHERE id = '{$idStr}' AND updated_at >= {$cutoff} LIMIT 1"
        );

        if (!$queryResult) {
            return false;
        }

        $row = $db->fetchByAssoc($queryResult, false);

        return !empty($row);
    }

    public function read(Uuid $id): string|false
    {
        $db = DBManagerFactory::getInstance();
        $idStr = $db->quote($id->toRfc4122());
        $cutoff = time() - $this->ttl;

        $queryResult = $db->query(
            "SELECT session_data FROM " . self::TABLE . " WHERE id = '{$idStr}' AND updated_at >= {$cutoff} LIMIT 1"
        );

        if (!$queryResult) {
            return false;
        }

        $row = $db->fetchByAssoc($queryResult, false);

        if (empty($row)) {
            return false;
        }

        return $row['session_data'];
    }

    public function write(Uuid $id, string $data): bool
    {
        $db = DBManagerFactory::getInstance();
        $idStr = $db->quote($id->toRfc4122());
        // Use mysqli_real_escape_string directly to avoid $db->quote() calling from_html()
        // which would html_entity_decode &quot; → " inside a JSON string value, breaking JSON.
        $dataStr = mysqli_real_escape_string($db->getDatabase(), $data);
        $now = time();

        $queryResult = $db->query(
            "REPLACE INTO " . self::TABLE . " (id, session_data, updated_at) VALUES ('{$idStr}', '{$dataStr}', {$now})"
        );

        return $queryResult !== false;
    }

    public function destroy(Uuid $id): bool
    {
        $db = DBManagerFactory::getInstance();
        $idStr = $db->quote($id->toRfc4122());

        $queryResult = $db->query("DELETE FROM " . self::TABLE . " WHERE id = '{$idStr}'");

        return $queryResult !== false;
    }

    public function gc(): array
    {
        $db = DBManagerFactory::getInstance();
        $cutoff = time() - $this->ttl;

        $result = $db->query(
            "SELECT id FROM " . self::TABLE . " WHERE updated_at < {$cutoff}"
        );

        if ($result === false) {
            return [];
        }

        $deleted = [];
        while ($row = $db->fetchByAssoc($result, false)) {
            $deleted[] = Uuid::fromString($row['id']);
        }

        $db->query("DELETE FROM " . self::TABLE . " WHERE updated_at < {$cutoff}");

        return $deleted;
    }
}
