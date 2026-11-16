<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Masuk · EduAttend</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body class="login-page">
    <div class="login-decoration decoration-one"></div>
    <div class="login-decoration decoration-two"></div>
    <main class="login-layout">
        <section class="login-intro">
            <a class="brand brand-light" href="index.php?page=login">
                <span class="brand-mark">
                    <svg viewBox="0 0 32 32" aria-hidden="true"><path d="M16 3 28 9.5v13L16 29 4 22.5v-13L16 3Z" fill="none" stroke="currentColor" stroke-width="2.2"/><path d="m10 16 4 4 8-9" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
                </span>
                <span><strong>EduAttend</strong><small>School Management</small></span>
            </a>
            <div class="intro-copy">
                <h1>Administrasi sekolah, lebih <em>mudah</em> dan teratur.</h1>
                <p>Kelola data guru, murid, kelas, mata pelajaran, dan kehadiran dalam satu tempat.</p>
            </div>
            <div class="login-illustration" aria-hidden="true">
                <div class="illustration-card card-back"></div>
                <div class="illustration-card card-front">
                    <div class="illustration-top"><span></span><span></span><span></span></div>
                    <div class="illustration-title">Ringkasan Kehadiran</div>
                    <div class="illustration-chart"><i style="height:48%"></i><i style="height:78%"></i><i style="height:60%"></i><i style="height:92%"></i><i style="height:68%"></i><i style="height:84%"></i></div>
                    <div class="illustration-line"></div>
                    <div class="illustration-line short"></div>
                </div>
                <div class="floating-check">✓</div>
            </div>
            <div class="login-footnote">Satu dashboard untuk kegiatan belajar yang lebih tertata.</div>
        </section>

        <section class="login-panel">
            <div class="login-form-wrap">
                <div class="mobile-brand">
                    <span class="brand-mark"><svg viewBox="0 0 32 32"><path d="M16 3 28 9.5v13L16 29 4 22.5v-13L16 3Z" fill="none" stroke="currentColor" stroke-width="2.2"/><path d="m10 16 4 4 8-9" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg></span>
                    <strong>EduAttend</strong>
                </div>
                <h2>Masuk ke akun</h2>
                <p class="login-subtitle">Masukkan detail akun administrator untuk melanjutkan.</p>

                <?php if (!empty($error)): ?>
                    <div class="alert alert-error login-alert"><span>!</span><?= e($error); ?></div>
                <?php endif; ?>

                <form method="POST" action="index.php?page=login" class="login-form">
                    <?= csrf_field(); ?>
                    <label for="username">Username</label>
                    <div class="input-with-icon">
                        <span>♙</span>
                        <input type="text" id="username" name="username" placeholder="Masukkan username" autocomplete="username" required autofocus>
                    </div>
                    <label for="password">Password</label>
                    <div class="input-with-icon">
                        <span>⌑</span>
                        <input type="password" id="password" name="password" placeholder="Masukkan password" autocomplete="current-password" required>
                        <button class="toggle-password" type="button" data-toggle-password="password">Lihat</button>
                    </div>
                    <button class="btn btn-primary btn-login" type="submit">Masuk ke Dashboard <span>→</span></button>
                </form>

                <div class="login-security"><span>▣</span> Akses terbatas untuk administrator yang terdaftar.</div>
                <div class="login-copyright">© <?= date('Y'); ?> EduAttend. All rights reserved.</div>
            </div>
        </section>
    </main>
</body>
</html>
