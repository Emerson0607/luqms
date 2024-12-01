<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Window;
use App\Models\Client;
use Illuminate\Support\Facades\Auth;

class QueueingWindow extends Component
{

    public $currentUserDepartment;
    public $currentUserWindow;
    
    public function mount(){
        $this->renderQueue();
    }

    public function renderQueue(){
        $this->currentUserDepartment = session('current_department_name');
        $currentDepartment = $this->currentUserDepartment;

        $user_w_id = Window::where('p_id', Auth::user()->p_id)
            ->where('department', $currentDepartment)
            ->first();

        $this->currentUserWindow = $user_w_id ? Window::where('w_id', $user_w_id->w_id)
            ->where('department', $currentDepartment)
            ->first()
            : null;
    }

    public function nextQueue()
    {
        $this->currentUserDepartment = session('current_department_name');
        $currentDepartment = $this->currentUserDepartment;

        // Check if a window is assigned to the user
        $user_w_id = Window::where('p_id', Auth::user()->p_id)
            ->where('department', $currentDepartment)
            ->first();

        if (!$user_w_id) {
            // Trigger the SweetAlert2 notification
            $this->dispatchBrowserEvent('no-personnel-assigned');
            return;
        }

        $client = Client::where('department', $currentDepartment)->oldest()->first();

        if ($client) {
            Window::updateOrCreate(
                ['w_id' => $user_w_id->w_id, 'department' => $currentDepartment, 'p_id' => $user_w_id->p_id],
                ['name' => $client->name, 'number' => $client->number, 'status' => "Now Serving"]
            );

            // Delete the client data after storing it in the Window model
            $client->delete();

            // Optionally re-fetch the window after updating
            $this->currentUserWindow = Window::where('w_id', $user_w_id->w_id)
                ->where('department', $currentDepartment)
                ->first();
        }
    }

    public function waitQueue()
    {
        $this->currentUserDepartment = session('current_department_name');
        $currentDepartment = $this->currentUserDepartment;
        
        // Check if a window is assigned to the user
        $user_w_id = Window::where('p_id', Auth::user()->p_id)
            ->where('department', $currentDepartment)
            ->first();

        if ($user_w_id) {
            // Update or create the Window record with matching w_id and department
            Window::updateOrCreate(
                ['w_id' => $user_w_id->w_id, 'department' => $currentDepartment],
                ['name' => "---", 'number' => "---", 'status' => "Waiting..."]
            );
        
            // Retrieve the updated or created Window record
            $window = Window::where('w_id', $user_w_id->w_id)
                            ->where('department', $currentDepartment)
                            ->first();
        }
    }

    public function generateClient()
    {
        Client::factory(5)->create();
    }

    public function render()
    {
        return view('livewire.queueing-window');
    }
}
