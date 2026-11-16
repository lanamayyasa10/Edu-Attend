<?php
$pageTitle = 'Data Guru';
$pageSubtitle = 'Kelola informasi guru dan mata pelajaran yang diampu.';
$activePage = 'guru';
require __DIR__ . '/../layouts/header.php';
$isEdit = !empty($editData);
?>
<div class="content-grid master-grid">
    <section class="panel form-panel">
        <div class="panel-heading">
            <div><h2><?= $isEdit ? 'Ubah data guru' : 'Tambah guru'; ?></h2><p><?= $isEdit ? 'Perbarui informasi guru berikut.' : 'Lengkapi formulir untuk menambahkan guru.'; ?></p></div>
            <span class="panel-step"><?= $isEdit ? 'EDIT' : 'FORM'; ?></span>
        </div>
        <form method="POST" action="<?= e(app_url('guru', ['action' => 'save'])); ?>" class="stack-form">
            <?= csrf_field(); ?>
            <input type="hidden" name="id" value="<?= e($editData['id'] ?? ''); ?>">
            <div class="form-group">
                <label for="nama_guru">Nama lengkap guru <span class="required">*</span></label>
                <input type="text" id="nama_guru" name="nama_guru" placeholder="Contoh: Siti Rahmawati, S.Pd." value="<?= e($editData['nama_guru'] ?? ''); ?>" maxlength="120" required>
            </div>
            <div class="form-group">
                <label for="mapel_id">Mata pelajaran yang diampu</label>
                <select id="mapel_id" name="mapel_id">
                    <option value="">Pilih mata pelajaran</option>
                    <?php foreach ($mapelList as $mapel): ?>
                        <option value="<?= (int)$mapel['id']; ?>" <?= (int)($editData['mapel_id'] ?? 0) === (int)$mapel['id'] ? 'selected' : ''; ?>><?= e($mapel['nama_mapel']); ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (empty($mapelList)): ?><small class="field-hint">Tambahkan mata pelajaran terlebih dahulu agar dapat ditautkan ke guru.</small><?php endif; ?>
            </div>
            <div class="form-actions">
                <button class="btn btn-primary" type="submit"><?= $isEdit ? 'Simpan perubahan' : 'Tambah guru'; ?></button>
                <?php if ($isEdit): ?><a class="btn btn-light" href="<?= e(app_url('guru')); ?>">Batal</a><?php endif; ?>
            </div>
        </form>
    </section>

    <section class="panel table-panel">
        <div class="panel-heading">
            <div><h2>Daftar guru</h2><p><?= count($items); ?> data guru terdaftar.</p></div>
            <span class="count-badge"><?= count($items); ?> Guru</span>
        </div>
        <?php if (empty($items)): ?>
            <div class="empty-state compact"><div class="empty-icon">♙</div><strong>Belum ada data guru</strong><p>Gunakan formulir untuk menambahkan data pertama.</p></div>
        <?php else: ?>
            <div class="table-wrap">
                <table class="data-table">
                    <thead><tr><th style="width:60px">No.</th><th>Nama Guru</th><th>Mata Pelajaran</th><th style="width:125px">Aksi</th></tr></thead>
                    <tbody>
                    <?php foreach ($items as $index => $item): ?>
                        <tr>
                            <td class="row-number"><?= $index + 1; ?></td>
                            <td><div class="person-cell"><span class="person-avatar avatar-blue"><?= e(strtoupper(substr($item['nama_guru'], 0, 1))); ?></span><strong><?= e($item['nama_guru']); ?></strong></div></td>
                            <td><?= $item['nama_mapel'] ? e($item['nama_mapel']) : '<span class="muted">Belum ditentukan</span>'; ?></td>
                            <td><div class="action-group">
                                <a class="icon-action edit-action" href="<?= e(app_url('guru', ['edit' => $item['id']])); ?>" title="Ubah">✎</a>
                                <form method="POST" action="<?= e(app_url('guru', ['action' => 'delete'])); ?>" data-confirm="Hapus data guru ini?">
                                    <?= csrf_field(); ?><input type="hidden" name="id" value="<?= (int)$item['id']; ?>">
                                    <button class="icon-action delete-action" type="submit" title="Hapus">×</button>
                                </form>
                            </div></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </section>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
