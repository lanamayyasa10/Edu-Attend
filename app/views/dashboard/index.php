<?php
$pageTitle = 'Dashboard';
$pageSubtitle = 'Pantau ringkasan data dan aktivitas absensi sekolah.';
$activePage = 'dashboard';
require __DIR__ . '/../layouts/header.php';

$attendanceTotal = max(1, $today['tercatat']);
$attendanceRate = $today['tercatat'] > 0 ? (int)round(($today['hadir'] / $attendanceTotal) * 100) : 0;
?>
<section class="welcome-banner">
    <div>
        <h2>Selamat datang, <?= e($_SESSION['user_name'] ?? 'Administrator'); ?> 👋</h2>
        <p>Berikut ringkasan data sekolah dan aktivitas kehadiran yang tercatat.</p>
    </div>
    <div class="banner-art" aria-hidden="true">
        <div class="banner-orbit orbit-a"></div><div class="banner-orbit orbit-b"></div>
        <div class="banner-cap">✦</div>
        <div class="banner-book">▤</div>
    </div>
</section>

<div class="section-heading">
    <div><h2>Ringkasan data</h2><p>Jumlah data yang tersimpan di sistem.</p></div>
</div>

<div class="stats-grid">
    <a class="stat-card" href="<?= e(app_url('murid')); ?>">
        <div class="stat-icon icon-blue">♧</div>
        <div class="stat-label">Total Murid</div>
        <div class="stat-value"><?= number_format($counts['murid']); ?></div>
        <div class="stat-foot">Lihat data murid <span>→</span></div>
    </a>
    <a class="stat-card" href="<?= e(app_url('guru')); ?>">
        <div class="stat-icon icon-violet">♙</div>
        <div class="stat-label">Total Guru</div>
        <div class="stat-value"><?= number_format($counts['guru']); ?></div>
        <div class="stat-foot">Lihat data guru <span>→</span></div>
    </a>
    <a class="stat-card" href="<?= e(app_url('kelas')); ?>">
        <div class="stat-icon icon-orange">▤</div>
        <div class="stat-label">Total Kelas</div>
        <div class="stat-value"><?= number_format($counts['kelas']); ?></div>
        <div class="stat-foot">Lihat data kelas <span>→</span></div>
    </a>
    <a class="stat-card" href="<?= e(app_url('mapel')); ?>">
        <div class="stat-icon icon-green">▧</div>
        <div class="stat-label">Mata Pelajaran</div>
        <div class="stat-value"><?= number_format($counts['mapel']); ?></div>
        <div class="stat-foot">Lihat mata pelajaran <span>→</span></div>
    </a>
</div>

<div class="dashboard-grid">
    <section class="panel attendance-panel">
        <div class="panel-heading">
            <div><h2>Kehadiran hari ini</h2><p>Ringkasan absensi berdasarkan data yang sudah disimpan.</p></div>
            <a class="text-link" href="<?= e(app_url('absensi')); ?>">Buka absensi <span>→</span></a>
        </div>
        <div class="attendance-overview">
            <div class="attendance-donut" style="--progress: <?= $attendanceRate; ?>%">
                <div><strong><?= $attendanceRate; ?>%</strong><small>Hadir</small></div>
            </div>
            <div class="attendance-legend">
                <div><span class="legend-dot dot-present"></span><span>Hadir</span><strong><?= number_format($today['hadir']); ?></strong></div>
                <div><span class="legend-dot dot-permission"></span><span>Izin</span><strong><?= number_format($today['izin']); ?></strong></div>
                <div><span class="legend-dot dot-sick"></span><span>Sakit</span><strong><?= number_format($today['sakit']); ?></strong></div>
                <div><span class="legend-dot dot-absent"></span><span>Alpha</span><strong><?= number_format($today['alpha']); ?></strong></div>
            </div>
        </div>
        <div class="attendance-foot"><span><?= number_format($today['tercatat']); ?> catatan status tersimpan</span><span class="muted">Tanggal <?= e(date('d/m/Y')); ?></span></div>
    </section>

    <section class="panel quick-panel">
        <div class="panel-heading"><div><h2>Akses cepat</h2><p>Langsung ke aktivitas utama.</p></div></div>
        <a class="quick-action" href="<?= e(app_url('absensi')); ?>"><span class="quick-icon quick-blue">✓</span><span><strong>Catat kehadiran</strong><small>Input absensi siswa hari ini</small></span><b>→</b></a>
        <a class="quick-action" href="<?= e(app_url('murid')); ?>"><span class="quick-icon quick-purple">♧</span><span><strong>Tambah murid</strong><small>Kelola data peserta didik</small></span><b>→</b></a>
        <a class="quick-action" href="<?= e(app_url('laporan')); ?>"><span class="quick-icon quick-orange">▥</span><span><strong>Lihat laporan</strong><small>Periksa riwayat kehadiran</small></span><b>→</b></a>
    </section>
</div>

<section class="panel recent-panel">
    <div class="panel-heading">
        <div><h2>Aktivitas absensi terbaru</h2><p>Enam sesi absensi terakhir yang tercatat.</p></div>
        <a class="text-link" href="<?= e(app_url('laporan')); ?>">Lihat semua <span>→</span></a>
    </div>
    <?php if (empty($recent)): ?>
        <div class="empty-state"><div class="empty-icon">▤</div><strong>Belum ada aktivitas absensi</strong><p>Data sesi absensi akan muncul di sini setelah pencatatan dilakukan.</p><a class="btn btn-primary btn-sm" href="<?= e(app_url('absensi')); ?>">Mulai absensi</a></div>
    <?php else: ?>
        <div class="table-wrap">
            <table class="data-table">
                <thead><tr><th>Tanggal</th><th>Kelas</th><th>Mata Pelajaran</th><th>Guru</th><th>Hadir</th><th>Catatan</th></tr></thead>
                <tbody>
                <?php foreach ($recent as $row): ?>
                    <tr>
                        <td class="date-cell"><?= e(tanggal_indonesia($row['tanggal'])); ?></td>
                        <td><strong><?= e($row['nama_kelas']); ?></strong></td>
                        <td><?= e($row['nama_mapel']); ?></td>
                        <td><?= e($row['nama_guru']); ?></td>
                        <td><span class="status-pill status-hadir"><?= (int)$row['hadir']; ?> hadir</span></td>
                        <td><?= (int)$row['total_tercatat']; ?> murid</td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
