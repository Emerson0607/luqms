<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;

use App\Models\{
    QmsWindow, WindowList, QmsClients, QmsSharedClient, QmsCharter
};

class CurrentDepartment extends Component
{
    public $currentUserDepartment;
    public $currentUserDepartmentId;
    public $currentDepartmentImage;
    public $allWindowQueue = [];
    public $allWaitingList = [];
    public $clients = [];
    public $charters;

    public function mount()
    {
        $this->updateDepartment();
        $this->fetchAllWindows();
        $this->fetchWaitingList();
    }

    public function updateDepartment()
    {
        $this->currentUserDepartmentId = session('current_department_id');
        $this->currentUserDepartment = session('current_department_name');
        $this->currentDepartmentImage = $this->getDepartmentImage($this->currentUserDepartment);
        $this->charters = QmsCharter::where('dept_id', $this->currentUserDepartmentId)->first();
        $this->fetchAllWindows();
        $this->fetchWaitingList();

    }

    private function getDepartmentImage($departmentName)
    {
        $images = [

            'Cashier\'s Office' => 'img/logo/lu.png',
            'College of Arts and Sciences' => 'img/logo/cas.png',
            'College of Business Administration & Accountancy' => 'img/logo/cbaa.png',
            'College of Computing Studies' => 'img/logo/ccs.png',
            'College of Education' => 'img/logo/coed.png',
            'College of Engineering' => 'img/logo/coeng.png',
            'College of Health Sciences' => 'img/logo/chs.png',
            'Human Resource Management Office' => 'img/logo/hrmo.png',
            'University Library' => 'img/logo/lu.png',
            'Management Information System' => 'img/logo/mis.png',
            'Medical & Dental Clinic' => 'img/logo/mdc.png',
            'Office of the Vice President for Academic Affairs' => 'img/logo/vpaa.png',
            'Quality Management Office' => 'img/logo/qmo.png',
            'Registrar\'s Office' => 'img/logo/reg.png',
            'Research & Development Center' => 'img/logo/rdc.png',
            'Student Affairs & Services Office' => 'img/logo/lu.png',
            'Supply Office' => 'img/logo/lu.png',
            'Office of the President' => 'img/logo/lu.png',
            'Corporate Communications Center' => 'img/logo/lu.png',
            'Office of the Vice President for Administration' => 'img/logo/lu.png',
            'Office of the Vice President for Planning & Finance' => 'img/logo/vppf.png',
            'Community Extension Services Unit' => 'img/logo/cesu.png',
            'Building & Maintenance Office' => 'img/logo/bmo.png',
            'Security Services Office' => 'img/logo/sso.png',
        ];

        return $images[$departmentName] ?? 'img/logo/lu.png';
    }

    public function fetchAllWindows()
    {
        $currentDepartmentId = session('current_department_id');
        $currentDepartment = session('current_department_name');

        // Retrieve all windows for the department
        $this->allWindowQueue = QmsWindow::where('dept_id', $currentDepartmentId)
            ->where('w_status', 1)
            ->orderBy('w_name')
            ->get();

        // For each window, fetch clients associated with that window
        foreach ($this->allWindowQueue as $window) {

            if ($window->shared_name === 'None') {
                $window->clients = QmsClients::where('dept_id', $currentDepartmentId)
                ->where('w_name', $window->w_name)
                ->take(3)
                ->get();
            } else {
                $window->clients = QmsSharedClient::where('dept_id', $currentDepartmentId)
                ->where('w_name', $window->shared_name)
                ->take(3)
                ->get();
            }
        }
    }

    public function fetchWaitingList()
    {
        $currentDepartmentId = session('current_department_id');
        $currentDepartment = session('current_department_name');

        // Retrieve all windows for the department
        $this->allWaitingList = QmsWindow::where('dept_id', $currentDepartmentId)
            ->where('w_status', 1)
            ->orderBy('w_name')
            ->get();

        // For each window, fetch clients associated with that window
        foreach ($this->allWaitingList as $window) {

            if ($window->shared_name === 'None') {
                $window->clients = QmsClients::where('dept_id', $currentDepartmentId)
                    ->where('w_name', $window->w_name)
                    ->skip(3)
                    ->take(4)
                    ->get();
            } else {
                $window->clients = QmsSharedClient::where('dept_id', $currentDepartmentId)
                    ->where('w_name', $window->shared_name)
                    ->skip(3)
                    ->take(4)
                    ->get();
            }
        }
    }

    public function render()
    {
        return view('livewire.current-department');
    }
}
