<?php
$pageTitle = 'Pengaturan Akun';
$pageSubtitle = 'Perbarui keamanan akun administrator yang digunakan untuk masuk.';
$activePage = 'akun';
require __DIR__ . '/../layouts/header.php';
?>
<div class="account-layout">
    <section class="panel account-card">
        <div class="account-profile">
            <span class="account-avatar"><?= e(strtoupper(substr($user['nama'] ?? 'A', 0, 1))); ?></span>
            <div><h2><?= e($user['nama'] ?? 'Administrator'); ?></h2><p><?= e($user['username'] ?? ''); ?> · <?= e(ucfirst($user['role'] ?? 'admin')); ?></p></div>
        </div>
        <div class="account-detail"><span>Nama akun</span><strong><?= e($user['nama'] ?? 'Administrator'); ?></strong></div>
        <div class="account-detail"><span>Username</span><strong><?= e($user['username'] ?? ''); ?></strong></div>
        <div class="account-detail"><span>Hak akses</span><strong>Administrator</strong></div>
        <div class="account-tip"><strong>Tips keamanan</strong><p>Gunakan password yang kuat dan jangan membagikan akses akun kepada orang lain.</p></div>
    </section>
    <section class="panel form-panel password-panel">
        <div class="panel-heading"><div><h2>Ubah password</h2><p>Gunakan minimal 8 karakter untuk password baru.</p></div><span class="panel-step">AMAN</span></div>
        <form method="POST" action="<?= e(app_url('akun', ['action' => 'save'])); ?>" class="stack-form">
            <?= csrf_field(); ?>
            <div class="form-group"><label for="password_lama">Password saat ini <span class="required">*</span></label><input type="password" id="password_lama" name="password_lama" autocomplete="current-password" required></div>
            <div class="form-group"><label for="password_baru">Password baru <span class="required">*</span></label><input type="password" id="password_baru" name="password_baru" minlength="8" autocomplete="new-password" required></div>
            <div class="form-group"><label for="konfirmasi_password">Konfirmasi password baru <span class="required">*</span></label><input type="password" id="konfirmasi_password" name="konfirmasi_password" minlength="8" autocomplete="new-password" required></div>
            <div class="form-actions"><button class="btn btn-primary" type="submit">Perbarui password</button></div>
        </form>
    </section>
</div>
<?php require __DIR__ . '/../layouts/footer.php'; ?>
