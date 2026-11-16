<?php

require_once __DIR__ . '/../../core/Controller.php';

class AbsensiController extends Controller
{
    private GuruModel $guruModel;
    private KelasModel $kelasModel;
    private MuridModel $muridModel;
    private AbsensiModel $absensiModel;

    public function __construct()
    {
        $this->guruModel = $this->model('GuruModel');
        $this->kelasModel = $this->model('KelasModel');
        $this->muridModel = $this->model('MuridModel');
        $this->absensiModel = $this->model('AbsensiModel');
    }

    public function index(): void
    {
        $this->requireAuth();

        $guruList = $this->guruModel->getAll();
        $kelasList = $this->kelasModel->getAll();

        $guruId = (int)($_GET['guru'] ?? ($guruList[0]['id'] ?? 0));
        $kelasId = (int)($_GET['kelas'] ?? ($kelasList[0]['id'] ?? 0));
        $tanggal = (string)($_GET['tanggal'] ?? date('Y-m-d'));

        $validGuruIds = array_map('intval', array_column($guruList, 'id'));
        $validKelasIds = array_map('intval', array_column($kelasList, 'id'));
        if (!in_array($guruId, $validGuruIds, true)) {
            $guruId = (int)($guruList[0]['id'] ?? 0);
        }
        if (!in_array($kelasId, $validKelasIds, true)) {
            $kelasId = (int)($kelasList[0]['id'] ?? 0);
        }
        if (!is_valid_date($tanggal)) {
            $tanggal = date('Y-m-d');
        }

        $kelasNama = '';
        foreach ($kelasList as $kelasRow) {
            if ((int)$kelasRow['id'] === $kelasId) {
                $kelasNama = $kelasRow['nama_kelas'];
                break;
            }
        }

        $mapel = $guruId > 0 ? $this->guruModel->getMapel($guruId) : null;
        $headerId = 0;
        $murid = [];
        $summary = ['hadir' => 0, 'izin' => 0, 'sakit' => 0, 'alpha' => 0];

        if ($mapel && $kelasId > 0) {
            $headerId = $this->absensiModel->getHeaderId($kelasId, $guruId, (int)$mapel['id'], $tanggal);
            $murid = $this->muridModel->getByKelas($kelasId);
            $this->absensiModel->ensureDefaultStatuses($headerId, $kelasId);
            $statuses = $this->absensiModel->getStatusMap($headerId);

            foreach ($murid as &$student) {
                $student['status'] = $statuses[(int)$student['id']] ?? 'Hadir';
                $summary[strtolower($student['status'])]++;
            }
            unset($student);
        }

        $this->view('absensi/index', [
            'guruList' => $guruList,
            'kelasList' => $kelasList,
            'guruId' => $guruId,
            'kelasId' => $kelasId,
            'kelasNama' => $kelasNama,
            'tanggal' => $tanggal,
            'mapel' => $mapel,
            'headerId' => $headerId,
            'murid' => $murid,
            'summary' => $summary,
        ]);
    }

    public function saveStatus(): void
    {
        $this->requireAuth();
        $this->requirePost();

        if (!csrf_is_valid()) {
            $this->json(['success' => false, 'message' => 'Sesi tidak valid. Muat ulang halaman.'], 419);
        }

        $headerId = (int)($_POST['header_id'] ?? 0);
        $muridId = (int)($_POST['murid_id'] ?? 0);
        $status = (string)($_POST['status'] ?? '');

        if ($headerId < 1 || $muridId < 1 || !in_array($status, AbsensiModel::STATUSES, true)) {
            $this->json(['success' => false, 'message' => 'Data absensi tidak valid.'], 422);
        }

        try {
            $saved = $this->absensiModel->save($headerId, $muridId, $status);
            $this->json([
                'success' => $saved,
                'message' => $saved ? 'Perubahan tersimpan.' : 'Data murid tidak sesuai dengan kelas absensi.',
            ], $saved ? 200 : 422);
        } catch (Throwable $error) {
            $this->json(['success' => false, 'message' => 'Absensi gagal disimpan.'], 500);
        }
    }
}
