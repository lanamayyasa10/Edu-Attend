<<<<<<< HEAD
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
=======
<?php

class Controller
{
    /**
     * Load Model
     */
    public function model($model)
    {
        $file = __DIR__ . "/../app/models/" . $model . ".php";

        if (!file_exists($file)) {
            die("Model <b>$model</b> tidak ditemukan.");
        }

        require_once $file;

        return new $model();
    }

    /**
     * Load View
     */
    public function view($view, $data = [])
    {
        $file = __DIR__ . "/../app/views/" . $view . ".php";

        if (!file_exists($file)) {
            die("View <b>$view</b> tidak ditemukan.");
        }

        extract($data);

        require_once $file;
    }

    /**
     * Redirect
     */
    public function redirect($url)
    {
        header("Location: " . $url);
        exit;
    }

    /**
     * JSON Response (AJAX)
     */
    public function json($data = [])
    {
        header("Content-Type: application/json");
        echo json_encode($data);
        exit;
    }

    /**
     * Cek request POST
     */
    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Cek request GET
     */
    public function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }
}
>>>>>>> 44d0f02d7378dfe1ba506b3ef4eeb64afd0a9587
