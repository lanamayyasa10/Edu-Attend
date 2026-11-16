<?php
$pageTitle = $pageTitle ?? 'Dashboard';
$pageSubtitle = $pageSubtitle ?? 'Ringkasan aktivitas sekolah';
$activePage = $activePage ?? 'dashboard';
$flash = get_flash();
$userName = $_SESSION['user_name'] ?? 'Administrator';
$initial = strtoupper(substr((string)$userName, 0, 1));
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle); ?> · EduAttend</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<div class="app-shell">
    <aside class="sidebar" id="sidebar">
        <a class="brand" href="<?= e(app_url('dashboard')); ?>">
            <span class="brand-mark">
                <svg viewBox="0 0 32 32" aria-hidden="true"><path d="M16 3 28 9.5v13L16 29 4 22.5v-13L16 3Z" fill="none" stroke="currentColor" stroke-width="2.2"/><path d="m10 16 4 4 8-9" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </span>
            <span><strong>EduAttend</strong><small>School Management</small></span>
        </a>

        <div class="nav-label">MENU UTAMA</div>
        <nav class="side-nav">
            <a class="nav-link <?= $activePage === 'dashboard' ? 'active' : ''; ?>" href="<?= e(app_url('dashboard')); ?>">
                <span class="nav-icon">▦</span><span>Dashboard</span>
            </a>
            <div class="nav-label nav-label-spaced">DATA MASTER</div>
            <a class="nav-link <?= $activePage === 'guru' ? 'active' : ''; ?>" href="<?= e(app_url('guru')); ?>">
                <span class="nav-icon">♙</span><span>Data Guru</span>
            </a>
            <a class="nav-link <?= $activePage === 'kelas' ? 'active' : ''; ?>" href="<?= e(app_url('kelas')); ?>">
                <span class="nav-icon">▤</span><span>Data Kelas</span>
            </a>
            <a class="nav-link <?= $activePage === 'mapel' ? 'active' : ''; ?>" href="<?= e(app_url('mapel')); ?>">
                <span class="nav-icon">▧</span><span>Mata Pelajaran</span>
            </a>
            <a class="nav-link <?= $activePage === 'murid' ? 'active' : ''; ?>" href="<?= e(app_url('murid')); ?>">
                <span class="nav-icon">♧</span><span>Data Murid</span>
            </a>
            <div class="nav-label nav-label-spaced">AKADEMIK</div>
            <a class="nav-link <?= $activePage === 'absensi' ? 'active' : ''; ?>" href="<?= e(app_url('absensi')); ?>">
                <span class="nav-icon">✓</span><span>Absensi</span>
            </a>
            <a class="nav-link <?= $activePage === 'laporan' ? 'active' : ''; ?>" href="<?= e(app_url('laporan')); ?>">
                <span class="nav-icon">▥</span><span>Laporan Absensi</span>
            </a>
        </nav>

        <div class="sidebar-bottom">
            <div class="sidebar-help">
                <span class="help-symbol">✦</span>
                <strong>Butuh bantuan?</strong>
                <p>Kelola data sekolah dengan lebih mudah.</p>
            </div>
            <div class="sidebar-version">EDUATTEND <span>•</span> VERSI 1.0</div>
        </div>
    </aside>

    <div class="main-wrap">
        <header class="topbar">
            <button class="mobile-menu" id="mobileMenu" type="button" aria-label="Buka menu">☰</button>
            <div class="breadcrumb">Halaman <span>/</span> <strong><?= e($pageTitle); ?></strong></div>
            <div class="topbar-actions">
                <span class="today-label"><?= e(tanggal_indonesia(date('Y-m-d'))); ?></span>
                <a class="user-chip" href="<?= e(app_url('akun')); ?>">
                    <span class="avatar"><?= e($initial); ?></span>
                    <span class="user-meta"><strong><?= e($userName); ?></strong><small>Administrator</small></span>
                    <span class="chevron">⌄</span>
                </a>
                <a class="logout-link" href="<?= e(app_url('logout')); ?>" title="Keluar">↗</a>
            </div>
        </header>

        <main class="main-content">
            <div class="page-heading">
                <div>
                    <h1><?= e($pageTitle); ?></h1>
                    <p><?= e($pageSubtitle); ?></p>
                </div>
                <?php if ($activePage === 'absensi'): ?>
                    <button class="btn btn-light" type="button" onclick="window.print()">Cetak halaman</button>
                <?php endif; ?>
            </div>

            <?php if ($flash): ?>
                <div class="alert alert-<?= e($flash['type']); ?>" role="status">
                    <span><?= $flash['type'] === 'success' ? '✓' : '!'; ?></span>
                    <?= e($flash['message']); ?>
                    <button type="button" class="alert-close" onclick="this.parentElement.remove()" aria-label="Tutup">×</button>
                </div>
            <?php endif; ?>
