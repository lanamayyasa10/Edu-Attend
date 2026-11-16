<?php
$pageTitle = 'Absensi Siswa';
$pageSubtitle = 'Catat dan perbarui kehadiran murid berdasarkan guru, kelas, dan tanggal.';
$activePage = 'absensi';
require __DIR__ . '/../layouts/header.php';
$totalMurid = count($murid);
?>
<section class="panel filter-panel">
    <div class="filter-heading"><div><span class="filter-icon">⌕</span><div><h2>Atur sesi absensi</h2><p>Pilih informasi pembelajaran untuk menampilkan daftar murid.</p></div></div><span class="live-indicator"><i></i> FORM ABSENSI</span></div>
    <form method="GET" action="index.php" class="filter-grid">
        <input type="hidden" name="page" value="absensi">
        <div class="form-group"><label for="guru">Guru pengajar</label><select name="guru" id="guru" onchange="this.form.submit()"><option value="">Pilih guru</option><?php foreach ($guruList as $guru): ?><option value="<?= (int)$guru['id']; ?>" <?= $guruId === (int)$guru['id'] ? 'selected' : ''; ?>><?= e($guru['nama_guru']); ?></option><?php endforeach; ?></select></div>
        <div class="form-group"><label for="kelas">Kelas</label><select name="kelas" id="kelas" onchange="this.form.submit()"><option value="">Pilih kelas</option><?php foreach ($kelasList as $kelas): ?><option value="<?= (int)$kelas['id']; ?>" <?= $kelasId === (int)$kelas['id'] ? 'selected' : ''; ?>><?= e($kelas['nama_kelas']); ?></option><?php endforeach; ?></select></div>
        <div class="form-group"><label for="tanggal">Tanggal absensi</label><input type="date" name="tanggal" id="tanggal" value="<?= e($tanggal); ?>" onchange="this.form.submit()"></div>
    </form>
    <?php if ($mapel): ?>
        <div class="session-meta"><span><small>MATA PELAJARAN</small><strong><?= e($mapel['nama_mapel']); ?></strong></span><span><small>STATUS SESI</small><strong class="session-active"><i></i> Siap dicatat</strong></span></div>
    <?php endif; ?>
</section>

<?php if (empty($guruList) || empty($kelasList)): ?>
    <div class="panel empty-state"><div class="empty-icon">⚑</div><strong>Data guru atau kelas belum tersedia</strong><p>Tambahkan data guru, mata pelajaran, dan kelas sebelum memulai pencatatan absensi.</p><a class="btn btn-primary btn-sm" href="<?= e(app_url('guru')); ?>">Lengkapi data master</a></div>
<?php elseif (!$mapel): ?>
    <div class="notice-box notice-large"><strong>Guru belum memiliki mata pelajaran.</strong><p>Atur mata pelajaran pada menu Data Guru terlebih dahulu. Satu guru menggunakan satu mata pelajaran utama untuk sesi absensi ini.</p><a class="btn btn-primary btn-sm" href="<?= e(app_url('guru')); ?>">Atur data guru</a></div>
<?php else: ?>
    <div class="attendance-stats">
        <div class="mini-stat"><span class="mini-stat-icon mini-blue">♧</span><div><small>Total murid</small><strong><?= $totalMurid; ?></strong></div></div>
        <div class="mini-stat"><span class="mini-stat-icon mini-green">✓</span><div><small>Hadir</small><strong><?= $summary['hadir']; ?></strong></div></div>
        <div class="mini-stat"><span class="mini-stat-icon mini-yellow">◷</span><div><small>Izin</small><strong><?= $summary['izin']; ?></strong></div></div>
        <div class="mini-stat"><span class="mini-stat-icon mini-red">!</span><div><small>Sakit / Alpha</small><strong><?= $summary['sakit'] + $summary['alpha']; ?></strong></div></div>
    </div>

    <section class="panel attendance-table-panel">
        <div class="panel-heading attendance-table-heading">
            <div><h2>Daftar kehadiran murid</h2><p>Perubahan status disimpan otomatis saat pilihan diubah.</p></div>
            <div class="attendance-heading-right"><span class="class-tag"><?= e($kelasNama ?: 'Kelas'); ?></span><span class="date-chip"><?= e(tanggal_indonesia($tanggal)); ?></span></div>
        </div>
        <?php if (empty($murid)): ?>
            <div class="empty-state compact"><div class="empty-icon">♧</div><strong>Belum ada murid di kelas ini</strong><p>Tambahkan murid terlebih dahulu untuk mengisi absensi.</p><a class="btn btn-primary btn-sm" href="<?= e(app_url('murid')); ?>">Tambah murid</a></div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="data-table attendance-table">
                    <thead><tr><th style="width:70px">No.</th><th>Nama Murid</th><th style="width:220px">Status Kehadiran</th><th style="width:130px">Penyimpanan</th></tr></thead>
                    <tbody>
                    <?php foreach ($murid as $index => $student): ?>
                        <tr>
                            <td class="row-number"><?= $index + 1; ?></td>
                            <td><div class="person-cell"><span class="person-avatar avatar-blue"><?= e(strtoupper(substr($student['nama_murid'], 0, 1))); ?></span><strong><?= e($student['nama_murid']); ?></strong></div></td>
                            <td>
                                <select class="status-select status-<?= strtolower($student['status']); ?>" data-header="<?= (int)$headerId; ?>" data-murid="<?= (int)$student['id']; ?>" data-csrf="<?= e(csrf_token()); ?>">
                                    <?php foreach (['Hadir', 'Izin', 'Sakit', 'Alpha'] as $status): ?>
                                        <option value="<?= e($status); ?>" <?= $student['status'] === $status ? 'selected' : ''; ?>><?= e($status); ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td><span class="save-state saved"><i></i> Tersimpan</span></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="table-bottom-note"><span><i class="legend-dot dot-present"></i> Hadir <i class="legend-dot dot-permission"></i> Izin <i class="legend-dot dot-sick"></i> Sakit <i class="legend-dot dot-absent"></i> Alpha</span><span id="saveFeedback">Perubahan tersimpan otomatis.</span></div>
        <?php endif; ?>
    </section>
<?php endif; ?>

<?php require __DIR__ . '/../layouts/footer.php'; ?>
