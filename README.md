# EduAttend — Sistem Manajemen Absensi Sekolah

EduAttend adalah aplikasi web berbasis PHP dan MySQL untuk membantu administrasi sekolah mengelola data guru, kelas, mata pelajaran, murid, serta pencatatan dan laporan kehadiran.

## Fitur

- Login administrator dengan password yang di-hash.
- Dashboard ringkasan data guru, murid, kelas, mata pelajaran, dan kehadiran hari ini.
- CRUD data guru beserta mata pelajaran yang diampu.
- CRUD data kelas.
- CRUD mata pelajaran.
- CRUD data murid dan penempatan kelas.
- Pencatatan absensi berdasarkan guru, mata pelajaran, kelas, dan tanggal.
- Status kehadiran: Hadir, Izin, Sakit, dan Alpha.
- Penyimpanan perubahan status secara otomatis.
- Laporan riwayat absensi dengan filter tanggal dan kelas.
- Pengaturan akun untuk mengganti password.
- Tampilan responsif untuk desktop, tablet, dan ponsel.

## Teknologi

- PHP 8.0 atau lebih baru
- MySQL 5.7+ atau MariaDB 10.4+
- MySQLi
- HTML, CSS, dan JavaScript

## Cara instalasi menggunakan XAMPP

1. Ekstrak folder `bu_zaima` ke dalam folder `htdocs`.
2. Jalankan Apache dan MySQL dari XAMPP Control Panel.
3. Buka `http://localhost/phpmyadmin`.
4. Pilih menu **Import**, lalu impor file `database.sql` yang ada di folder `bu_zaima`.
5. Jika username, password, atau nama database MySQL berbeda, sesuaikan pengaturan pada `config/Database.php`.
6. Buka aplikasi melalui `http://localhost/bu_zaima/` atau `http://localhost/bu_zaima/public/`.

## Akun login awal

| Kolom | Isi |
|---|---|
| Username | `admin` |
| Password | `admin123` |

**Penting:** Setelah berhasil login, buka menu akun di kanan atas dan segera ganti password awal.

## Urutan penggunaan

1. Masuk menggunakan akun administrator.
2. Tambahkan data mata pelajaran.
3. Tambahkan data guru dan tentukan mata pelajaran yang diampu.
4. Tambahkan data kelas.
5. Tambahkan data murid dan tempatkan ke kelas yang sesuai.
6. Buka menu **Absensi**, pilih guru, kelas, dan tanggal.
7. Ubah status kehadiran setiap murid. Perubahan disimpan otomatis.
8. Buka **Laporan Absensi** untuk melihat riwayat pencatatan.

## Struktur database

Aplikasi menggunakan tabel berikut:

| Tabel | Fungsi |
|---|---|
| `users` | Akun administrator |
| `guru` | Data guru |
| `kelas` | Data kelas |
| `mapel` | Data mata pelajaran |
| `murid` | Data murid dan relasi kelas |
| `guru_mapel` | Relasi guru dengan mata pelajaran |
| `absensi_header` | Informasi sesi absensi |
| `absensi_detail` | Status kehadiran setiap murid |

Relasi utama:
- Satu kelas dapat memiliki banyak murid.
- Guru terhubung dengan mata pelajaran melalui `guru_mapel`. Formulir saat ini menetapkan satu mata pelajaran utama per guru, mengikuti alur aplikasi awal.
- Satu sesi absensi menyimpan kelas, guru, mata pelajaran, dan tanggal.
- Detail absensi menyimpan status setiap murid pada sesi tersebut.

## Catatan untuk database lama

Jika sebelumnya sudah memiliki database `db_absensi`, **buat backup terlebih dahulu** sebelum melakukan perubahan. File `database.sql` menggunakan `CREATE TABLE IF NOT EXISTS`, sehingga tabel yang sudah ada tidak akan ditimpa. Namun, file tersebut tidak otomatis menambahkan kolom atau indeks yang mungkin belum ada pada tabel lama.

Pastikan struktur tabel lama memiliki kolom yang digunakan aplikasi:
- `guru`: `id`, `nama_guru`
- `kelas`: `id`, `nama_kelas`
- `mapel`: `id`, `nama_mapel`
- `murid`: `id`, `nama_murid`, `kelas_id`
- `guru_mapel`: `guru_id`, `mapel_id`
- `absensi_header`: `id`, `kelas_id`, `guru_id`, `mapel_id`, `tanggal`
- `absensi_detail`: `id`, `header_id`, `murid_id`, `status`

Jika struktur database lama berbeda, sesuaikan skema terlebih dahulu agar data lama tetap aman.

## Catatan keamanan

- Password disimpan menggunakan `password_hash()` dan diverifikasi dengan `password_verify()`.
- Formulir perubahan data menggunakan token CSRF.
- Query data menggunakan prepared statement.
- Penghapusan data master ditolak apabila data tersebut masih dipakai oleh catatan lain.
- Untuk penggunaan di server publik, aktifkan HTTPS, ubah password awal, batasi akses database, dan simpan file konfigurasi di luar document root jika memungkinkan.
