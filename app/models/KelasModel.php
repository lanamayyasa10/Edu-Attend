<?php

require_once __DIR__ . '/../../core/Model.php';

class KelasModel extends Model
{
    public function getAll(): array
    {
        return $this->selectAll(
            'SELECT k.*, (SELECT COUNT(*) FROM murid s WHERE s.kelas_id = k.id) AS jumlah_murid
             FROM kelas k ORDER BY k.nama_kelas ASC'
        );
    }

    public function getById(int $id): ?array
    {
        return $this->selectOne('SELECT * FROM kelas WHERE id = ?', 'i', [$id]);
    }

    public function save(?int $id, string $nama): bool
    {
        if ($id) {
            return $this->executePrepared('UPDATE kelas SET nama_kelas = ? WHERE id = ?', 'si', [$nama, $id]);
        }

        return $this->executePrepared('INSERT INTO kelas (nama_kelas) VALUES (?)', 's', [$nama]);
    }

    public function delete(int $id): bool
    {
        $muridCount = $this->countRows('SELECT COUNT(*) AS total FROM murid WHERE kelas_id = ?', 'i', [$id]);
        $attendanceCount = $this->countRows('SELECT COUNT(*) AS total FROM absensi_header WHERE kelas_id = ?', 'i', [$id]);

        if ($muridCount > 0 || $attendanceCount > 0) {
            return false;
        }

        return $this->executePrepared('DELETE FROM kelas WHERE id = ?', 'i', [$id]);
    }
}
