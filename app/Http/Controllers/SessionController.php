<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Http\Request;
use App\Models\Department;
use App\Models\DmsUserDepts;
use App\Models\DmsDepartment;
use App\Models\Logs;

class SessionController extends Controller{

    public function create(){
        return view('auth.login');
    }

    public function store()
    {
        $validatedAttributes = request()->validate([
            'username2' => ['required'],
            'password' => ['required'],
        ]);

        $user = Auth::attempt([
            'username2' => $validatedAttributes['username2'], 
            'password' => $validatedAttributes['password'],
        ]);

        if (!$user) {
            throw ValidationException::withMessages([
                'username2' => trans('Sorry, credentials do not match'),
            ]);
        }

        request()->session()->regenerate();

        $user = Auth::user();

        // Fetch departments for the logged-in user
        $userDepartments = DmsUserDepts::where('p_id', $user->p_id)->get();

        // Loop through each department the user is part of
        foreach ($userDepartments as $userDept) {
            // Get the department name
            $department = DmsDepartment::where('id', $userDept->dept_id)->first();

            // Check for an existing active log for this user and department
            $existingLog = Logs::where('p_id', $user->p_id)
                ->where('dept_id', $department->id)  // Check for the department-specific log
                ->whereNull('time_out')
                ->first();

            if (!$existingLog) {
                // Create a new log entry for this department
                Logs::create([
                    'p_id' => $user->p_id,
                    'p_fname' => $user->p_fname,
                    'p_lname' => $user->p_lname,
                    'dept_id' => $department->id,  // Store the department name
                    'time_in' => now(),
                    'time_out' => null,
                    'date' => now()->toDateString(),
                ]);
            }
        }

        // Fetch all department associations for the authenticated user
        $dmsUserDepts = \App\Models\DmsUserDepts::where('p_id', $user->p_id)->get();
        $allDepartment = \App\Models\DmsDepartment::whereIn('id', $dmsUserDepts->pluck('dept_id'))
            ->orderBy('name', 'asc')
            ->get();

        if ($allDepartment->isNotEmpty()) {
            // Store all department IDs in the session
            session(['user_department' => $allDepartment->pluck('id')->toArray()]);

            // Get the first department object
            $firstDepartment = $allDepartment->first();

            // Store the current department ID and name in the session
            session(['current_department_id' => $firstDepartment->id]);
            session(['current_department_name' => $firstDepartment->name]);
        } else {
            // Clear department session data if no departments are found
            session()->forget(['user_department', 'current_department_id', 'current_department_name']);
        }

        return redirect('/');
    }
    
    public function destroy()
    {
        Logs::where('p_id', Auth::user()->p_id)
        ->where('time_out', null)
        ->update(['time_out' => now()]);
       
        // Destroy all sessions for the user
        $userId = Auth::user()->getAuthIdentifier();
        \Illuminate\Support\Facades\DB::table('sessions')
            ->where('user_id', $userId)
            ->delete();
    
        // Log out the current session
        Auth::logout();
    
        // Regenerate the session to prevent session fixation attacks
        request()->session()->invalidate();
        request()->session()->regenerateToken();
    
        return redirect('/');
    }
   
}
