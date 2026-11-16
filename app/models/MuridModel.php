<?php

require_once __DIR__ . '/../../core/Model.php';

class MuridModel extends Model
{
    public function getAll(): array
    {
        return $this->selectAll(
            'SELECT s.*, k.nama_kelas
             FROM murid s
             INNER JOIN kelas k ON s.kelas_id = k.id
             ORDER BY k.nama_kelas ASC, s.nama_murid ASC'
        );
    }

    public function getByKelas(int $kelasId): array
    {
        return $this->selectAll(
            'SELECT * FROM murid WHERE kelas_id = ? ORDER BY nama_murid ASC',
            'i',
            [$kelasId]
        );
    }

    public function getById(int $id): ?array
    {
        return $this->selectOne('SELECT * FROM murid WHERE id = ?', 'i', [$id]);
    }

    public function save(?int $id, string $nama, int $kelasId): bool
    {
        if ($id) {
            $current = $this->selectOne('SELECT kelas_id FROM murid WHERE id = ?', 'i', [$id]);
            if ($current && (int)$current['kelas_id'] !== $kelasId) {
                $hasHistory = $this->countRows(
                    'SELECT COUNT(*) AS total FROM absensi_detail WHERE murid_id = ?',
                    'i',
                    [$id]
                );
                if ($hasHistory > 0) {
                    return false;
                }
            }

            return $this->executePrepared(
                'UPDATE murid SET nama_murid = ?, kelas_id = ? WHERE id = ?',
                'sii',
                [$nama, $kelasId, $id]
            );
        }

        return $this->executePrepared(
            'INSERT INTO murid (nama_murid, kelas_id) VALUES (?, ?)',
            'si',
            [$nama, $kelasId]
        );
    }

    public function delete(int $id): bool
    {
        $attendanceCount = $this->countRows('SELECT COUNT(*) AS total FROM absensi_detail WHERE murid_id = ?', 'i', [$id]);

        if ($attendanceCount > 0) {
            return false;
        }

        return $this->executePrepared('DELETE FROM murid WHERE id = ?', 'i', [$id]);
    }
}
