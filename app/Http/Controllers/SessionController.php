<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\Department;

class SessionController extends Controller
{
    public function create()
    {
        $allDepartment = \App\Models\DmsDepartment::orderBy('name', 'asc')->get();
        return view('auth.login', compact('allDepartment'));
    }

    public function store()
    {
        // $validatedattributes = request()->validate([
        //     'username2' => ['required'],
        //     'password' => ['required'],
        //     'department' => ['required'], 
        // ]);
    
        // if (!Auth::attempt($validatedattributes)) {
        //     throw ValidationException::withMessages([
        //         'username2' => trans('Sorry, credentials do not match'),
        //     ]);
        // }
    
        // request()->session()->regenerate();


        // Validate the incoming request data
        $validatedAttributes = request()->validate([
            'username2' => ['required'],
            'password' => ['required'],
            'department' => ['required'],  // Ensure a department is selected
        ]);

        // Check if the credentials match
        $user = Auth::attempt([
            'username2' => $validatedAttributes['username2'], // Assuming your users use 'username' to log in
            'password' => $validatedAttributes['password'],
        ]);

        // If authentication fails, throw an exception with a custom message
        if (!$user) {
            throw ValidationException::withMessages([
                'username2' => trans('Sorry, credentials do not match'),
            ]);
        }

        // Retrieve the department information for the authenticated user
        $dmsUserDepts = \App\Models\DmsUserDepts::where('p_id', Auth::user()->p_id)->first();
        $department = \App\Models\DmsDepartment::find($dmsUserDepts->dept_id);
        $currentDepartment = $department ? $department->name : null; // Ensure that department exists


         // Store the department in the session
        session(['user_department' => $currentDepartment]);

        // Check if the selected department matches the user's department
        if ($validatedAttributes['department'] != $currentDepartment) {
            throw ValidationException::withMessages([
                'department' => trans('The selected department does not match your profile'),
            ]);
        }

        // Regenerate session to prevent session fixation
        request()->session()->regenerate();
    
        // Retrieve current user data
        $user = Auth::user();
    
        // Check if the user already has an active log (time_out === null)
        $existingLog = \App\Models\Logs::where('p_id', $user->p_id)
            ->whereNull('time_out')
            ->first();
    
        if ($existingLog) {
            // Handle the case where the user has an active log
            // You can either return an error or update the `time_out` field
            return redirect('/')->withErrors([
                'login' => 'You already have an active session.',
            ]);
        }

    
        // Log user login info
        \App\Models\Logs::create([
            'p_id' => $user->p_id,
            'p_fname' => $user->p_fname,
            'p_lname' => $user->p_lname,
            'department' => $currentDepartment, // Make sure the `department` field exists on the User model
            'time_in' => now(),
            'time_out' => null, // This can be updated later
            'date' => now()->toDateString(),
        ]);
    
        return redirect('/');
    }
    

    // public function destroy()
    // {


    //     $log = \App\Models\Logs::where('p_id', Auth::user()->p_id)
    //     ->whereNull('time_out') // Only get logs where `time_out` is null
    //     ->orderBy('created_at', 'desc') // Sort by created_at (most recent first)
    //     ->first(); // Get the first (most recent) log entry

    //     // If the log entry exists, update the time_outxa
    //     if ($log) {
    //     $log->time_out = now(); // Set the time_out to the current time
    //     $log->save(); // Save the updated log entry
    //     }

    

    //     Auth::logout();





    //     return redirect('/');
    // }

    // when destroy it automatic logout all session of specific account
    public function destroy()
    {
        // Update the most recent log with time_out
        $log = \App\Models\Logs::where('p_id', Auth::user()->p_id)
            ->whereNull('time_out') // Only get logs where `time_out` is null
            ->orderBy('created_at', 'desc') // Sort by created_at (most recent first)
            ->first();
    
        if ($log) {
            $log->time_out = now(); // Set the time_out to the current time
            $log->save(); // Save the updated log entry
        }
    
        // Destroy all sessions for the user
        $userId = Auth::user()->getAuthIdentifier(); // Get the user's ID
        \Illuminate\Support\Facades\DB::table('sessions')
            ->where('user_id', $userId) // Replace 'user_id' with your session table column name
            ->delete(); // Delete all sessions for the user
    
        // Log out the current session
        Auth::logout();
    
        // Regenerate the session to prevent session fixation attacks
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    
        return redirect('/');
    }
    
}
