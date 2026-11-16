<<<<<<< HEAD
<?php

require_once __DIR__ . '/../core/helpers.php';
require_once __DIR__ . '/../core/Controller.php';

$controllerFiles = glob(__DIR__ . '/../app/controllers/*.php');
foreach ($controllerFiles as $controllerFile) {
    require_once $controllerFile;
}

$page = (string)($_GET['page'] ?? 'dashboard');
$action = (string)($_GET['action'] ?? 'index');

if ($page === 'login') {
    (new AuthController())->login();
    exit;
}

if ($page === 'logout') {
    (new AuthController())->logout();
    exit;
}

if (!is_logged_in()) {
    header('Location: index.php?page=login');
    exit;
}

$routes = [
    'dashboard' => DashboardController::class,
    'guru' => GuruController::class,
    'kelas' => KelasController::class,
    'mapel' => MapelController::class,
    'murid' => MuridController::class,
    'absensi' => AbsensiController::class,
    'laporan' => LaporanController::class,
    'akun' => AkunController::class,
];

if (!isset($routes[$page])) {
    http_response_code(404);
    $page = 'dashboard';
    $action = 'index';
}

$controllerClass = $routes[$page];
$controller = new $controllerClass();

$allowedActions = [
    'dashboard' => ['index'],
    'guru' => ['index', 'save', 'delete'],
    'kelas' => ['index', 'save', 'delete'],
    'mapel' => ['index', 'save', 'delete'],
    'murid' => ['index', 'save', 'delete'],
    'absensi' => ['index', 'saveStatus'],
    'laporan' => ['index', 'delete'],
    'akun' => ['index', 'save'],
];

if (!in_array($action, $allowedActions[$page], true)) {
    http_response_code(404);
    $action = 'index';
}

$controller->{$action}();
=======
<?php

/*
|--------------------------------------------------------------------------
| Core
|--------------------------------------------------------------------------
*/

require_once "../config/Database.php";
require_once "../core/Model.php";
require_once "../core/Controller.php";

/*
|--------------------------------------------------------------------------
| Controller
|--------------------------------------------------------------------------
*/

require_once "../app/controllers/AbsensiController.php";

$controller = new AbsensiController();

/*
|--------------------------------------------------------------------------
| Routing
|--------------------------------------------------------------------------
*/

$action = $_GET['action'] ?? '';

switch($action){

    case "simpan":

        $controller->simpan();

        break;

    default:

        $controller->index();

        break;

}
>>>>>>> 44d0f02d7378dfe1ba506b3ef4eeb64afd0a9587
