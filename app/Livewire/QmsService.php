<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\DmsService;
use App\Models\QmsWindow;
use App\Models\Personnel;
use App\Models\DmsUserDepts;
use App\Models\DmsUsers;


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

        $this->users = DmsUserDepts::where('dept_id', $this->currentUserDepartmentId)->get();
        // Step 2: Extract p_id values
        $pIds = $this->users->pluck('p_id');
        // Step 3: Fetch dms_users where p_id is in the extracted list and status = 1
        $this->personnels = DmsUsers::whereIn('p_id', $pIds)->where('status', 1)->get();

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
