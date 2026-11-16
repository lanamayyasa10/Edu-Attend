<?php

require_once __DIR__ . '/../config/Database.php';

class Model
{
    protected mysqli $db;

    public function __construct()
    {
        $this->db = (new Database())->getConnection();
    }

    protected function bindParams(mysqli_stmt $statement, string $types, array $params): void
    {
        if ($types === '' || $params === []) {
            return;
        }

        $references = [];
        foreach ($params as $key => &$value) {
            $references[$key] = &$value;
        }

        $statement->bind_param($types, ...$references);
    }

    protected function selectAll(string $sql, string $types = '', array $params = []): array
    {
        $statement = $this->db->prepare($sql);
        $this->bindParams($statement, $types, $params);
        $statement->execute();

        $result = $statement->get_result();
        $rows = $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
        $statement->close();

        return $rows;
    }

    protected function selectOne(string $sql, string $types = '', array $params = []): ?array
    {
        $rows = $this->selectAll($sql, $types, $params);
        return $rows[0] ?? null;
    }

    protected function executePrepared(string $sql, string $types = '', array $params = []): bool
    {
        $statement = $this->db->prepare($sql);
        $this->bindParams($statement, $types, $params);
        $success = $statement->execute();
        $statement->close();

        return $success;
    }

    protected function insertPrepared(string $sql, string $types = '', array $params = []): int
    {
        $statement = $this->db->prepare($sql);
        $this->bindParams($statement, $types, $params);
        $statement->execute();
        $id = (int)$this->db->insert_id;
        $statement->close();

        return $id;
    }

    protected function countRows(string $sql, string $types = '', array $params = []): int
    {
        $row = $this->selectOne($sql, $types, $params);
        return (int)($row['total'] ?? 0);
    }
}
