<?php
// Endpoint AJAX lama dipertahankan untuk kompatibilitas.
// Penyimpanan absensi sekarang ditangani melalui index.php?page=absensi&action=saveStatus.
header('Location: index.php?page=absensi');
exit;
