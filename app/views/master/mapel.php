<?php
$pageTitle = 'Mata Pelajaran';
$pageSubtitle = 'Kelola daftar mata pelajaran yang digunakan dalam kegiatan belajar.';
$activePage = 'mapel';
require __DIR__ . '/../layouts/header.php';
$isEdit = !empty($editData);
?>
<div class="content-grid master-grid">
    <section class="panel form-panel">
        <div class="panel-heading"><div><h2><?= $isEdit ? 'Ubah mata pelajaran' : 'Tambah mata pelajaran'; ?></h2><p><?= $isEdit ? 'Perbarui nama mata pelajaran.' : 'Tambahkan mata pelajaran baru.'; ?></p></div><span class="panel-step"><?= $isEdit ? 'EDIT' : 'FORM'; ?></span></div>
        <form method="POST" action="<?= e(app_url('mapel', ['action' => 'save'])); ?>" class="stack-form">
            <?= csrf_field(); ?><input type="hidden" name="id" value="<?= e($editData['id'] ?? ''); ?>">
            <div class="form-group"><label for="nama_mapel">Nama mata pelajaran <span class="required">*</span></label><input type="text" id="nama_mapel" name="nama_mapel" placeholder="Contoh: Matematika" value="<?= e($editData['nama_mapel'] ?? ''); ?>" maxlength="100" required></div>
            <div class="form-actions"><button class="btn btn-primary" type="submit"><?= $isEdit ? 'Simpan perubahan' : 'Tambah mapel'; ?></button><?php if ($isEdit): ?><a class="btn btn-light" href="<?= e(app_url('mapel')); ?>">Batal</a><?php endif; ?></div>
        </form>
    </section>
    <section class="panel table-panel">
        <div class="panel-heading"><div><h2>Daftar mata pelajaran</h2><p><?= count($items); ?> mata pelajaran tersedia.</p></div><span class="count-badge"><?= count($items); ?> Mapel</span></div>
        <?php if (empty($items)): ?><div class="empty-state compact"><div class="empty-icon">▧</div><strong>Belum ada mata pelajaran</strong><p>Tambahkan mata pelajaran melalui formulir.</p></div>
        <?php else: ?>
        <div class="table-wrap"><table class="data-table"><thead><tr><th style="width:60px">No.</th><th>Nama Mata Pelajaran</th><th>Guru Pengampu</th><th style="width:125px">Aksi</th></tr></thead><tbody>
        <?php foreach ($items as $index => $item): ?><tr><td class="row-number"><?= $index + 1; ?></td><td><div class="person-cell"><span class="person-avatar avatar-green">▧</span><strong><?= e($item['nama_mapel']); ?></strong></div></td><td><span class="count-inline"><?= (int)$item['jumlah_guru']; ?> guru</span></td><td><div class="action-group"><a class="icon-action edit-action" href="<?= e(app_url('mapel', ['edit' => $item['id']])); ?>" title="Ubah">✎</a><form method="POST" action="<?= e(app_url('mapel', ['action' => 'delete'])); ?>" data-confirm="Hapus mata pelajaran ini? Mapel yang masih digunakan guru atau riwayat absensi tidak dapat dihapus."><?= csrf_field(); ?><input type="hidden" name="id" value="<?= (int)$item['id']; ?>"><button class="icon-action delete-action" type="submit" title="Hapus">×</button></form></div></td></tr><?php endforeach; ?>
        </tbody></table></div><?php endif; ?>
    </section>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
