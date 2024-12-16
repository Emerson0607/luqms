<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\{
    QmsClients, QmsWindow
};

class QueueingStack extends Component
{
    public $currentUserDepartment, $currentUserDepartmentId, $clients = [];

    public function mount()
    {
        $this->renderClient();
    }

    // public function renderClient()
    // {
    //     $this->currentUserDepartment = session('current_department_name');
    //     $this->currentUserDepartmentId = session('current_department_id');
        
    //     $user = Auth::user();

    //     $userWindow = QmsWindow::where('p_id', $user->p_id)
    //         ->where('dept_id', $this->currentUserDepartmentId)
    //         ->first();
 
    //     if ($userWindow) {
    //         $this->clients = QmsClients::where('dept_id', $this->currentUserDepartmentId)
    //             ->where('w_name', $userWindow->w_name)
    //             ->take(8)
    //             ->get();
    //     } else {
    //         $this->clients = collect();
    //     }
            
    //     $this->dispatch('log', [
    //         'obj' => $this->clients,   
    //         'level' => 'info'      
    //     ]);
    // }

    public function renderClient()
    {
        $this->currentUserDepartment = session('current_department_name');
        $this->currentUserDepartmentId = session('current_department_id');
    
        $user = Auth::user();
    
        $userWindow = QmsWindow::where('p_id', $user->p_id)
            ->where('dept_id', $this->currentUserDepartmentId)
            ->first();
    
        if ($userWindow) {
            // Fetch the clients for the current window
            $this->clients = QmsClients::where('dept_id', $this->currentUserDepartmentId)
                ->where('w_name', $userWindow->w_name)
                ->take(8)
                ->get();
    
            // Check total clients for the current window
            $totalClients = QmsClients::where('dept_id', $this->currentUserDepartmentId)
                ->where('w_name', $userWindow->w_name)
                ->count();
    
            // Dispatch whether to continue polling
            $this->dispatch('updatePollStatus', [
                'shouldPoll' => $totalClients > 0 && $this->clients->count() < 8,
            ]);
        } else {
            $this->clients = collect();
    
            // No active window or clients
            $this->dispatch('updatePollStatus', [
                'shouldPoll' => false,
            ]);
        }
    }
    
    public function render()
    {
        return view('livewire.queueing-stack');
    }
}
