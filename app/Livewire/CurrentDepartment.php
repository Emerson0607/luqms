<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Window;
use App\Models\WindowList;

class CurrentDepartment extends Component
{
    public $currentUserDepartment;
    public $currentDepartmentImage;
    public $allWindowQueue = [];

    public function mount()
    {
        $this->updateDepartment();
        $this->fetchAllWindows();
    }

    public function updateDepartment()
    {
        $this->currentUserDepartment = session('current_department_name');
        $this->currentDepartmentImage = $this->getDepartmentImage($this->currentUserDepartment);
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
        $currentDepartment = session('current_department_name');

        $this->allWindowQueue = Window::where('qms_window.department', $currentDepartment)
            ->join('qms_window_list', 'qms_window.w_id', '=', 'qms_window_list.w_id')
            ->select('qms_window.*', 'qms_window_list.name as window_name')
            ->get()
            ->toArray();
    }

    public function render()
    {
        return view('livewire.current-department');
    }
}
