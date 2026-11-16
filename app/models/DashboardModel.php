<?php

require_once __DIR__ . '/../../core/Model.php';

class DashboardModel extends Model
{
    public function getCounts(): array
    {
        return [
            'murid' => $this->countRows('SELECT COUNT(*) AS total FROM murid'),
            'guru' => $this->countRows('SELECT COUNT(*) AS total FROM guru'),
            'kelas' => $this->countRows('SELECT COUNT(*) AS total FROM kelas'),
            'mapel' => $this->countRows('SELECT COUNT(*) AS total FROM mapel'),
        ];
    }

    public function getTodayAttendance(): array
    {
        $row = $this->selectOne(
            "SELECT
                COUNT(ad.id) AS tercatat,
                SUM(ad.status = 'Hadir') AS hadir,
                SUM(ad.status = 'Izin') AS izin,
                SUM(ad.status = 'Sakit') AS sakit,
                SUM(ad.status = 'Alpha') AS alpha
             FROM absensi_header ah
             LEFT JOIN absensi_detail ad ON ad.header_id = ah.id
             WHERE ah.tanggal = CURDATE()"
        ) ?? [];

        return [
            'tercatat' => (int)($row['tercatat'] ?? 0),
            'hadir' => (int)($row['hadir'] ?? 0),
            'izin' => (int)($row['izin'] ?? 0),
            'sakit' => (int)($row['sakit'] ?? 0),
            'alpha' => (int)($row['alpha'] ?? 0),
        ];
    }

    public function getRecentAttendance(): array
    {
        return $this->selectAll(
            "SELECT
                ah.id, ah.tanggal, k.nama_kelas, g.nama_guru, m.nama_mapel,
                COUNT(ad.id) AS total_tercatat,
                SUM(ad.status = 'Hadir') AS hadir
             FROM absensi_header ah
             INNER JOIN kelas k ON k.id = ah.kelas_id
             INNER JOIN guru g ON g.id = ah.guru_id
             INNER JOIN mapel m ON m.id = ah.mapel_id
             LEFT JOIN absensi_detail ad ON ad.header_id = ah.id
             GROUP BY ah.id
             ORDER BY ah.tanggal DESC, ah.id DESC
             LIMIT 6"
        );
    }
}
