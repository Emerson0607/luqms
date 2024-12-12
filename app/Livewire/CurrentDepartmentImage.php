<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\{
    QmsWindow, WindowList
};


class CurrentDepartmentImage extends Component
{
    public $currentUserDepartment;
    public $currentUserDepartmentId;
    public $currentDepartmentImage;
   

    public function mount()
    {
        $this->updateDepartment();
   
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
            'Registrar\'s Office' => 'img/logo/lu.png',
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

    public function render()
    {
        return view('livewire.current-department-image');
    }
}
