<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Window;
use App\Models\Client;
use App\Models\QmsWindow;
use App\Models\QmsService;
use App\Models\DmsService;
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
            
        // if ($user_w_id) {
        //     $this->services = QmsService::where('w_name', $user_w_id->w_name)
        //     ->where('dept_id', $currentDepartmentId)->get();
        // }
        if ($user_w_id) {
            $this->services = QmsService::where('w_name', $user_w_id->w_name)
                ->where('dept_id', $currentDepartmentId)
                ->get()
                ->map(function ($service) {
                    $serviceName = DmsService::where('service_id', $service->service_id)->value('service_name');
                    return [
                        'service_id' => $service->service_id,
                        'service_name' => $serviceName,
                    ];
                });
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
            if ($user_w_id->c_service === "No service selected") {
                $this->dispatch("done-next");
            } else {
                QmsWindow::updateOrCreate(
                    ['w_name' => $user_w_id->w_name, 'dept_id' => $currentDepartmentId, 'p_id' => $user_w_id->p_id, 'w_status' => $user_w_id->w_status],
                    ['c_name' => $client->name, 'c_number' => $client->number, 'c_status' => "Now Serving", 'c_service' => "No service selected" ]
                );
    
                $client->delete();
    
                 // Optionally re-fetch the window after updating
                 $this->currentUserWindow = QmsWindow::where('w_name', $user_w_id->w_name)
                 ->where('dept_id', $currentDepartmentId)
                 ->first();
            }


        }else {
            // Dispatch event for no client available
            $this->dispatch('no-client-to-serve');
        }
    }

    public function doneQueue()
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

         // Check if a service is selected
        if (!$this->selectedService) {
            // Trigger the SweetAlert2 notification for no service selected
            $this->dispatch('no-service-selected');
            return;
        } else{

            $window = QmsWindow::where('w_name', $user_w_id->w_name)
                ->where('dept_id', $currentDepartmentId)
                ->first();

            if ($window) {
                $window->update([

                    'c_name' => $user_w_id->c_name,
                    'c_number' => $user_w_id->c_number,
                    'c_status' => "Done",
                    'c_service' => $this->selectedService,
                ]);
            }

            // Delete the client data after storing it in the Window model
            // $client->delete();

            // Optionally re-fetch the window after updating
            $this->currentUserWindow = QmsWindow::where('w_name', $user_w_id->w_name)
                ->where('dept_id', $currentDepartmentId)
                ->first();

                $this->selectedService = null;
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
