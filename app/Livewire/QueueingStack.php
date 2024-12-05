<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\QmsClients;
use App\Models\QmsWindow;
use Illuminate\Support\Facades\Auth;

class QueueingStack extends Component
{

    public $currentUserDepartment;
    public $currentUserDepartmentId;
    public $clients = [];

    public function mount()
    {
        $this->renderClient();
    }

    public function renderClient()
    {
        $this->currentUserDepartment = session('current_department_name');
        $this->currentUserDepartmentId = session('current_department_id');
        
        $user = Auth::user();

        $userWindow = QmsWindow::where('p_id', $user->p_id)
            ->where('dept_id', $this->currentUserDepartmentId)
            ->first();
 
        $this->clients = QmsClients::where('dept_id', $this->currentUserDepartmentId)
            ->where('w_name', $userWindow->w_name )
            ->take(10)
            ->get();
        
        $this->dispatch('log', [
            'obj' => $this->clients,   
            'level' => 'info'      
        ]);
    }

    public function render()
    {
        return view('livewire.queueing-stack');
    }
}
