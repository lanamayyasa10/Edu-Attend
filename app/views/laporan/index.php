<?php
$pageTitle = 'Laporan Absensi';
$pageSubtitle = 'Tinjau riwayat pencatatan kehadiran berdasarkan rentang tanggal dan kelas.';
$activePage = 'laporan';
require __DIR__ . '/../layouts/header.php';
$totalHadir = array_sum(array_map(fn($row) => (int)$row['hadir'], $items));
$totalIzin = array_sum(array_map(fn($row) => (int)$row['izin'], $items));
$totalSakit = array_sum(array_map(fn($row) => (int)$row['sakit'], $items));
$totalAlpha = array_sum(array_map(fn($row) => (int)$row['alpha'], $items));
?>
<section class="panel filter-panel report-filter">
    <div class="filter-heading"><div><span class="filter-icon">⌕</span><div><h2>Filter laporan</h2><p>Tentukan periode dan kelas yang ingin ditampilkan.</p></div></div></div>
    <form method="GET" action="index.php" class="filter-grid report-filter-grid">
        <input type="hidden" name="page" value="laporan">
        <div class="form-group"><label for="from">Dari tanggal</label><input type="date" id="from" name="from" value="<?= e($from); ?>"></div>
        <div class="form-group"><label for="to">Sampai tanggal</label><input type="date" id="to" name="to" value="<?= e($to); ?>"></div>
        <div class="form-group"><label for="kelas">Kelas</label><select id="kelas" name="kelas"><option value="0">Semua kelas</option><?php foreach ($kelasList as $kelas): ?><option value="<?= (int)$kelas['id']; ?>" <?= $kelasId === (int)$kelas['id'] ? 'selected' : ''; ?>><?= e($kelas['nama_kelas']); ?></option><?php endforeach; ?></select></div>
        <div class="filter-submit"><button class="btn btn-primary" type="submit">Tampilkan laporan</button></div>
    </form>
</section>

<div class="report-summary">
    <div class="report-metric"><span class="legend-dot dot-present"></span><div><small>Total hadir</small><strong><?= $totalHadir; ?></strong></div></div>
    <div class="report-metric"><span class="legend-dot dot-permission"></span><div><small>Total izin</small><strong><?= $totalIzin; ?></strong></div></div>
    <div class="report-metric"><span class="legend-dot dot-sick"></span><div><small>Total sakit</small><strong><?= $totalSakit; ?></strong></div></div>
    <div class="report-metric"><span class="legend-dot dot-absent"></span><div><small>Total alpha</small><strong><?= $totalAlpha; ?></strong></div></div>
</div>

<section class="panel recent-panel">
    <div class="panel-heading">
        <div><h2>Riwayat sesi absensi</h2><p><?= count($items); ?> sesi ditemukan pada periode <?= e(tanggal_indonesia($from)); ?> – <?= e(tanggal_indonesia($to)); ?>.</p></div>
        <button class="btn btn-light btn-sm" type="button" onclick="window.print()">Cetak laporan</button>
    </div>
    <?php if (empty($items)): ?>
        <div class="empty-state"><div class="empty-icon">▥</div><strong>Belum ada laporan pada periode ini</strong><p>Coba ubah rentang tanggal atau mulai catat absensi siswa.</p><a class="btn btn-primary btn-sm" href="<?= e(app_url('absensi')); ?>">Buka absensi</a></div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>Tanggal</th><th>Kelas</th><th>Mata Pelajaran</th><th>Guru</th><th>Hadir</th><th>Izin</th><th>Sakit</th><th>Alpha</th><th>Aksi</th></tr></thead>
                <tbody>
                <?php foreach ($items as $row): ?>
                    <tr>
                        <td class="date-cell"><?= e(tanggal_indonesia($row['tanggal'])); ?></td>
                        <td><span class="class-tag"><?= e($row['nama_kelas']); ?></span></td>
                        <td><?= e($row['nama_mapel']); ?></td>
                        <td><?= e($row['nama_guru']); ?></td>
                        <td><span class="status-pill status-hadir"><?= (int)$row['hadir']; ?></span></td>
                        <td><span class="status-pill status-izin"><?= (int)$row['izin']; ?></span></td>
                        <td><span class="status-pill status-sakit"><?= (int)$row['sakit']; ?></span></td>
                        <td><span class="status-pill status-alpha"><?= (int)$row['alpha']; ?></span></td>
                        <td><form method="POST" action="<?= e(app_url('laporan', ['action' => 'delete'])); ?>" data-confirm="Hapus sesi absensi ini beserta seluruh detail status murid? Tindakan ini tidak dapat dibatalkan."><?= csrf_field(); ?><input type="hidden" name="id" value="<?= (int)$row['id']; ?>"><button class="icon-action delete-action" type="submit" title="Hapus sesi">×</button></form></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
