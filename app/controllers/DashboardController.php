<?php

require_once __DIR__ . '/../../core/Controller.php';

class DashboardController extends Controller
{
    public function index(): void
    {
        $this->requireAuth();

        $dashboard = $this->model('DashboardModel');
        $this->view('dashboard/index', [
            'counts' => $dashboard->getCounts(),
            'today' => $dashboard->getTodayAttendance(),
            'recent' => $dashboard->getRecentAttendance(),
        ]);
    }
}
