<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DmsUserDepts;

class DepartmentPersonnel extends Component
{
    public $dept_id;
    public $userDepartments = [];
    public $personnels = [];

    public function mount()
    {
        // Load distinct departments when the component mounts
        $this->userDepartments = DmsUserDepts::select('dept_id')->distinct()->get();
    }

    public function fetchPersonnels()
    {
        $this->personnels = DmsUserDepts::where('dept_id', $this->dept_id)->get();
          // Load distinct departments when the component mounts
        $this->userDepartments = DmsUserDepts::select('dept_id')->distinct()->get();
    }

    public function render()
    {
        return view('livewire.department-personnel');
    }
}
