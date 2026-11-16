<?php

require_once __DIR__ . '/helpers.php';

class Controller
{
    public function model(string $model)
    {
        $file = __DIR__ . '/../app/models/' . $model . '.php';

        if (!file_exists($file)) {
            throw new RuntimeException("Model {$model} tidak ditemukan.");
        }

        require_once $file;
        return new $model();
    }

    public function view(string $view, array $data = []): void
    {
        $file = __DIR__ . '/../app/views/' . $view . '.php';

        if (!file_exists($file)) {
            throw new RuntimeException("View {$view} tidak ditemukan.");
        }

        extract($data, EXTR_SKIP);
        require $file;
    }

    protected function requireAuth(): void
    {
        if (!is_logged_in()) {
            header('Location: ' . app_url('login'));
            exit;
        }
    }

    protected function requirePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Metode tidak diizinkan.');
        }
    }

    protected function verifyCsrfOrRedirect(string $page): void
    {
        if (!csrf_is_valid()) {
            set_flash('error', 'Sesi formulir tidak valid. Silakan coba kembali.');
            header('Location: ' . app_url($page));
            exit;
        }
    }

    protected function json(array $data, int $status = 200): void
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data);
        exit;
    }

    protected function redirect(string $page, array $params = []): void
    {
        header('Location: ' . app_url($page, $params));
        exit;
    }
}
