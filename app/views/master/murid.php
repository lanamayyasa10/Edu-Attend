<?php
$pageTitle = 'Data Murid';
$pageSubtitle = 'Kelola daftar murid dan penempatan kelas masing-masing.';
$activePage = 'murid';
require __DIR__ . '/../layouts/header.php';
$isEdit = !empty($editData);
?>
<div class="content-grid master-grid">
    <section class="panel form-panel">
        <div class="panel-heading"><div><h2><?= $isEdit ? 'Ubah data murid' : 'Tambah murid'; ?></h2><p><?= $isEdit ? 'Perbarui informasi murid.' : 'Masukkan nama dan kelas murid.'; ?></p></div><span class="panel-step"><?= $isEdit ? 'EDIT' : 'FORM'; ?></span></div>
        <?php if (empty($kelasList)): ?>
            <div class="notice-box">Kamu perlu membuat data kelas terlebih dahulu sebelum menambahkan murid.</div>
            <a class="btn btn-primary" href="<?= e(app_url('kelas')); ?>">Kelola kelas</a>
        <?php else: ?>
        <form method="POST" action="<?= e(app_url('murid', ['action' => 'save'])); ?>" class="stack-form">
            <?= csrf_field(); ?><input type="hidden" name="id" value="<?= e($editData['id'] ?? ''); ?>">
            <div class="form-group"><label for="nama_murid">Nama lengkap murid <span class="required">*</span></label><input type="text" id="nama_murid" name="nama_murid" placeholder="Contoh: Ahmad Rizky Pratama" value="<?= e($editData['nama_murid'] ?? ''); ?>" maxlength="120" required></div>
            <div class="form-group"><label for="kelas_id">Kelas <span class="required">*</span></label><select id="kelas_id" name="kelas_id" required><option value="">Pilih kelas</option><?php foreach ($kelasList as $kelas): ?><option value="<?= (int)$kelas['id']; ?>" <?= (int)($editData['kelas_id'] ?? 0) === (int)$kelas['id'] ? 'selected' : ''; ?>><?= e($kelas['nama_kelas']); ?></option><?php endforeach; ?></select></div>
            <div class="form-actions"><button class="btn btn-primary" type="submit"><?= $isEdit ? 'Simpan perubahan' : 'Tambah murid'; ?></button><?php if ($isEdit): ?><a class="btn btn-light" href="<?= e(app_url('murid')); ?>">Batal</a><?php endif; ?></div>
        </form>
        <?php endif; ?>
    </section>
    <section class="panel table-panel">
        <div class="panel-heading"><div><h2>Daftar murid</h2><p><?= count($items); ?> murid terdaftar dalam sistem.</p></div><span class="count-badge"><?= count($items); ?> Murid</span></div>
        <?php if (empty($items)): ?><div class="empty-state compact"><div class="empty-icon">♧</div><strong>Belum ada data murid</strong><p>Tambahkan murid melalui formulir di samping.</p></div>
        <?php else: ?>
        <div class="table-wrap"><table class="data-table"><thead><tr><th style="width:60px">No.</th><th>Nama Murid</th><th>Kelas</th><th style="width:125px">Aksi</th></tr></thead><tbody>
        <?php foreach ($items as $index => $item): ?><tr><td class="row-number"><?= $index + 1; ?></td><td><div class="person-cell"><span class="person-avatar avatar-blue"><?= e(strtoupper(substr($item['nama_murid'], 0, 1))); ?></span><strong><?= e($item['nama_murid']); ?></strong></div></td><td><span class="class-tag"><?= e($item['nama_kelas']); ?></span></td><td><div class="action-group"><a class="icon-action edit-action" href="<?= e(app_url('murid', ['edit' => $item['id']])); ?>" title="Ubah">✎</a><form method="POST" action="<?= e(app_url('murid', ['action' => 'delete'])); ?>" data-confirm="Hapus data murid ini? Murid yang sudah memiliki catatan absensi tidak dapat dihapus."><?= csrf_field(); ?><input type="hidden" name="id" value="<?= (int)$item['id']; ?>"><button class="icon-action delete-action" type="submit" title="Hapus">×</button></form></div></td></tr><?php endforeach; ?>
        </tbody></table></div><?php endif; ?>
    </section>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
