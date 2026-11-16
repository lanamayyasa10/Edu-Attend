<?php

require_once __DIR__ . '/../../core/Controller.php';

class LaporanController extends Controller
{
    private AbsensiModel $absensiModel;
    private KelasModel $kelasModel;

    public function __construct()
    {
        $this->absensiModel = $this->model('AbsensiModel');
        $this->kelasModel = $this->model('KelasModel');
    }

    public function index(): void
    {
        $this->requireAuth();

        $from = (string)($_GET['from'] ?? date('Y-m-01'));
        $to = (string)($_GET['to'] ?? date('Y-m-d'));
        $kelasId = (int)($_GET['kelas'] ?? 0);

        if (!is_valid_date($from)) $from = date('Y-m-01');
        if (!is_valid_date($to)) $to = date('Y-m-d');
        if ($from > $to) [$from, $to] = [$to, $from];

        $this->view('laporan/index', [
            'items' => $this->absensiModel->getHistory($from, $to, $kelasId),
            'kelasList' => $this->kelasModel->getAll(),
            'from' => $from,
            'to' => $to,
            'kelasId' => $kelasId,
        ]);
    }

    public function delete(): void
    {
        $this->requireAuth();
        $this->requirePost();
        $this->verifyCsrfOrRedirect('laporan');

        $id = (int)($_POST['id'] ?? 0);
        if ($id > 0 && $this->absensiModel->deleteHeader($id)) {
            set_flash('success', 'Satu sesi absensi beserta detailnya berhasil dihapus.');
        } else {
            set_flash('error', 'Sesi absensi gagal dihapus.');
        }

        $this->redirect('laporan');
    }
}
