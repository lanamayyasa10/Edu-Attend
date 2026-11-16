# Aplikasi Daftar Hadir Murid

Aplikasi Daftar Hadir Murid merupakan aplikasi sederhana berbasis web yang dibuat menggunakan PHP dan MySQL. Aplikasi ini digunakan untuk membantu guru mencatat dan mengelola kehadiran murid berdasarkan tanggal dalam satu bulan.

Aplikasi ini menggunakan konsep **Model–View–Controller (MVC)** untuk memisahkan bagian pengolahan data, tampilan, dan pengaturan alur aplikasi agar kode lebih terstruktur dan mudah dikembangkan.

## 1. Fitur Aplikasi

Beberapa fitur yang tersedia dalam aplikasi ini antara lain:

- **Daftar Murid:** Menampilkan daftar nama murid yang tersimpan di database.
- **Input Informasi Absensi:** Menyediakan kolom mata pelajaran, kelas, nama guru, dan bulan.
- **Pencatatan Kehadiran:** Guru dapat mengisi status kehadiran murid berdasarkan tanggal.
- **Pilihan Status Kehadiran:** Tersedia empat pilihan status kehadiran:
  - H = Hadir
  - I = Izin
  - S = Sakit
  - A = Alpa
- **Simpan Absensi:** Menyimpan data kehadiran ke dalam database tanpa harus memuat ulang halaman.
- **Update Data Absensi:** Jika data absensi murid pada tanggal yang sama sudah tersedia, aplikasi akan memperbarui statusnya. Jika belum tersedia, aplikasi akan menambahkan data baru.

## 2. Teknologi yang Digunakan

Aplikasi ini dikembangkan menggunakan beberapa teknologi berikut:

| Teknologi | Kegunaan |
|---|---|
| PHP | Mengelola proses dan logika aplikasi |
| MySQL | Menyimpan data murid dan absensi |
| HTML | Membuat struktur halaman aplikasi |
| CSS | Mengatur tampilan aplikasi |
| JavaScript | Menangani proses penyimpanan data tanpa reload halaman |
| Fetch API | Mengirim data absensi ke server |
| MVC | Mengatur struktur dan pembagian kode aplikasi |

## 3. Struktur Folder

Berikut struktur folder yang digunakan dalam project:

```text
├── app/
│   ├── controllers/
│   │   └── AbsensiController.php
│   │
│   ├── models/
│   │   └── AbsensiModel.php
│   │
│   └── views/
│       └── absensi/
│           └── index.php
│
├── config/
│   └── database.php
│
├── core/
│   ├── Controller.php
│   ├── Database.php
│   └── Model.php
│
├── public/
│   ├── index.php
│   └── style.css
│
└── routes.php
```

### Penjelasan Struktur Folder

**1. Folder app**

Folder ini berisi bagian utama aplikasi yang terdiri dari:

- `controllers/`: Berisi AbsensiController.php yang mengatur proses permintaan pengguna, menampilkan halaman absensi, dan memproses penyimpanan data.
- `models/`: Berisi AbsensiModel.php yang bertugas mengambil data murid, memeriksa data absensi, serta menyimpan dan memperbarui data kehadiran.
- `views/`: Berisi halaman tampilan aplikasi, yaitu index.php yang menampilkan formulir dan tabel absensi.

**2. Folder config**

Berisi konfigurasi koneksi database melalui file `database.php`. File ini mengatur nama host, username, password, dan nama database yang digunakan aplikasi.

**3. Folder core**

Berisi file dasar untuk menjalankan konsep MVC:

- `Controller.php`: Membantu controller memanggil dan menampilkan halaman view.
- `Model.php`: Menyediakan koneksi database yang dapat digunakan oleh model.
- `Database.php`: File tersedia, tetapi saat ini belum digunakan dalam proses koneksi database.

**4. Folder public**

Folder yang menjadi bagian utama ketika aplikasi dijalankan.

- `index.php`: Menjadi pintu masuk aplikasi sekaligus mengatur pemanggilan method pada controller.
- `style.css`: Mengatur tampilan halaman, formulir, tabel, dan pilihan status absensi.

**5. File routes.php**

File ini disediakan untuk pengaturan routing tambahan, tetapi saat ini belum memiliki implementasi. Routing sederhana masih ditangani langsung oleh `public/index.php`.

## 4. Cara Kerja Aplikasi

Secara umum, aplikasi berjalan melalui beberapa tahapan berikut:

