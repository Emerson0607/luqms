<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\Department;

class SessionController extends Controller{

    public function create(){
       
        return view('auth.login');
    }

    public function store(){

        // Validate the incoming request data
        $validatedAttributes = request()->validate([
            'username2' => ['required'],
            'password' => ['required'],
            // 'department' => ['required'], 
        ]);

        // Check if the credentials match
        $user = Auth::attempt([
            'username2' => $validatedAttributes['username2'], 
            'password' => $validatedAttributes['password'],
        ]);

        // If authentication fails, throw an exception with a custom message
        if (!$user) {
            throw ValidationException::withMessages([
                'username2' => trans('Sorry, credentials do not match'),
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
            'department' => null,
            'time_in' => now(),
            'time_out' => null, // This can be updated later
            'date' => now()->toDateString(),
        ]);

        // Fetch all department associations for the authenticated user
        $dmsUserDepts = \App\Models\DmsUserDepts::where('p_id', Auth::user()->p_id)->get();

        // Fetch all departments related to the user (using the department ids)
        $allDepartment = \App\Models\DmsDepartment::whereIn('id', $dmsUserDepts->pluck('dept_id'))->orderBy('name', 'asc')->get();

        // Store the department IDs in the session
        session(['user_department' => $allDepartment->pluck('id')->toArray()]);

   
        return redirect('/');
    }
    
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
