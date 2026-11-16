<?php

require_once __DIR__ . '/../../core/Controller.php';

class MapelController extends Controller
{
    private MapelModel $mapelModel;

    public function __construct()
    {
        $this->mapelModel = $this->model('MapelModel');
    }

    public function index(): void
    {
        $this->requireAuth();
        $id = (int)($_GET['edit'] ?? 0);

        $this->view('master/mapel', [
            'items' => $this->mapelModel->getAll(),
            'editData' => $id ? $this->mapelModel->getById($id) : null,
        ]);
    }

    public function save(): void
    {
        $this->requireAuth();
        $this->requirePost();
        $this->verifyCsrfOrRedirect('mapel');

        $id = (int)($_POST['id'] ?? 0);
        $nama = trim((string)($_POST['nama_mapel'] ?? ''));

        if ($nama === '') {
            set_flash('error', 'Nama mata pelajaran wajib diisi.');
            $this->redirect('mapel', $id ? ['edit' => $id] : []);
        }

        try {
            $this->mapelModel->save($id ?: null, $nama);
            set_flash('success', $id ? 'Mata pelajaran berhasil diperbarui.' : 'Mata pelajaran berhasil ditambahkan.');
        } catch (Throwable $error) {
            set_flash('error', 'Mata pelajaran gagal disimpan. Periksa kembali data yang dimasukkan.');
        }

        $this->redirect('mapel');
    }

    public function delete(): void
    {
        $this->requireAuth();
        $this->requirePost();
        $this->verifyCsrfOrRedirect('mapel');

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0 && $this->mapelModel->delete($id)) {
            set_flash('success', 'Mata pelajaran berhasil dihapus.');
        } else {
            set_flash('error', 'Mata pelajaran tidak dapat dihapus karena masih digunakan oleh guru atau riwayat absensi.');
        }

        $this->redirect('mapel');
    }
}
