<?php

namespace App\Http\Controllers;
use App\Models\Client;
use App\Models\Window;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Department;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function index()
    {
        return view('queue.dashboard');
    }

    public function window()
    {
        return view('queue.allWindow');
    }

    public function homepage()
    {
        return view('homepage');
    }

    public function logs()
    {
        return view('queue.logs');
    }


     // display in queue stack
    public function getAllClients(){
        $dmsUserDepts = \App\Models\DmsUserDepts::where('p_id',  Auth::user()->p_id)->first();
        $department = \App\Models\DmsDepartment::find($dmsUserDepts->dept_id);
        $clients = \App\Models\Client::where('department', $department->name)->get();

        return response()->json($clients);
    }

    // display all window per department
    public function getAllWindows(){

        $dmsUserDepts = \App\Models\DmsUserDepts::where('p_id',  Auth::user()->p_id)->first();
        $department = \App\Models\DmsDepartment::find($dmsUserDepts->dept_id);
    
        $window_queue = Window::where('qms_window.department', $department->name) // Filter by department name
        ->join('qms_window_list', 'qms_window.w_id', '=', 'qms_window_list.w_id') // Join with window_list table
        ->select('qms_window.*', 'qms_window_list.name as window_name') // Select all columns from window and the window name
        ->get();
    
        // Return the result as JSON
        return response()->json($window_queue);
    }
   
    // get the first data in the stack which is the oldest  
    public function getOldestClient(){
        
        $dmsUserDepts = \App\Models\DmsUserDepts::where('p_id',  Auth::user()->p_id)->first();
        $department = \App\Models\DmsDepartment::find($dmsUserDepts->dept_id);

        $currentDepartment = $department->name;
        $user_w_id = \App\Models\Window::where('p_id', Auth::user()->p_id)->first();
        $client = Client::where('department', $currentDepartment)->oldest()->first();

        if ($client) {
            // Update or create the Window record with matching w_id and department
            Window::updateOrCreate(
                ['w_id' => $user_w_id->w_id, 'department' => $currentDepartment],
                ['name' => $client->name, 'number' => $client->number, 'status' => "Now Serving"]
            );

            // Retrieve the updated or created Window record
            $window = Window::where('w_id', $user_w_id->w_id)
                            ->where('department', $currentDepartment)
                            ->first();
        
            // Delete the client data after storing it in the Window model
            $client->delete();
        
            return response()->json([
                'name' => $window->w_id,
                'number' => $window->number,
                'w_id' => $window->w_id,
                'department' => $window->department,
                'status' => $window->status
            ]);
        } else {
            return response()->json([
                'message' => 'No clients found.'
            ], 404);
        }
    }

    public function waitingQueue(){

        $dmsUserDepts = \App\Models\DmsUserDepts::where('p_id',  Auth::user()->p_id)->first();
        $department = \App\Models\DmsDepartment::find($dmsUserDepts->dept_id);
        $currentDepartment = $department->name;

        $user_w_id = \App\Models\Window::where('p_id', Auth::user()->p_id)->first();

        $window = Window::where('w_id', $user_w_id->w_id)
        ->where('department', $currentDepartment)
        ->first();

        if ($window) {
            // Update or create the Window record with matching w_id and department
            Window::updateOrCreate(
                ['w_id' => $user_w_id->w_id, 'department' => $currentDepartment],
                ['name' => "---", 'number' => "---", 'status' => "Waiting..."]
            );
        
            // Retrieve the updated or created Window record
            $window = Window::where('w_id', $user_w_id->w_id)
                            ->where('department', $currentDepartment)
                            ->first();
    
            return response()->json([
                'name' => $window->name,
                'number' => $window->number,
                'w_id' => $window->w_id,
                'department' => $window->department,
                'status' => $window->status
            ]);
        } else {
            return response()->json([
                'message' => 'No clients found.'
            ], 404);
        }
    }
}