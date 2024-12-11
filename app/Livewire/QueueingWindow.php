<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Window;
use App\Models\Client;
use App\Models\QmsWindow;
use App\Models\QmsService;
use App\Models\DmsService;
use Illuminate\Support\Facades\Auth;
use App\Models\QmsClientLogs;

use App\Models\Students;  
use App\Models\QmsClients; 

class QueueingWindow extends Component
{

    public $currentUserDepartment;
    public $currentUserDepartmentId;
    public $currentUserWindow;
    public $selectedService;
    public $services;

    // for push client
    public $studentNo;
    public $dept_id;
    public $gName;
    public $sName;
    
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

        $client = QmsClients::where('dept_id', $currentDepartmentId)->oldest()
            ->where('w_name', $user_w_id->w_name)
            ->first();

        if ($client) {
           
                // QmsWindow::updateOrCreate(
                //     ['w_name' => $user_w_id->w_name, 'dept_id' => $currentDepartmentId, 'p_id' => $user_w_id->p_id, 'w_status' => $user_w_id->w_status],
                //     ['c_name' => $client->name, 'c_number' => $client->number, 'c_status' => "Now Serving", 'c_service' => "No service selected" ]
                // );

                QmsWindow::updateOrCreate([
                    'w_name' => $user_w_id->w_name, 
                    'dept_id' => $currentDepartmentId, 
                    'p_id' => $user_w_id->p_id, 
                    'w_status' => $user_w_id->w_status ],
                    
                    [
                    'gName' => $client->gName, 
                    'sName' => $client->sName, 
                    'studentNo' => $client->studentNo, 
                    'c_status' => "Now Serving", 
                    'c_service' => "No service selected" ]
                );
    
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
            $this->dispatch('no-service-selected');
            return;
        } else{

        $window = QmsWindow::where('w_name', $user_w_id->w_name)
            ->where('dept_id', $currentDepartmentId)
            ->first();

        if ($window->c_status === 'On Break') {
            $this->dispatch('done-no-client-selected');
            return;
        }
        
        if (!$window || $window->studentNo === '---') {
            $this->dispatch('done-no-client-selected');
            return;
        }
        
        if ($window) {
            // Create a new log in QmsClientLogs
            QmsClientLogs::create([
                'gName' => $window->gName,
                'sName' => $window->sName,
                'studentNo' => $window->studentNo,
                'c_service' =>$this->selectedService,
                'dept_id' => $window->dept_id,
                'p_id' => $window->p_id,
                'date' => now(),
            ]);
        }

        $client = QmsClients::where('dept_id', $currentDepartmentId)->oldest()->first();
        if ($client) {
            QmsWindow::updateOrCreate([
                'w_name' => $user_w_id->w_name, 
                'dept_id' => $currentDepartmentId, 
                'p_id' => $user_w_id->p_id, 
                'w_status' => $user_w_id->w_status],
                
                [
                'gName' => $client->gName, 
                'sName' => $client->sName, 
                'studentNo' => $client->studentNo, 
                'c_status' => "Now Serving", 
                'c_service' => "No service selected" ]);
    
                $client->delete();
    
                 // Optionally re-fetch the window after updating
                 $this->currentUserWindow = QmsWindow::where('w_name', $user_w_id->w_name)
                 ->where('dept_id', $currentDepartmentId)
                 ->first();
        
        }

         // if no client in window 
         $clientCount = QmsClients::where('dept_id', $currentDepartmentId)->count();
         if ($clientCount <= 0) {
             QmsWindow::updateOrCreate([
                 'w_name' => $user_w_id->w_name, 
                 'dept_id' => $currentDepartmentId, 
                 'p_id' => $user_w_id->p_id, 
                 'w_status' => $user_w_id->w_status],
                 
                 [
                 'gName' => "---", 
                 'sName' => "---", 
                 'studentNo' =>"---", 
                 'c_status' => "On Break", 
                 'c_service' => "No service selected" ]);
     
                  // Optionally re-fetch the window after updating
                  $this->currentUserWindow = QmsWindow::where('w_name', $user_w_id->w_name)
                  ->where('dept_id', $currentDepartmentId)
                  ->first();
         
         }
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
            QmsWindow::updateOrCreate([
                'w_name' => $user_w_id->w_name, 
                'dept_id' => $currentDepartmentId, 
                'p_id' => $user_w_id->p_id, 
                'w_status' => $user_w_id->w_status,
                'gName' => $user_w_id->gName, 
                'sName' => $user_w_id->sName, 
                'studentNo' => $user_w_id->studentNo, 
            ],[
               
                'c_status' => "On Break", 
                'c_service' => "ID and Reg." ]
            );

            // Optionally re-fetch the window after updating
            $this->currentUserWindow = QmsWindow::where('w_name', $user_w_id->w_name)
                ->where('dept_id', $currentDepartmentId)
                ->first();
 
        }
    }

    public function continueQueue()
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
            QmsWindow::updateOrCreate([
                'w_name' => $user_w_id->w_name, 
                'dept_id' => $currentDepartmentId, 
                'p_id' => $user_w_id->p_id, 
                'w_status' => $user_w_id->w_status,
                'gName' => $user_w_id->gName, 
                'sName' => $user_w_id->sName, 
                'studentNo' => $user_w_id->studentNo, 
            ],[
               
                'c_status' => "Now serving", 
                'c_service' => "ID and Reg." ]
            );

            // Optionally re-fetch the window after updating
            $this->currentUserWindow = QmsWindow::where('w_name', $user_w_id->w_name)
                ->where('dept_id', $currentDepartmentId)
                ->first();
 
        }
    }

    // for push clients
    public function pushClient()
    {

        $this->currentUserDepartment = session('current_department_name');
        $this->currentUserDepartmentId = session('current_department_id');
        $currentDepartment = $this->currentUserDepartment;
        $currentDepartmentId = $this->currentUserDepartmentId;
    
        $student = Students::where('studentNo', $this->studentNo)->first();

        $randomWindow = QmsWindow::where('p_id', Auth::user()->p_id)
        ->where('dept_id', $currentDepartmentId)
        ->first();


        if (!$randomWindow) {
            session()->flash('error', 'No available window found.');
            return;
        }

        if ($student) {
            QmsClients::create([
                'studentNo' => $this->studentNo,
                'gName' => $student->GName,
                'sName' => $student->Sname,
                'dept_id' => $currentDepartmentId,   
                'w_name' => $randomWindow->w_name
            ]);
    
            session()->flash('message', 'Client successfully pushed!');

        } else {
            QmsClients::create([
                'studentNo' => $this->studentNo,
                'gName' => 'Guest', 
                'sName' => 'Guest',
                'w_name' => $randomWindow->w_name,
                'dept_id' => $currentDepartmentId
            ]);
    
            session()->flash('message', 'Client successfully pushed!');
        }
    
        // Optionally reset the fields after submission
        $this->reset('studentNo', 'gName', 'sName', 'dept_id');
    }
    
    public function generateClient()
    {
        QmsClients::factory(10)->create();
        session()->flash('message', 'Guest successfully pushed!');
    }

    public function render()
    {
        return view('livewire.queueing-window');
    }
}
