<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DmsService;
use App\Models\QmsWindow;
use App\Models\Personnel;
use App\Models\DmsUserDepts;


class QmsService extends Component
{

    public $currentUserDepartmentId;
    public $services;
    public $windowLists;
    public $personnels;
    public $inactiveWindows;


    public function mount()
    {
        $this->renderQmsWindow();
        
    }

    public function renderQmsWindow()
    {   
        $this->currentUserDepartmentId = session('current_department_id');
        $this->personnels = DmsUserDepts::where('dept_id', $this->currentUserDepartmentId)->get();
        $this->services = DmsService::where('dept_id', $this->currentUserDepartmentId)->get();
        $this->windowLists = QmsWindow::where('dept_id', $this->currentUserDepartmentId)->get();
        $this->inactiveWindows = QmsWindow::where('dept_id', $this->currentUserDepartmentId)
            ->where('w_status', 0)
            ->get(); 
    }


    public function render()
    {
        return view('livewire.qms-service');
    }
}
