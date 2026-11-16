<<<<<<< HEAD
<?php

require_once __DIR__ . '/../../core/Model.php';

class AbsensiModel extends Model
{
    public const STATUSES = ['Hadir', 'Izin', 'Sakit', 'Alpha'];

    public function getHeaderId(int $kelasId, int $guruId, int $mapelId, string $tanggal): int
    {
        $header = $this->selectOne(
            'SELECT id FROM absensi_header
             WHERE kelas_id = ? AND guru_id = ? AND mapel_id = ? AND tanggal = ?
             LIMIT 1',
            'iiis',
            [$kelasId, $guruId, $mapelId, $tanggal]
        );

        if ($header) {
            return (int)$header['id'];
        }

        return $this->insertPrepared(
            'INSERT INTO absensi_header (kelas_id, guru_id, mapel_id, tanggal) VALUES (?, ?, ?, ?)',
            'iiis',
            [$kelasId, $guruId, $mapelId, $tanggal]
        );
    }

    public function ensureDefaultStatuses(int $headerId, int $kelasId): void
    {
        // Selaras dengan perilaku aplikasi awal: murid yang belum diubah berstatus Hadir.
        // Status awal dibuat sekali agar ringkasan dan laporan tetap konsisten.
        $this->executePrepared(
            "INSERT INTO absensi_detail (header_id, murid_id, status)
             SELECT ?, s.id, 'Hadir'
             FROM murid s
             WHERE s.kelas_id = ?
               AND NOT EXISTS (
                    SELECT 1 FROM absensi_detail d
                    WHERE d.header_id = ? AND d.murid_id = s.id
               )",
            'iii',
            [$headerId, $kelasId, $headerId]
        );
    }

    public function getStatusMap(int $headerId): array
    {
        $rows = $this->selectAll(
            'SELECT murid_id, status FROM absensi_detail WHERE header_id = ?',
            'i',
            [$headerId]
        );

        $statuses = [];
        foreach ($rows as $row) {
            $statuses[(int)$row['murid_id']] = $row['status'];
        }

        return $statuses;
    }

    public function save(int $headerId, int $muridId, string $status): bool
    {
        if (!in_array($status, self::STATUSES, true)) {
            return false;
        }

        $valid = $this->selectOne(
            'SELECT h.id
             FROM absensi_header h
             INNER JOIN murid s ON s.kelas_id = h.kelas_id
             WHERE h.id = ? AND s.id = ?',
            'ii',
            [$headerId, $muridId]
        );

        if (!$valid) {
            return false;
        }

        $existing = $this->selectOne(
            'SELECT id FROM absensi_detail WHERE header_id = ? AND murid_id = ? LIMIT 1',
            'ii',
            [$headerId, $muridId]
        );

        if ($existing) {
            return $this->executePrepared(
                'UPDATE absensi_detail SET status = ? WHERE id = ?',
                'si',
                [$status, (int)$existing['id']]
            );
        }

        return $this->executePrepared(
            'INSERT INTO absensi_detail (header_id, murid_id, status) VALUES (?, ?, ?)',
            'iis',
            [$headerId, $muridId, $status]
        );
    }

    public function getSummaryByDate(string $tanggal, int $kelasId = 0): array
    {
        $sql = "SELECT
                    COUNT(ad.id) AS tercatat,
                    SUM(ad.status = 'Hadir') AS hadir,
                    SUM(ad.status = 'Izin') AS izin,
                    SUM(ad.status = 'Sakit') AS sakit,
                    SUM(ad.status = 'Alpha') AS alpha
                FROM absensi_header ah
                LEFT JOIN absensi_detail ad ON ad.header_id = ah.id
                WHERE ah.tanggal = ?";
        $types = 's';
        $params = [$tanggal];

        if ($kelasId > 0) {
            $sql .= ' AND ah.kelas_id = ?';
            $types .= 'i';
            $params[] = $kelasId;
        }

        $row = $this->selectOne($sql, $types, $params) ?? [];
        return [
            'tercatat' => (int)($row['tercatat'] ?? 0),
            'hadir' => (int)($row['hadir'] ?? 0),
            'izin' => (int)($row['izin'] ?? 0),
            'sakit' => (int)($row['sakit'] ?? 0),
            'alpha' => (int)($row['alpha'] ?? 0),
        ];
    }

