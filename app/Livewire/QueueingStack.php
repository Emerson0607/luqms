<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\QmsClients;

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
        // $this->clients = Client::where('department', $this->currentUserDepartment)
        // ->take(10)
        // ->get();

        $this->clients = QmsClients::where('dept_id', $this->currentUserDepartmentId)
        ->take(10)
        ->get();
        
        // Dispatch log event to log client data in the browser console
        $this->dispatch('log', [
            'obj' => $this->clients,   // Log the clients data
            'level' => 'info'          // You can use 'info', 'warn', 'debug', etc.
        ]);
    }

    public function render()
    {
        return view('livewire.queueing-stack');
    }
}
