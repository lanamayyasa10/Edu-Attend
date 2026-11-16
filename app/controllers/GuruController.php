<?php

require_once __DIR__ . '/../../core/Controller.php';

class GuruController extends Controller
{
    private GuruModel $guruModel;
    private MapelModel $mapelModel;

    public function __construct()
    {
        $this->guruModel = $this->model('GuruModel');
        $this->mapelModel = $this->model('MapelModel');
    }

    public function index(): void
    {
        $this->requireAuth();

        $id = (int)($_GET['edit'] ?? 0);
        $this->view('master/guru', [
            'items' => $this->guruModel->getAll(),
            'mapelList' => $this->mapelModel->getAll(),
            'editData' => $id ? $this->guruModel->getById($id) : null,
        ]);
    }

    public function save(): void
    {
        $this->requireAuth();
        $this->requirePost();
        $this->verifyCsrfOrRedirect('guru');

        $id = (int)($_POST['id'] ?? 0);
        $nama = trim((string)($_POST['nama_guru'] ?? ''));
        $mapelId = (int)($_POST['mapel_id'] ?? 0);

        if ($nama === '') {
            set_flash('error', 'Nama guru wajib diisi.');
            $this->redirect('guru', $id ? ['edit' => $id] : []);
        }

        try {
            $this->guruModel->save($id ?: null, $nama, $mapelId ?: null);
            set_flash('success', $id ? 'Data guru berhasil diperbarui.' : 'Guru baru berhasil ditambahkan.');
        } catch (Throwable $error) {
            set_flash('error', 'Data guru gagal disimpan. Pastikan mata pelajaran yang dipilih tersedia.');
        }

        $this->redirect('guru');
    }

    public function delete(): void
    {
        $this->requireAuth();
        $this->requirePost();
        $this->verifyCsrfOrRedirect('guru');

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0 && $this->guruModel->delete($id)) {
            set_flash('success', 'Data guru berhasil dihapus.');
        } else {
            set_flash('error', 'Guru tidak dapat dihapus karena sudah digunakan pada riwayat absensi.');
        }

        $this->redirect('guru');
    }
}
