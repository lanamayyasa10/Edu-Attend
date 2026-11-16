<?php

require_once __DIR__ . '/../../core/Controller.php';

class MuridController extends Controller
{
    private MuridModel $muridModel;
    private KelasModel $kelasModel;

    public function __construct()
    {
        $this->muridModel = $this->model('MuridModel');
        $this->kelasModel = $this->model('KelasModel');
    }

    public function index(): void
    {
        $this->requireAuth();
        $id = (int)($_GET['edit'] ?? 0);

        $this->view('master/murid', [
            'items' => $this->muridModel->getAll(),
            'kelasList' => $this->kelasModel->getAll(),
            'editData' => $id ? $this->muridModel->getById($id) : null,
        ]);
    }

    public function save(): void
    {
        $this->requireAuth();
        $this->requirePost();
        $this->verifyCsrfOrRedirect('murid');

        $id = (int)($_POST['id'] ?? 0);
        $nama = trim((string)($_POST['nama_murid'] ?? ''));
        $kelasId = (int)($_POST['kelas_id'] ?? 0);

        if ($nama === '' || $kelasId < 1) {
            set_flash('error', 'Nama murid dan kelas wajib diisi.');
            $this->redirect('murid', $id ? ['edit' => $id] : []);
        }

        try {
            $saved = $this->muridModel->save($id ?: null, $nama, $kelasId);
            if ($saved) {
                set_flash('success', $id ? 'Data murid berhasil diperbarui.' : 'Murid baru berhasil ditambahkan.');
            } else {
                set_flash('error', 'Kelas murid tidak dapat dipindahkan karena sudah memiliki riwayat absensi.');
            }
        } catch (Throwable $error) {
            set_flash('error', 'Data murid gagal disimpan. Pastikan kelas yang dipilih tersedia.');
        }

        $this->redirect('murid');
    }

    public function delete(): void
    {
        $this->requireAuth();
        $this->requirePost();
        $this->verifyCsrfOrRedirect('murid');

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0 && $this->muridModel->delete($id)) {
            set_flash('success', 'Data murid berhasil dihapus.');
        } else {
            set_flash('error', 'Murid tidak dapat dihapus karena sudah memiliki catatan absensi.');
        }

        $this->redirect('murid');
    }
}
