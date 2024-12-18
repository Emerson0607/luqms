<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\{
    DmsService, QmsWindow, Personnel, DmsUserDepts, DmsUsers, QmsSharedWindow
};

class QmsService extends Component
{
    public $currentUserDepartmentId, $services, $windowLists, $personnels, $inactiveWindows, $sharedWindows;
    
    public function mount()
    {
        $this->renderQmsWindow();
    }

    public function renderQmsWindow()
    {   
        $this->currentUserDepartmentId = session('current_department_id');
        $this->users = DmsUserDepts::where('dept_id', $this->currentUserDepartmentId)->get();
        $pIds = $this->users->pluck('p_id');
        $this->personnels = DmsUsers::whereIn('p_id', $pIds)->where('status', 1)->get();
        $this->services = DmsService::where('dept_id', $this->currentUserDepartmentId)->get();
        $this->windowLists = QmsWindow::where('dept_id', $this->currentUserDepartmentId)->get();
        $this->inactiveWindows = QmsWindow::where('dept_id', $this->currentUserDepartmentId)
            ->where('w_status', 0)
            ->get(); 
        $this->sharedWindows = QmsSharedWindow::where('dept_id', $this->currentUserDepartmentId)->get();
    }

    public function render()
    {
        return view('livewire.qms-service');
    }
}