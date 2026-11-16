-- ==========================================================
-- EDUATTEND - DATABASE SETUP
-- Import file ini melalui phpMyAdmin atau MySQL CLI.
-- Database default: db_absensi
-- ==========================================================

CREATE DATABASE IF NOT EXISTS db_absensi
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE db_absensi;

CREATE TABLE IF NOT EXISTS guru (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_guru VARCHAR(120) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS kelas (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_kelas VARCHAR(80) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS mapel (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_mapel VARCHAR(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS murid (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama_murid VARCHAR(120) NOT NULL,
    kelas_id INT UNSIGNED NOT NULL,
    INDEX idx_murid_kelas (kelas_id),
    CONSTRAINT fk_murid_kelas FOREIGN KEY (kelas_id) REFERENCES kelas(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS guru_mapel (
    guru_id INT UNSIGNED NOT NULL,
    mapel_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (guru_id, mapel_id),
    INDEX idx_guru_mapel_mapel (mapel_id),
    CONSTRAINT fk_guru_mapel_guru FOREIGN KEY (guru_id) REFERENCES guru(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_guru_mapel_mapel FOREIGN KEY (mapel_id) REFERENCES mapel(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS absensi_header (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    kelas_id INT UNSIGNED NOT NULL,
    guru_id INT UNSIGNED NOT NULL,
    mapel_id INT UNSIGNED NOT NULL,
    tanggal DATE NOT NULL,
    UNIQUE KEY uq_absensi_sesi (kelas_id, guru_id, mapel_id, tanggal),
    INDEX idx_absensi_tanggal (tanggal),
    CONSTRAINT fk_absensi_kelas FOREIGN KEY (kelas_id) REFERENCES kelas(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_absensi_guru FOREIGN KEY (guru_id) REFERENCES guru(id)
        ON UPDATE CASCADE ON DELETE RESTRICT,
    CONSTRAINT fk_absensi_mapel FOREIGN KEY (mapel_id) REFERENCES mapel(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS absensi_detail (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    header_id INT UNSIGNED NOT NULL,
    murid_id INT UNSIGNED NOT NULL,
    status ENUM('Hadir', 'Izin', 'Sakit', 'Alpha') NOT NULL DEFAULT 'Hadir',
    UNIQUE KEY uq_absensi_murid (header_id, murid_id),
    INDEX idx_absensi_detail_murid (murid_id),
    CONSTRAINT fk_detail_header FOREIGN KEY (header_id) REFERENCES absensi_header(id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_detail_murid FOREIGN KEY (murid_id) REFERENCES murid(id)
        ON UPDATE CASCADE ON DELETE RESTRICT
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS users (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nama VARCHAR(120) NOT NULL,
    username VARCHAR(60) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    role VARCHAR(30) NOT NULL DEFAULT 'admin',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Akun awal:
-- username: admin
-- password: admin123
-- Segera ganti password setelah login pertama.
INSERT INTO users (nama, username, password, role)
VALUES (
    'Administrator',
    'admin',
    '$2y$12$cMnMXw0aakn1GYaEBtKK/OYo5yOpv.a7YDEyNUegUUlEzYSlhRXlm',
    'admin'
)
ON DUPLICATE KEY UPDATE username = VALUES(username);
