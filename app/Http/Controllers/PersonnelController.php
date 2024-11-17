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


class PersonnelController extends Controller
{
    
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

        // Check if p_id already exists in the Window table
        $pIdExists = \App\Models\Window::where('p_id', $validatedAttributes['p_id'])->exists();
        if ($pIdExists) {
            return redirect()->back()->with('error', 'The provided p_id already exists.')->withInput();
        }

        // Create a new record in the Window table
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
    
            // Update the status of the corresponding WindowList entry
            $windowList = \App\Models\WindowList::where('w_id', $window->w_id)->first();
            if ($windowList) {
                $windowList->status = 0;
                $windowList->save();
            }
            return redirect()->back()->with('success', 'Window deleted successfully.');
        } else {
            // Handle case where window with the given p_id doesn't exist
            return redirect()->back()->with('error', 'Window not found.');
        }
    }

}
