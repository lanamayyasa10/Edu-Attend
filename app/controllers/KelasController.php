<?php

require_once __DIR__ . '/../../core/Controller.php';

class KelasController extends Controller
{
    private KelasModel $kelasModel;

    public function __construct()
    {
        $this->kelasModel = $this->model('KelasModel');
    }

    public function index(): void
    {
        $this->requireAuth();
        $id = (int)($_GET['edit'] ?? 0);

        $this->view('master/kelas', [
            'items' => $this->kelasModel->getAll(),
            'editData' => $id ? $this->kelasModel->getById($id) : null,
        ]);
    }

    public function save(): void
    {
        $this->requireAuth();
        $this->requirePost();
        $this->verifyCsrfOrRedirect('kelas');

        $id = (int)($_POST['id'] ?? 0);
        $nama = trim((string)($_POST['nama_kelas'] ?? ''));

        if ($nama === '') {
            set_flash('error', 'Nama kelas wajib diisi.');
            $this->redirect('kelas', $id ? ['edit' => $id] : []);
        }

        try {
            $this->kelasModel->save($id ?: null, $nama);
            set_flash('success', $id ? 'Data kelas berhasil diperbarui.' : 'Kelas baru berhasil ditambahkan.');
        } catch (Throwable $error) {
            set_flash('error', 'Data kelas gagal disimpan. Periksa kembali nama kelas.');
        }

        $this->redirect('kelas');
    }

    public function delete(): void
    {
        $this->requireAuth();
        $this->requirePost();
        $this->verifyCsrfOrRedirect('kelas');

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0 && $this->kelasModel->delete($id)) {
            set_flash('success', 'Data kelas berhasil dihapus.');
        } else {
            set_flash('error', 'Kelas tidak dapat dihapus karena masih memiliki murid atau riwayat absensi.');
        }

        $this->redirect('kelas');
    }
}
