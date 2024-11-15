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

    public function personnel()
    {
        $dmsUserDepts = \App\Models\DmsUserDepts::where('p_id', Auth::user()->p_id)->first();
        $department = \App\Models\DmsDepartment::find($dmsUserDepts->dept_id);
        $currentDepartment = $department->name;
    
        $users = \App\Models\Window::where('department', $currentDepartment)->get();
    
        // Retrieve corresponding names from WindowList and personnel
        foreach ($users as $user) {
            // Get WindowList name for w_id
            $windows_name = \App\Models\WindowList::where('w_id', $user->w_id)->first();
            $user->w_name = $windows_name -> name;

            $personnel = \App\Models\DmsUserDepts::where('p_id', $user->p_id)->first();

            $user->p_fname = $personnel->firstname ;
            $user->p_lname = $personnel->lastname ;
        }
    
        $departments = \App\Models\DmsUserDepts::where('dept_id', $dmsUserDepts->dept_id)->get();
        $all_windows = \App\Models\WindowList::where('department', $currentDepartment)
        ->where('status', 0)
        ->get();


        return view('user.personnel', compact(['users', 'departments', 'all_windows', 'currentDepartment']));
    }
    
    public function p_store()
    {
        $validatedAttributes = request()->validate([
            'p_id' => ['required'],
            'w_id' => ['required'],
            'department' => ['required'],
        ]);
    
        Window::create($validatedAttributes);

         // Update the status of the corresponding WindowList entry to 1
        $windowList = \App\Models\WindowList::where('w_id', $validatedAttributes['w_id'])->first();
        if ($windowList) {
            $windowList->status = 1;
            $windowList->save();
        }
    
        // Redirect to the personnel function after storing
        return redirect()->route('personnel');
    }
    


    public function destroy($pId)
    {
        // Find the Window record where p_id equals the provided $pId
        $window = Window::where('p_id', $pId)->first();
    
        // Check if the window exists
        if ($window) {
            // Delete the window record
            $window->delete();
    
            // Redirect back with a success message
            return redirect()->back()->with('success', 'Window deleted successfully.');
        } else {
            // Handle case where window with the given p_id doesn't exist
            return redirect()->back()->with('error', 'Window not found.');
        }
    }







     // display in queue stack
    public function getAllClients(){
        // $currentDepartment = Auth::user()->department;
        // $clients = Client::where('department', $currentDepartment)->get();
        // return response()->json($clients);

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
        $user_w_id = \App\Models\WindowList::where('p_id', Auth::user()->p_id)->first();
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

        $user_w_id = \App\Models\WindowList::where('p_id', Auth::user()->p_id)->first();

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





    
    // // for user management
    // public function user()
    // {
    //     $users = User::all();
    //     $departments = Department::all(); 
      
    //     return view('user.user', compact(['users', 'departments']));
    // }

    // public function store()
    // {
    //     $validatedattributes = request()->validate([
    //         'name' => ['required'],
    //         'w_id' => ['required'],
    //         'department' => ['required'],
    //         'email' => ['required', 'email', 'max:254'],
    //         'password' => ['required', Password::min(8)->letters()->numbers()] //password_confirmation

    //     ]);

    //     $user = User::create($validatedattributes);


    //     return redirect('/user');
    // }

    // // Update the specified user in storage
    // public function update(Request $request, User $user)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'department' => 'required|string|max:255',
    //         'email' => 'required|email|unique:users,email,' . $user->id,
    //         'password' => 'nullable|string|min:6',
    //     ]);

   
    //     $user->name = $request->name;
    //     $user->department = $request->department;
    //     $user->email = $request->email;

    //     // If a new password is provided, update it
    //     if ($request->filled('password')) {
    //         $user->password = bcrypt($request->password);
    //     }

    //     $user->save();

    //     return redirect()->back()->with('success', 'User updated successfully.');
    // }

    // // Remove the specified user from storage
    // public function destroy(User $user)
    // {
    //     $user->delete();

    //     return redirect()->back()->with('success', 'User deleted successfully.');
    // }

}