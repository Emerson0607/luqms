<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Window;
use App\Models\Client;
use App\Models\QmsWindow;
use App\Models\QmsService;
use Illuminate\Support\Facades\Auth;

class QueueingWindow extends Component
{

    public $currentUserDepartment;
    public $currentUserWindow;


    public $currentUserDepartmentId;
    public $services;
    public $selectedService;
    
    public function mount(){
        $this->renderQueue();
        $this->selectedService = null; 
    }

    public function renderQueue(){
        $this->currentUserDepartment = session('current_department_name');
        $this->currentUserDepartmentId = session('current_department_id');
        $currentDepartment = $this->currentUserDepartment;
        $currentDepartmentId = $this->currentUserDepartmentId;

        $user_w_id = QmsWindow::where('p_id', Auth::user()->p_id)
            ->where('dept_id', $currentDepartmentId)
            ->where('w_status', 1)
            ->first();

        $this->currentUserWindow = $user_w_id ? QmsWindow::where('w_name', $user_w_id->w_name)
            ->where('dept_id', $currentDepartmentId)
            ->first()
            : null;
            
        if ($user_w_id) {
            $this->services = QmsService::where('w_name', $user_w_id->w_name)
            ->where('dept_id', $currentDepartmentId)->get();
        }
      
    }

    public function nextQueue()
    {
        $this->currentUserDepartment = session('current_department_name');
        $this->currentUserDepartmentId = session('current_department_id');
        $currentDepartment = $this->currentUserDepartment;
        $currentDepartmentId = $this->currentUserDepartmentId;

        // Check if a window is assigned to the user
        $user_w_id = QmsWindow::where('p_id', Auth::user()->p_id)
            ->where('dept_id', $currentDepartmentId)
            ->first();

        if (!$user_w_id) {
            // Trigger the SweetAlert2 notification
            $this->dispatchBrowserEvent('no-personnel-assigned');
            return;
        }

        $client = Client::where('department', $currentDepartment)->oldest()->first();

        if ($client) {
            QmsWindow::updateOrCreate(
                ['w_name' => $user_w_id->w_name, 'dept_id' => $currentDepartmentId, 'p_id' => $user_w_id->p_id, 'w_status' => $user_w_id->w_status],
                ['c_name' => $client->name, 'c_number' => $client->number, 'c_status' => "Now Serving", 'c_service' => $this->selectedService ]
            );

            // Delete the client data after storing it in the Window model
            $client->delete();

            // Optionally re-fetch the window after updating
            $this->currentUserWindow = QmsWindow::where('w_name', $user_w_id->w_name)
                ->where('dept_id', $currentDepartmentId)
                ->first();
                
        }else {
            // Dispatch event for no client available
            $this->dispatch('no-client-to-serve');
        }
    }

    public function waitQueue()
    {
        $this->currentUserDepartment = session('current_department_name');
        $this->currentUserDepartmentId = session('current_department_id');
        $currentDepartment = $this->currentUserDepartment;
        $currentDepartmentId = $this->currentUserDepartmentId;
        
        // Check if a window is assigned to the user
        $user_w_id = QmsWindow::where('p_id', Auth::user()->p_id)
            ->where('dept_id', $currentDepartmentId)
            ->first();

        if ($user_w_id) {
            QmsWindow::updateOrCreate(
                ['w_name' => $user_w_id->w_name, 'dept_id' => $currentDepartmentId, 'p_id' => $user_w_id->p_id, 'w_status' => $user_w_id->w_status],
                ['c_name' => "---", 'c_number' => "---", 'c_status' => "Waiting...", 'c_service' => "ID and Reg." ]
            );

            // Optionally re-fetch the window after updating
            $this->currentUserWindow = QmsWindow::where('w_name', $user_w_id->w_name)
                ->where('dept_id', $currentDepartmentId)
                ->first();
 
        }
    }

    public function generateClient()
    {
        Client::factory(10)->create();
    }

    public function render()
    {
        return view('livewire.queueing-window');
    }
}
