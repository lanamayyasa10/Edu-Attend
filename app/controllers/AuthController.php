<?php

require_once __DIR__ . '/../../core/Controller.php';

class AuthController extends Controller
{
    private AuthModel $authModel;

    public function __construct()
    {
        $this->authModel = $this->model('AuthModel');
    }

    public function login(): void
    {
        if (is_logged_in()) {
            $this->redirect('dashboard');
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_is_valid()) {
                $error = 'Sesi login tidak valid. Muat ulang halaman dan coba lagi.';
            } else {
                $username = trim((string)($_POST['username'] ?? ''));
                $password = (string)($_POST['password'] ?? '');
                $user = $this->authModel->findByUsername($username);

                if ($user && password_verify($password, $user['password'])) {
                    session_regenerate_id(true);
                    $_SESSION['user_id'] = (int)$user['id'];
                    $_SESSION['user_name'] = $user['nama'];
                    $_SESSION['user_username'] = $user['username'];
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['_csrf_token'] = bin2hex(random_bytes(32));
                    $this->redirect('dashboard');
                }

                $error = 'Username atau password yang kamu masukkan tidak sesuai.';
            }
        }

        $this->view('auth/login', ['error' => $error]);
    }

    public function logout(): void
    {
        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        }

        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}
