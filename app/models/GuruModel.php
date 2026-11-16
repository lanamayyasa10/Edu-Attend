<?php

require_once __DIR__ . '/../../core/Model.php';

class GuruModel extends Model
{
    public function getAll(): array
    {
        return $this->selectAll(
            "SELECT
                g.id,
                g.nama_guru,
                (
                    SELECT m.nama_mapel
                    FROM guru_mapel gm
                    INNER JOIN mapel m ON m.id = gm.mapel_id
                    WHERE gm.guru_id = g.id
                    ORDER BY m.nama_mapel ASC
                    LIMIT 1
                ) AS nama_mapel,
                (
                    SELECT gm.mapel_id
                    FROM guru_mapel gm
                    WHERE gm.guru_id = g.id
                    ORDER BY gm.mapel_id ASC
                    LIMIT 1
                ) AS mapel_id
             FROM guru g
             ORDER BY g.nama_guru ASC"
        );
    }

    public function getById(int $id): ?array
    {
        return $this->selectOne(
            "SELECT g.id, g.nama_guru,
                (SELECT gm.mapel_id FROM guru_mapel gm WHERE gm.guru_id = g.id ORDER BY gm.mapel_id ASC LIMIT 1) AS mapel_id
             FROM guru g WHERE g.id = ?",
            'i',
            [$id]
        );
    }

    public function save(?int $id, string $nama, ?int $mapelId): bool
    {
        $this->db->begin_transaction();

        try {
            if ($id) {
                $this->executePrepared(
                    'UPDATE guru SET nama_guru = ? WHERE id = ?',
                    'si',
                    [$nama, $id]
                );
                $guruId = $id;
            } else {
                $guruId = $this->insertPrepared(
                    'INSERT INTO guru (nama_guru) VALUES (?)',
                    's',
                    [$nama]
                );
            }

            $this->executePrepared('DELETE FROM guru_mapel WHERE guru_id = ?', 'i', [$guruId]);

            if ($mapelId) {
                $this->executePrepared(
                    'INSERT INTO guru_mapel (guru_id, mapel_id) VALUES (?, ?)',
                    'ii',
                    [$guruId, $mapelId]
                );
            }

            $this->db->commit();
            return true;
        } catch (Throwable $error) {
            $this->db->rollback();
            throw $error;
        }
    }

    public function delete(int $id): bool
    {
        if ($this->countRows('SELECT COUNT(*) AS total FROM absensi_header WHERE guru_id = ?', 'i', [$id]) > 0) {
            return false;
        }

        $this->db->begin_transaction();

        try {
            $this->executePrepared('DELETE FROM guru_mapel WHERE guru_id = ?', 'i', [$id]);
            $this->executePrepared('DELETE FROM guru WHERE id = ?', 'i', [$id]);
            $this->db->commit();
            return true;
        } catch (Throwable $error) {
            $this->db->rollback();
            throw $error;
        }
    }

    public function getMapel(int $guruId): ?array
    {
        return $this->selectOne(
            'SELECT m.id, m.nama_mapel
             FROM guru_mapel gm
             INNER JOIN mapel m ON m.id = gm.mapel_id
             WHERE gm.guru_id = ?
             ORDER BY m.nama_mapel ASC
             LIMIT 1',
            'i',
            [$guruId]
        );
    }
}
