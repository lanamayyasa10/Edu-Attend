<<<<<<< HEAD
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
=======
<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>Absensi Siswa</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css">

</head>

<body>

<div class="container">

    <h2>DAFTAR HADIR SISWA</h2>

    <form
        id="filterForm"
        method="GET"
        action="index.php">

        <table class="form-table">

            <tr>

                <td width="180">

                    Guru

                </td>

                <td>

                    <select
                        name="guru"
                        id="guru"
                        onchange="document.getElementById('filterForm').submit();">

                        <?php foreach($guru as $g){ ?>

                            <option

                                value="<?= $g['id']; ?>"

                                <?= ($guru_id==$g['id']) ? 'selected' : ''; ?>

                            >

                                <?= htmlspecialchars($g['nama_guru']); ?>

                            </option>

                        <?php } ?>

                    </select>

                </td>

            </tr>

            <tr>

                <td>

                    Mata Pelajaran

                </td>

                <td>

                    <input

                        type="text"

                        class="readonly"

                        readonly

                        value="<?= htmlspecialchars($mapel['nama_mapel']); ?>"

                    >

                </td>

            </tr>

            <tr>

                <td>

                    Kelas

                </td>

                <td>

                    <select

                        name="kelas"

                        id="kelas"

                        onchange="document.getElementById('filterForm').submit();"

                    >

                        <?php foreach($kelas as $k){ ?>

                            <option

                                value="<?= $k['id']; ?>"

                                <?= ($kelas_id==$k['id']) ? 'selected' : ''; ?>

                            >

                                <?= htmlspecialchars($k['nama_kelas']); ?>

                            </option>

                        <?php } ?>

                    </select>

                </td>

            </tr>

            <tr>

                <td>

                    Tanggal

                </td>

                <td>

                    <input

                        type="date"

                        id="tanggal"

                        name="tanggal"

                        value="<?= $tanggal; ?>"

                        onchange="document.getElementById('filterForm').submit();"

                    >

                </td>

            </tr>

        </table>

    </form>

    <br>

    <table class="table-absensi">

        <thead>

            <tr>

                <th width="60">

                    No

                </th>

                <th>

                    Nama Murid

                </th>

                <th width="180">

                    Status

                </th>

            </tr>

        </thead>

        <tbody>

        <?php

        $no = 1;

        foreach($murid as $m){

        ?>

        <tr>

            <td align="center">

                <?= $no++; ?>

            </td>

            <td>

                <?= htmlspecialchars($m['nama_murid']); ?>

            </td>

            <td>

                <select

                    class="status"

                    data-header="<?= $header_id; ?>"

                    data-murid="<?= $m['id']; ?>"

                >

                    <option
                        value="Hadir"
                        <?= ($m['status']=="Hadir") ? 'selected' : ''; ?>
                    >
                        Hadir
                    </option>

                    <option
                        value="Izin"
                        <?= ($m['status']=="Izin") ? 'selected' : ''; ?>
                    >
                        Izin
                    </option>

                    <option
                        value="Sakit"
                        <?= ($m['status']=="Sakit") ? 'selected' : ''; ?>
                    >
                        Sakit
                    </option>

                    <option
                        value="Alpha"
                        <?= ($m['status']=="Alpha") ? 'selected' : ''; ?>
                    >
                        Alpha
                    </option>
                                        </select>

                </td>

            </tr>

        <?php } ?>

        </tbody>

    </table>

</div>

<script>

document.querySelectorAll(".status").forEach(function(item){

    item.addEventListener("change",function(){

        let formData = new FormData();

        formData.append(
            "header_id",
            this.dataset.header
        );

        formData.append(
            "murid_id",
            this.dataset.murid
        );

        formData.append(
            "status",
            this.value
        );

        fetch("index.php?action=simpan",{

            method:"POST",

            body:formData

        })

        .then(response=>response.json())

        .then(data=>{

            if(data.success){

                item.style.background="#d4edda";

                setTimeout(function(){

                    item.style.background="white";

                },500);

            }else{

                alert("Gagal menyimpan absensi.");

            }

        })

        .catch(function(){

            alert("Terjadi kesalahan koneksi.");

        });

    });

});

</script>

</body>

</html>
>>>>>>> 44d0f02d7378dfe1ba506b3ef4eeb64afd0a9587