1. Pengguna membuka aplikasi melalui browser.
2. File `public/index.php` menerima permintaan dan memanggil AbsensiController.
3. Controller menjalankan method `index()` untuk mengambil data murid melalui AbsensiModel.
4. Model mengambil daftar murid dari database MySQL.
5. Data murid dikirim ke halaman view untuk ditampilkan dalam bentuk tabel absensi.
6. Guru mengisi informasi absensi dan memilih status kehadiran setiap murid berdasarkan tanggal.
7. Ketika tombol Simpan ditekan, JavaScript mengirimkan data formulir menggunakan Fetch API.
8. Controller menerima data tersebut dan meneruskannya ke model.
9. Model memeriksa apakah data absensi sudah tersedia. Jika sudah, data diperbarui. Jika belum, data baru disimpan ke database.
10. Setelah proses selesai, aplikasi menampilkan pesan bahwa absensi berhasil disimpan.

## 5. Persiapan Database

Sebelum menjalankan aplikasi, buat database MySQL dengan nama:

`absensi_mvc`

Berdasarkan query yang digunakan dalam kode aplikasi, database setidaknya membutuhkan dua tabel, yaitu `murid` dan `absensi`.

Contoh struktur tabel yang sesuai dengan kode:

**Tabel murid**

```sql
CREATE TABLE murid (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(100) NOT NULL
);
```

**Tabel absensi**

```sql
CREATE TABLE absensi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    murid_id INT NOT NULL,
    tanggal DATE NOT NULL,
    status VARCHAR(1) NOT NULL,
    UNIQUE KEY unik_absensi (murid_id, tanggal)
);
```

Contoh di atas merupakan struktur database yang dapat digunakan agar sesuai dengan kebutuhan query pada aplikasi.

## 6. Konfigurasi Koneksi Database

Buka file:

`config/database.php`

Pastikan konfigurasi database sesuai dengan pengaturan MySQL di komputer masing-masing.

```php
private $host = "localhost";
private $user = "root";
private $pass = "";
private $db = "absensi_mvc";
```

Jika menggunakan XAMPP dengan konfigurasi default, pengaturan tersebut umumnya sudah sesuai.

## 7. Cara Menjalankan Aplikasi

Berikut langkah-langkah untuk menjalankan aplikasi:

1. Pastikan XAMPP sudah terinstal di komputer.
2. Aktifkan Apache dan MySQL melalui XAMPP Control Panel.
3. Salin folder `bu_zaima` ke dalam folder `htdocs`.
4. Buka phpMyAdmin melalui browser.
5. Buat database dengan nama `absensi_mvc`.
6. Buat tabel `murid` dan `absensi` sesuai struktur database di atas.
7. Tambahkan beberapa data murid ke dalam tabel `murid`.
8. Buka browser dan akses alamat berikut:

```text
http://localhost/bu_zaima/public/
```

9. Setelah halaman terbuka, daftar murid akan ditampilkan dalam tabel absensi.
10. Isi informasi yang tersedia, pilih status kehadiran murid, kemudian tekan tombol **Simpan**.

## 8. Pembagian Tugas Berdasarkan MVC

Untuk memudahkan pemahaman, berikut pembagian tugas setiap bagian dalam aplikasi:

| Bagian | File | Tanggung Jawab |
|---|---|---|
| Model | AbsensiModel.php | Mengambil dan menyimpan data absensi |
| View | index.php | Menampilkan formulir dan tabel kehadiran |
| Controller | AbsensiController.php | Mengatur proses aplikasi |
| Core | Controller.php | Mengelola pemanggilan view |
| Core | Model.php | Menyediakan koneksi database |
| Config | database.php | Mengatur konfigurasi database |
| Public | index.php | Menjadi pintu masuk aplikasi |
| Public | style.css | Mengatur tampilan halaman |

## 9. Catatan Pengembangan

Aplikasi ini masih dalam tahap pengembangan sederhana. Beberapa hal yang dapat ditingkatkan pada pengembangan berikutnya adalah:

- Menyesuaikan jumlah tanggal secara otomatis berdasarkan bulan dan tahun yang dipilih.
- Menambahkan fitur melihat riwayat absensi.
- Menambahkan fitur edit dan hapus data absensi.
- Menambahkan validasi pada formulir.
- Menambahkan fitur login untuk membedakan hak akses guru dan administrator.
- Menyimpan informasi mata pelajaran, kelas, dan nama guru ke dalam database.
- Meningkatkan keamanan query database menggunakan prepared statement.
