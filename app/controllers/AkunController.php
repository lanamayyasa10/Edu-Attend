<?php

require_once __DIR__ . '/../../core/Controller.php';

class AkunController extends Controller
{
    private AuthModel $authModel;

    public function __construct()
    {
        $this->authModel = $this->model('AuthModel');
    }

    public function index(): void
    {
        $this->requireAuth();
        $this->view('akun/index', [
            'user' => $this->authModel->findById((int)$_SESSION['user_id']),
        ]);
    }

    public function save(): void
    {
        $this->requireAuth();
        $this->requirePost();
        $this->verifyCsrfOrRedirect('akun');

        $current = (string)($_POST['password_lama'] ?? '');
        $new = (string)($_POST['password_baru'] ?? '');
        $confirm = (string)($_POST['konfirmasi_password'] ?? '');

        $user = $this->authModel->findByUsername((string)($_SESSION['user_username'] ?? ''));

        if (!$user || !password_verify($current, $user['password'])) {
            set_flash('error', 'Password saat ini tidak sesuai.');
        } elseif (strlen($new) < 8) {
            set_flash('error', 'Password baru minimal 8 karakter.');
        } elseif ($new !== $confirm) {
            set_flash('error', 'Konfirmasi password baru belum cocok.');
        } else {
            $this->authModel->updatePassword((int)$user['id'], password_hash($new, PASSWORD_DEFAULT));
            set_flash('success', 'Password berhasil diperbarui.');
        }

        $this->redirect('akun');
    }
}
