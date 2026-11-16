<?php

require_once __DIR__ . '/../../core/Model.php';

class AuthModel extends Model
{
    public function findByUsername(string $username): ?array
    {
        return $this->selectOne(
            'SELECT id, nama, username, password, role FROM users WHERE username = ? LIMIT 1',
            's',
            [$username]
        );
    }

    public function findById(int $id): ?array
    {
        return $this->selectOne(
            'SELECT id, nama, username, role, created_at FROM users WHERE id = ? LIMIT 1',
            'i',
            [$id]
        );
    }

    public function updatePassword(int $id, string $passwordHash): bool
    {
        return $this->executePrepared(
            'UPDATE users SET password = ? WHERE id = ?',
            'si',
            [$passwordHash, $id]
        );
    }
}