    public function getHistory(string $from, string $to, int $kelasId = 0): array
    {
        $sql = "SELECT
                    ah.id,
                    ah.tanggal,
                    k.nama_kelas,
                    g.nama_guru,
                    m.nama_mapel,
                    COUNT(ad.id) AS total_tercatat,
                    SUM(ad.status = 'Hadir') AS hadir,
                    SUM(ad.status = 'Izin') AS izin,
                    SUM(ad.status = 'Sakit') AS sakit,
                    SUM(ad.status = 'Alpha') AS alpha
                FROM absensi_header ah
                INNER JOIN kelas k ON k.id = ah.kelas_id
                INNER JOIN guru g ON g.id = ah.guru_id
                INNER JOIN mapel m ON m.id = ah.mapel_id
                LEFT JOIN absensi_detail ad ON ad.header_id = ah.id
                WHERE ah.tanggal BETWEEN ? AND ?";
        $types = 'ss';
        $params = [$from, $to];

        if ($kelasId > 0) {
            $sql .= ' AND ah.kelas_id = ?';
            $types .= 'i';
            $params[] = $kelasId;
        }

        $sql .= ' GROUP BY ah.id ORDER BY ah.tanggal DESC, ah.id DESC';

        return $this->selectAll($sql, $types, $params);
    }

    public function deleteHeader(int $headerId): bool
    {
        $this->db->begin_transaction();

        try {
            $this->executePrepared('DELETE FROM absensi_detail WHERE header_id = ?', 'i', [$headerId]);
            $this->executePrepared('DELETE FROM absensi_header WHERE id = ?', 'i', [$headerId]);
            $this->db->commit();
            return true;
        } catch (Throwable $error) {
            $this->db->rollback();
            throw $error;
        }
    }
}
=======
<?php

require_once __DIR__ . "/../../core/Model.php";

class AbsensiModel extends Model
{

    /**
     * Cari Header Absensi
     */
    public function getHeader($kelas_id,$guru_id,$mapel_id,$tanggal)
    {

        $kelas_id = (int)$kelas_id;
        $guru_id  = (int)$guru_id;
        $mapel_id = (int)$mapel_id;

        $tanggal = $this->escape($tanggal);

        $sql = "SELECT *
                FROM absensi_header
                WHERE
                    kelas_id='$kelas_id'
                AND guru_id='$guru_id'
                AND mapel_id='$mapel_id'
                AND tanggal='$tanggal'";

        $result = $this->query($sql);

        return $this->fetch($result);

    }

    /**
     * Membuat Header Baru
     */
    public function createHeader($kelas_id,$guru_id,$mapel_id,$tanggal)
    {

        $kelas_id = (int)$kelas_id;
        $guru_id  = (int)$guru_id;
        $mapel_id = (int)$mapel_id;

        $tanggal = $this->escape($tanggal);

        $sql = "INSERT INTO absensi_header
                (
                    kelas_id,
                    guru_id,
                    mapel_id,
                    tanggal
                )
                VALUES
                (
                    '$kelas_id',
                    '$guru_id',
                    '$mapel_id',
                    '$tanggal'
                )";

        $this->execute($sql);

        return $this->lastInsertId();

    }

    /**
     * Ambil Header ID
     * Kalau belum ada otomatis dibuat
     */
    public function getHeaderId($kelas_id,$guru_id,$mapel_id,$tanggal)
    {

        $header = $this->getHeader(
            $kelas_id,
            $guru_id,
            $mapel_id,
            $tanggal
        );

        if($header){

            return $header['id'];

        }

        return $this->createHeader(
            $kelas_id,
            $guru_id,
            $mapel_id,
            $tanggal
        );

    }

    /**
     * Simpan / Update Absensi
     */
    public function save($header_id,$murid_id,$status)
    {

        $header_id = (int)$header_id;
        $murid_id  = (int)$murid_id;

        $status = $this->escape($status);

        $cek = $this->query("
            SELECT id
            FROM absensi_detail
            WHERE
                header_id='$header_id'
            AND murid_id='$murid_id'
        ");

        if($this->rowCount($cek)>0){

            $sql = "
                UPDATE absensi_detail
                SET status='$status'
                WHERE
                    header_id='$header_id'
                AND murid_id='$murid_id'
            ";

        }else{

            $sql = "
                INSERT INTO absensi_detail
                (
                    header_id,
                    murid_id,
                    status
                )
                VALUES
                (
                    '$header_id',
                    '$murid_id',
                    '$status'
                )
            ";

        }

        return $this->execute($sql);

    }

    /**
     * Ambil Status Murid
     */
    public function getStatus($header_id,$murid_id)
    {

        $header_id = (int)$header_id;
        $murid_id  = (int)$murid_id;

        $sql = "
            SELECT status
            FROM absensi_detail
            WHERE
                header_id='$header_id'
            AND murid_id='$murid_id'
        ";

        $result = $this->query($sql);

        if($this->rowCount($result)>0){

            $row = $this->fetch($result);

            return $row['status'];

        }

        return "Hadir";

    }

}
>>>>>>> 44d0f02d7378dfe1ba506b3ef4eeb64afd0a9587
