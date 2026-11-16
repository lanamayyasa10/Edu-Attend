<?php
$pageTitle = 'Data Kelas';
$pageSubtitle = 'Atur daftar kelas dan pantau jumlah murid di setiap kelas.';
$activePage = 'kelas';
require __DIR__ . '/../layouts/header.php';
$isEdit = !empty($editData);
?>
<div class="content-grid master-grid">
    <section class="panel form-panel">
        <div class="panel-heading"><div><h2><?= $isEdit ? 'Ubah data kelas' : 'Tambah kelas'; ?></h2><p><?= $isEdit ? 'Perbarui nama kelas.' : 'Daftarkan kelas baru ke sistem.'; ?></p></div><span class="panel-step"><?= $isEdit ? 'EDIT' : 'FORM'; ?></span></div>
        <form method="POST" action="<?= e(app_url('kelas', ['action' => 'save'])); ?>" class="stack-form">
            <?= csrf_field(); ?><input type="hidden" name="id" value="<?= e($editData['id'] ?? ''); ?>">
            <div class="form-group"><label for="nama_kelas">Nama kelas <span class="required">*</span></label><input type="text" id="nama_kelas" name="nama_kelas" placeholder="Contoh: XII IPA 1" value="<?= e($editData['nama_kelas'] ?? ''); ?>" maxlength="80" required></div>
            <div class="form-actions"><button class="btn btn-primary" type="submit"><?= $isEdit ? 'Simpan perubahan' : 'Tambah kelas'; ?></button><?php if ($isEdit): ?><a class="btn btn-light" href="<?= e(app_url('kelas')); ?>">Batal</a><?php endif; ?></div>
        </form>
    </section>
    <section class="panel table-panel">
        <div class="panel-heading"><div><h2>Daftar kelas</h2><p><?= count($items); ?> kelas terdaftar dalam sistem.</p></div><span class="count-badge"><?= count($items); ?> Kelas</span></div>
        <?php if (empty($items)): ?><div class="empty-state compact"><div class="empty-icon">▤</div><strong>Belum ada data kelas</strong><p>Tambahkan kelas melalui formulir di samping.</p></div>
        <?php else: ?>
        <div class="table-wrap"><table class="data-table"><thead><tr><th style="width:60px">No.</th><th>Nama Kelas</th><th>Jumlah Murid</th><th style="width:125px">Aksi</th></tr></thead><tbody>
        <?php foreach ($items as $index => $item): ?><tr><td class="row-number"><?= $index + 1; ?></td><td><div class="person-cell"><span class="person-avatar avatar-orange">▤</span><strong><?= e($item['nama_kelas']); ?></strong></div></td><td><span class="count-inline"><?= (int)$item['jumlah_murid']; ?> murid</span></td><td><div class="action-group"><a class="icon-action edit-action" href="<?= e(app_url('kelas', ['edit' => $item['id']])); ?>" title="Ubah">✎</a><form method="POST" action="<?= e(app_url('kelas', ['action' => 'delete'])); ?>" data-confirm="Hapus kelas ini? Kelas yang masih memiliki murid atau riwayat absensi tidak dapat dihapus."><?= csrf_field(); ?><input type="hidden" name="id" value="<?= (int)$item['id']; ?>"><button class="icon-action delete-action" type="submit" title="Hapus">×</button></form></div></td></tr><?php endforeach; ?>
        </tbody></table></div><?php endif; ?>
    </section>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
