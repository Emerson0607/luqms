<?php

namespace App\Livewire;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\{
    Window, Client, Students, QmsWindow, QmsService, DmsService, QmsClients, QmsClientLogs, QmsSharedClient
};


class QueueingWindow extends Component
{
    public $currentDepartment, $currentDepartmentId, $currentUserWindow, $selectedService, $services;
    public $studentNo, $dept_id, $gName, $sName, $clients = [];
    
    public function __construct()
    {
        $this->setCurrentDepartment();
    }

    protected function setCurrentDepartment()
    {
        $this->currentDepartment = session('current_department_name');
        $this->currentDepartmentId = session('current_department_id');
    }

    public function mount(){
        $this->renderQueue();
        $this->selectedService = null; 
        $this->renderClient();
    }

    public function renderQueue(){
      
        $user_w_id = QmsWindow::where('p_id', Auth::user()->p_id)
            ->where('dept_id', $this->currentDepartmentId)
            ->where('w_status', 1)
            ->first();

        $this->currentUserWindow = $user_w_id ? QmsWindow::where('w_name', $user_w_id->w_name)
            ->where('dept_id', $this->currentDepartmentId)
            ->first()
            : null;
       
        if ($user_w_id) {
            $this->services = QmsService::where('w_name', $user_w_id->w_name)
                ->where('dept_id', $this->currentDepartmentId)
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
        // Check if a window is assigned to the user
        $user_w_id = QmsWindow::where('p_id', Auth::user()->p_id)
            ->where('dept_id', $this->currentDepartmentId)
            ->first();

        if (!$user_w_id) {
            // Trigger the SweetAlert2 notification
            $this->dispatchBrowserEvent('no-personnel-assigned');
            return;
        }

        // if shared window is none
        if ($user_w_id->shared_name === 'None') {
            $client = QmsClients::where('dept_id', $this->currentDepartmentId)->oldest()
            ->where('w_name', $user_w_id->w_name)
            ->first();

            if ($client) {

                    QmsWindow::updateOrCreate([
                        'w_name' => $user_w_id->w_name, 
                        'dept_id' => $this->currentDepartmentId, 
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
                    $this->currentUserWindow = QmsWindow::where('w_name', $user_w_id->w_name)
                    ->where('dept_id', $this->currentDepartmentId)
                    ->first();
            
            }else {
                // Dispatch event for no client available
                $this->dispatch('no-client-to-serve');
            }
        
        } else {
            $client = QmsSharedClient::where('dept_id', $this->currentDepartmentId)->oldest()
                ->where('w_name', $user_w_id->shared_name)
                ->first();

            if ($client) {
                    QmsWindow::updateOrCreate([
                        'w_name' => $user_w_id->w_name, 
                        'dept_id' => $this->currentDepartmentId, 
                        'p_id' => $user_w_id->p_id, 
                        'shared_name' => $user_w_id->shared_name, 
                        'w_status' => $user_w_id->w_status ],
                        
                        [
                        'gName' => $client->gName, 
                        'sName' => $client->sName, 
                        'studentNo' => $client->studentNo, 
                        'c_status' => "Now Serving", 
                        'c_service' => "No service selected" ]
                    );
        
                    $client->delete();
                    $this->currentUserWindow = QmsWindow::where('shared_name', $user_w_id->shared_name)
                        ->where('dept_id', $this->currentDepartmentId)
                        ->first();
                
            }else {
                // Dispatch event for no client available
                $this->dispatch('no-client-to-serve');
            }
        }
        
    }

    public function doneQueue()
    {
        // Check if a window is assigned to the user
        $user_w_id = QmsWindow::where('p_id', Auth::user()->p_id)
            ->where('dept_id', $this->currentDepartmentId)
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
                ->where('dept_id', $this->currentDepartmentId)
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

            // if shared window 
            if ($window->shared_name === 'None') {
                $client = QmsClients::where('dept_id', $this->currentDepartmentId)
                    ->where('w_name',$window->w_name )
                    ->oldest()
                    ->first();

                if ($client) {
                    QmsWindow::updateOrCreate([
                        'w_name' => $user_w_id->w_name, 
                        'dept_id' => $this->currentDepartmentId, 
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
                        ->where('dept_id', $this->currentDepartmentId)
                        ->first();
                
                }
    
                // if no client in window 
                $clientCount = QmsClients::where('dept_id', $this->currentDepartmentId)
                    ->where('w_name', $window->w_name )
                    ->count();

                if ($clientCount <= 0) {
                    QmsWindow::updateOrCreate([
                        'w_name' => $user_w_id->w_name, 
                        'dept_id' => $this->currentDepartmentId, 
                        'p_id' => $user_w_id->p_id, 
                        'shared_name' => $user_w_id->shared_name, 
                        'w_status' => $user_w_id->w_status],
                        
                        [
                        'gName' => "---", 
                        'sName' => "---", 
                        'studentNo' =>"---", 
                        'c_status' => "On Break", 
                        'c_service' => "No service selected" ]);
            
                        // Optionally re-fetch the window after updating
                        $this->currentUserWindow = QmsWindow::where('w_name', $user_w_id->w_name)
                        ->where('dept_id', $this->currentDepartmentId)
                        ->first();
                
                } 

            } 
            else {
                $client = QmsSharedClient::where('dept_id', $this->currentDepartmentId)
                    ->where('w_name', $window->shared_name)
                    ->oldest()
                    ->first();

                if ($client) {
                    QmsWindow::updateOrCreate([
                        'w_name' => $user_w_id->w_name, 
                        'dept_id' => $this->currentDepartmentId, 
                        'p_id' => $user_w_id->p_id, 
                        'shared_name' => $user_w_id->shared_name,
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
                            ->where('dept_id', $this->currentDepartmentId)
                            ->first();
                
                }

                // if no client in window 
                $clientCount = QmsSharedClient::where('dept_id', $this->currentDepartmentId)
                    ->where('w_name', $window->shared_name)
                    ->count();

                if ($clientCount <= 0) {
                    QmsWindow::updateOrCreate([
                        'w_name' => $user_w_id->w_name, 
                        'dept_id' => $this->currentDepartmentId, 
                        'p_id' => $user_w_id->p_id, 
                        'shared_name' => $user_w_id->shared_name,
                        'w_status' => $user_w_id->w_status],
                        
                        [
                        'gName' => "---", 
                        'sName' => "---", 
                        'studentNo' =>"---", 
                        'c_status' => "On Break", 
                        'c_service' => "No service selected" ]);
            
                        // Optionally re-fetch the window after updating
                        $this->currentUserWindow = QmsWindow::where('w_name', $user_w_id->w_name)
                            ->where('dept_id', $this->currentDepartmentId)
                            ->first();
                }
            }
                $this->selectedService = null;
        }
    }

    public function waitQueue()
    {
        // Check if a window is assigned to the user
        $user_w_id = QmsWindow::where('p_id', Auth::user()->p_id)
            ->where('dept_id',$this->currentDepartmentId)
            ->first();

        if ($user_w_id) {
            QmsWindow::updateOrCreate([
                'w_name' => $user_w_id->w_name, 
                'dept_id' => $this->currentDepartmentId, 
                'p_id' => $user_w_id->p_id, 
                'w_status' => $user_w_id->w_status,
                'gName' => $user_w_id->gName, 
                'sName' => $user_w_id->sName, 
                'studentNo' => $user_w_id->studentNo, 
                'shared_name' => $user_w_id->shared_name,
            ],[
               
                'c_status' => "On Break", 
                'c_service' => "ID and Reg." ]
            );

            // Optionally re-fetch the window after updating
            $this->currentUserWindow = QmsWindow::where('w_name', $user_w_id->w_name)
                ->where('dept_id', $this->currentDepartmentId)
                ->first();
 
        }
    }

    public function continueQueue()
    {
        // Check if a window is assigned to the user
        $user_w_id = QmsWindow::where('p_id', Auth::user()->p_id)
            ->where('dept_id', $this->currentDepartmentId)
            ->first();

        if ($user_w_id) {
            QmsWindow::updateOrCreate([
                'w_name' => $user_w_id->w_name, 
                'dept_id' => $this->currentDepartmentId, 
                'p_id' => $user_w_id->p_id, 
                'w_status' => $user_w_id->w_status,
                'gName' => $user_w_id->gName, 
                'sName' => $user_w_id->sName, 
                'studentNo' => $user_w_id->studentNo, 
                'shared_name' => $user_w_id->shared_name,
            ],[
               
                'c_status' => "Now serving", 
                'c_service' => "ID and Reg." ]
            );

            // Optionally re-fetch the window after updating
            $this->currentUserWindow = QmsWindow::where('w_name', $user_w_id->w_name)
                ->where('dept_id', $this->currentDepartmentId)
                ->first();
 
        }
    }

    // for !shared window factory
    public function pushClient()
    {
        $student = Students::where('studentNo', $this->studentNo)->first();

        $randomWindow = QmsWindow::where('p_id', Auth::user()->p_id)
        ->where('dept_id', $this->currentDepartmentId)
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
                'dept_id' => $this->currentDepartmentId,   
                'w_name' => $randomWindow->w_name
            ]);
    
            session()->flash('message', 'Client successfully pushed!');

        } else {
            QmsClients::create([
                'studentNo' => $this->studentNo,
                'gName' => 'Guest', 
                'sName' => 'Guest',
                'w_name' => $randomWindow->w_name,
                'dept_id' => $this->currentDepartmentId
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

    // for shared window factory
    public function pushClient_s()
    {
        $student = Students::where('studentNo', $this->studentNo)->first();
   
        $randomWindow = QmsWindow::where('p_id', Auth::user()->p_id)
           ->where('dept_id', $this->currentDepartmentId)
           ->first();
   
   
        if (!$randomWindow) {
            session()->flash('error', 'No available window found.');
            return;
        }
   
        if ($student) {
            QmsSharedClient::create([
                'studentNo' => $this->studentNo,
                'gName' => $student->GName,
                'sName' => $student->Sname,
                'dept_id' => $this->currentDepartmentId,   
                'w_name' => $randomWindow->shared_name
            ]);
       
            session()->flash('message', 'Client successfully pushed!');
   
        } else {
            QmsSharedClient::create([
                'studentNo' => $this->studentNo,
                'gName' => 'Guest', 
                'sName' => 'Guest',
                'w_name' => $randomWindow->shared_name,
                'dept_id' => $this->currentDepartmentId
            ]);
       
            session()->flash('message', 'Client successfully pushed!');
        }
       
           // Optionally reset the fields after submission
        $this->reset('studentNo', 'gName', 'sName', 'dept_id');
    }
       
    public function generateClient_s()
    {
        QmsSharedClient::factory(10)->create();
        session()->flash('message', 'Guest successfully pushed!');
    }

    // for clients queue stack

    // for queue stack
    public function renderClient()
    {
        $user = Auth::user();
        $userWindow = QmsWindow::where('p_id', $user->p_id)
            ->where('dept_id', $this->currentDepartmentId)
            ->first();
         
        // dd($userWindow->shared_name, gettype($userWindow->shared_name));
        //  dd($userWindow->dept_id, gettype($userWindow->dept_id));
        if ($userWindow) {

            if ($userWindow->shared_name === 'None') {
                $this->clients = QmsClients::where('dept_id', $this->currentDepartmentId)
                ->where('w_name', $userWindow->w_name)
                ->take(8)
                ->get();
    
                $totalClients = QmsClients::where('dept_id', $this->currentDepartmentId)
                    ->where('w_name', $userWindow->w_name)
                    ->count();
                
                $this->dispatch('updatePollStatus', [
                    'shouldPoll' => $totalClients > 0 && $this->clients->count() < 8,
                ]);
            }else{
                $this->clients = QmsSharedClient::where('dept_id', $this->currentDepartmentId)
                ->where('w_name', $userWindow->shared_name)
                ->take(8)
                ->get();
    
                $totalClients = QmsSharedClient::where('dept_id', $this->currentDepartmentId)
                    ->where('w_name', $userWindow->shared_name)
                    ->count();
                
                $this->dispatch('updatePollStatus', [
                    'shouldPoll' => $totalClients > 0 && $this->clients->count() < 8,
                ]);
            }

            
        } else {
            $this->clients = collect();
            $this->dispatch('updatePollStatus', [
                'shouldPoll' => false,
            ]);
        }
    }

    public function render()
    {
        return view('livewire.queueing-window');
    }
}
