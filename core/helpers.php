<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function e($value): string
{
    return htmlspecialchars((string)($value ?? ''), ENT_QUOTES, 'UTF-8');
}

function app_url(string $page = 'dashboard', array $params = []): string
{
    return 'index.php?' . http_build_query(array_merge(['page' => $page], $params));
}

function csrf_token(): string
{
    if (empty($_SESSION['_csrf_token'])) {
        $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['_csrf_token'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="_token" value="' . e(csrf_token()) . '">';
}

function csrf_is_valid(): bool
{
    $sent = $_POST['_token'] ?? '';
    return is_string($sent)
        && isset($_SESSION['_csrf_token'])
        && hash_equals($_SESSION['_csrf_token'], $sent);
}

function set_flash(string $type, string $message): void
{
    $_SESSION['_flash'] = ['type' => $type, 'message' => $message];
}

function get_flash(): ?array
{
    if (empty($_SESSION['_flash'])) {
        return null;
    }

    $flash = $_SESSION['_flash'];
    unset($_SESSION['_flash']);

    return $flash;
}

function is_logged_in(): bool
{
    return !empty($_SESSION['user_id']);
}

function is_valid_date(string $date): bool
{
    if (!preg_match('/^\\d{4}-\\d{2}-\\d{2}$/', $date)) {
        return false;
    }

    $parsed = DateTime::createFromFormat('!Y-m-d', $date);
    return $parsed !== false && $parsed->format('Y-m-d') === $date;
}

function tanggal_indonesia(string $date, bool $withDay = false): string
{
    $timestamp = strtotime($date);
    if ($timestamp === false) {
        return $date;
    }

    $days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
    $months = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    $result = date('d', $timestamp) . ' ' . $months[(int)date('n', $timestamp)] . ' ' . date('Y', $timestamp);
    return $withDay ? $days[(int)date('w', $timestamp)] . ', ' . $result : $result;
}
