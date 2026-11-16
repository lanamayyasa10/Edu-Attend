<?php

require_once __DIR__ . '/../../core/Model.php';

class MapelModel extends Model
{
    public function getAll(): array
    {
        return $this->selectAll(
            'SELECT m.*,
                (SELECT COUNT(*) FROM guru_mapel gm WHERE gm.mapel_id = m.id) AS jumlah_guru
             FROM mapel m ORDER BY m.nama_mapel ASC'
        );
    }

    public function getById(int $id): ?array
    {
        return $this->selectOne('SELECT * FROM mapel WHERE id = ?', 'i', [$id]);
    }

    public function save(?int $id, string $nama): bool
    {
        if ($id) {
            return $this->executePrepared('UPDATE mapel SET nama_mapel = ? WHERE id = ?', 'si', [$nama, $id]);
        }

        return $this->executePrepared('INSERT INTO mapel (nama_mapel) VALUES (?)', 's', [$nama]);
    }

    public function delete(int $id): bool
    {
        $guruCount = $this->countRows('SELECT COUNT(*) AS total FROM guru_mapel WHERE mapel_id = ?', 'i', [$id]);
        $attendanceCount = $this->countRows('SELECT COUNT(*) AS total FROM absensi_header WHERE mapel_id = ?', 'i', [$id]);

        if ($guruCount > 0 || $attendanceCount > 0) {
            return false;
        }

        return $this->executePrepared('DELETE FROM mapel WHERE id = ?', 'i', [$id]);
    }
}
