<?php

namespace App\Http\Controllers;
use App\Models\Client;
use App\Models\Window;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Department;
use App\Models\WindowList;
use App\Models\QmsWindow;
use App\Models\QmsService;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class PersonnelController extends Controller
{

    protected $currentDepartment;
    protected $currentDepartmentId;

    public function __construct()
    {
        $this->currentDepartment = session('current_department_name');
        $this->currentDepartmentId = session('current_department_id');
    }

    // Add this function to the PersonnelController class
    private function isDepartmentHead()
    {
        // Get the department for the authenticated user
        $dmsUserDepts = \App\Models\DmsUserDepts::where('p_id', Auth::user()->p_id)->first();
        if ($dmsUserDepts) {
            // Check if the user is the dept_head
            $department = \App\Models\DmsDepartment::find($dmsUserDepts->dept_id);
            return $department && $department->dept_head == Auth::user()->p_id;
        }
        return false;
    }

    public function personnel()
    {

        // Check if the authenticated user is a department head
        if (!$this->isDepartmentHead()) {
            return redirect()->route('home')->with('error', 'You do not have access to this resource.');
        }

        $users = \App\Models\Window::where('department', $this->currentDepartment)->get();
    
        foreach ($users as $user) {
            // Get WindowList name for w_id
            $windows_name = \App\Models\WindowList::where('w_id', $user->w_id)->first();
            $user->w_name = $windows_name -> name;

            $department = \App\Models\DmsDepartment::where('name', $this->currentDepartment)->first();
            $dmsUserDepts = \App\Models\DmsUserDepts::where('dept_id', $department->id)->first();

            $personnel = \App\Models\DmsUserDepts::where('p_id', $user->p_id)->first();

            $user->p_fname = $personnel->firstname ;
            $user->p_lname = $personnel->lastname ;
        }

         // Sort the $users collection by 'w_name' in ascending order
        $users = $users->sortBy('w_name');
    
        $departments = \App\Models\DmsUserDepts::where('dept_id', $this->currentDepartmentId)->get();
        $all_windows = \App\Models\WindowList::where('department', $this->currentDepartment)
        ->where('status', 0)
        ->get();    

        $queueWindowLists = QmsWindow::where('dept_id', $this->currentDepartmentId)->get();

        $all_windows_tables = \App\Models\WindowList::where('department', $this->currentDepartment)->orderBy('name', 'asc')->get();

        return view('user.personnel', compact(['users', 'departments', 'all_windows', 'all_windows_tables', 'queueWindowLists']))
            ->with([
                    'currentDepartment' => $this->currentDepartment,
                    'currentDepartmentId' => $this->currentDepartmentId,
                ]);
    }
    

    public function p_store(){
        $validatedAttributes = request()->validate([
            'p_id' => ['required'],
            'w_name' => ['required'],
            'dept_id' => ['required'],
            'w_status' => ['required'],
            'services' => ['array'],
            'services.*' => ['exists:dms_service,service_id'],  // Validate that each service ID exists in the qms_services table
        ]);

        // Check if w_id already exists in the Window table
        $wIdExists = QmsWindow::where('w_name', $validatedAttributes['w_name'])
        ->where('dept_id', $validatedAttributes['dept_id'])
        ->exists();

        if ($wIdExists) {
            return redirect()->back()->with('error', 'The provided window already exists.')->withInput();
        }

        // Create a new record in the Window table
        QmsWindow::create($validatedAttributes);

            foreach ($validatedAttributes['services'] as $serviceId) {
                // Store the service in qms_service table
                QmsService::create([
                    'w_name' => $validatedAttributes['w_name'],
                    'dept_id' => $validatedAttributes['dept_id'],
                    'service_id' => $serviceId,
                ]);
            }
      

        return redirect()->route('personnel');
    }
    
    public function update(Request $request, $pId)
    {
        // Validate the incoming request
        $request->validate([
            'editWName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('qms_windows', 'w_name')->ignore($pId),
            ],
            'editDeptId' => ['required'],
            'editPersonnel' => 'required|string|max:255',
            'editStatus' => 'required|string|max:255',
            'editService' => ['array'],
            'editService.*' => ['exists:dms_service,service_id'],
        ], [
            // Custom error message for w_name uniqueness
            'editWName.unique' => 'The window name already exists. Please choose a different name.',
        ]);
    
        // Debug to ensure form is submitting correctly
        // dd($request->all());
        // Fetch the Window record
        $window = QmsWindow::findOrFail($pId); 
        

        $qmsServiceWindow = QmsService::where('w_name', $window->w_name)
                              ->where('dept_id', $window->dept_id);

        if ($qmsServiceWindow->exists()) {
            $qmsServiceWindow->delete();
        }


        // Save new services
        if ($request->editService) {
            foreach ($request->editService as $serviceId) {
                QmsService::create([
                    'w_name' => $request->editWName,
                    'dept_id' => $request->editDeptId,
                    'service_id' => $serviceId,
                ]);
            }
        }
    
        // Update the model's attributes
        $window->w_name = $request->editWName;
        $window->p_id = $request->editPersonnel;
        $window->w_status = $request->editStatus;
        $window->save();

        return redirect()->back()->with('success', 'Window updated successfully.');
    }

    public function getAssociatedServices($wName, $deptId)
    {
        try {
            // Replace QmsWindow with the correct table/model and adjust the query if needed
            $associatedServices = QmsService::where('w_name', $wName)
                                           ->where('dept_id', $deptId)
                                           ->pluck('service_id');
    
            return response()->json($associatedServices);
        } catch (\Exception $e) {
            Log::error('Error fetching associated services: ' . $e->getMessage());
            return response()->json(['error' => 'Something went wrong'], 500);
        }
    }

    public function destroy($pId){
        $window = QmsWindow::where('id', $pId)->first();

        if ($window) {
            $window->delete();
            return redirect()->back()->with('success', 'Window deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Window not found.');
        }
    }


    public function table_store()
    {
        $validatedAttributesTable = request()->validate([
            // 'table_id' => ['required'],
            'table_window' => ['required'],
            'table_status' => ['required', 'in:0,1'],  
            'table_department' => ['required'],
        ]);
        
    
        // Map the form fields to database columns
        $validatedAttributesTable['w_id'] = (string) bin2hex(random_bytes(8));


        $validatedAttributesTable['name'] = $validatedAttributesTable['table_window']; // table_window to name
        $validatedAttributesTable['status'] = $validatedAttributesTable['table_status']; // table_status to status
        $validatedAttributesTable['department'] = $validatedAttributesTable['table_department']; // table_department to department
    
       
       // Check if w_id and department already exist in the Window table
        $pIdExistsTable = \App\Models\WindowList::where('w_id', $validatedAttributesTable['w_id'])
        ->where('department', $validatedAttributesTable['department'])
        ->exists();

        if ($pIdExistsTable) {
        return redirect()->back()->with('error', 'The provided table ID and department combination already exists.')->withInput();
        }


        $wIdExistsTable = \App\Models\WindowList::where('name', $validatedAttributesTable['name'])
        ->where('department', $validatedAttributesTable['department'])
        ->exists();

        if ($wIdExistsTable) {
            return redirect()->back()->with('error', 'The provided table name already exists.')->withInput();
        }
    
        // Create a new record in the Window table
        WindowList::create($validatedAttributesTable);
    
        // Redirect to the personnel function after storing
        return redirect()->route('personnel');
    }

    public function table_destroy($pId){
        // Find the Window record where p_id equals the provided $pId
        $windowList = WindowList::where('id', $pId)->first();
    
        // Check if the window exists
        if ($windowList) {

            // If $windowList->w_id exists, execute the update
            if ($windowList->w_id) {
                $window = \App\Models\Window::where('w_id', $windowList->w_id)->first();
                
                if ($window) {
                    $window->delete();
                }
            }

            // Delete the window record
            $windowList->delete();
    
    
            return redirect()->back()->with('success', 'Window deleted successfully.');
        } else {
            // Handle case where window with the given p_id doesn't exist
            return redirect()->back()->with('error', 'Window not found.');
        }
    }

    public function table_update(Request $request, $pId){
      
        $request->validate([
            // 'edit_table_id' => 'required|max:255',
            'edit_table_window' => 'required|max:255',
            'edit_table_status' => 'required',
            'edit_table_department' => 'required|max:255',
        ]);

        

        // Fetch the Window record
        $windowList = WindowList::findOrFail($pId);
    

        // Check if the updated combination already exists in the database
        $existingRecord1 = WindowList::where('name', $request->edit_table_window)
        ->where('name', $request->edit_table_window)
        ->where('department', $request->edit_table_department)
        ->where('id', '!=', $pId) // Exclude the current record
        ->first();

        if ($existingRecord1) {
            return redirect()->back()->with('error', 'The provided table name already exists.');

        }


        // Update the status of the corresponding WindowList entry
        $window = \App\Models\Window::where('w_id', $windowList->w_id)->where('department', $windowList->department)->first();
        if ($window) {
            $window->w_id = $request->edit_table_id;
            $window->save();
        }

        // Update the model's attributes
        $windowList->w_id = $request->edit_table_id;
        $windowList->name = $request->edit_table_window;
        $windowList->department = $request->edit_table_department;
        $windowList->status = $request->edit_table_status;
    
        // Save the changes
        $windowList->save();


        return redirect()->back()->with('success', 'User updated successfully.');
    }





    // public function p_store(){
    //     $validatedAttributes = request()->validate([
    //         'p_id' => ['required'],
    //         'w_id' => ['required'],
    //         'department' => ['required'],
    //     ]);

    //    // Check if p_id already exists in the Window table
    //     $pIdExists = \App\Models\Window::where('p_id', $validatedAttributes['p_id'])
    //     ->where('department', $validatedAttributes['department'])
    //     ->exists();
    //     if ($pIdExists) {
    //         return redirect()->back()->with('error', 'The provided personnel already exists.')->withInput();
    //     }

    //     // Check if w_id already exists in the Window table
    //     $wIdExists = \App\Models\Window::where('w_id', $validatedAttributes['w_id'])
    //     ->where('department', $validatedAttributes['department'])
    //     ->exists();
    //     if ($wIdExists) {
    //         return redirect()->back()->with('error', 'The provided window already exists.')->withInput();
    //     }

    //     // Create a new record in the Window table
    //     Window::create($validatedAttributes);

    //     // Update the status of the corresponding WindowList entry to 1
    //     $windowList = \App\Models\WindowList::where('w_id', $validatedAttributes['w_id'])->first();
    //     if ($windowList) {
    //         $windowList->status = 1;
    //         $windowList->save();
    //     }

    //     // Redirect to the personnel function after storing
    //     return redirect()->route('personnel');
    // }

    // public function update(Request $request, $pId){
    //     // Validate the incoming request
    //     $request->validate([
    //         'editWindow' => 'required|string|max:255',
    //         'editName' => 'required|string|max:255',
    //         'editDepartment' => 'required|string|max:255',
    //     ]);
    
    //     // Fetch the Window record
    //     $window = Window::findOrFail($pId); // Ensure it throws an exception if not found
    
    //       // Update the status of the corresponding WindowList entry
    //       $windowList = \App\Models\WindowList::where('w_id', $window->w_id)->first();
    //       if ($windowList) {
    //           $windowList->status = 0;
    //           $windowList->save();
    //       }

    //     // Update the model's attributes
    //     $window->w_id = $request->editWindow;
    //     $window->p_id = $request->editName;
    //     $window->department = $request->editDepartment;
    
    //     // Save the changes
    //     $window->save();

    //     // Update the status of the corresponding WindowList entry
    //     $windowList = \App\Models\WindowList::where('w_id', $window->w_id)->first();
    //     if ($windowList) {
    //         $windowList->status = 1;
    //         $windowList->save();
    //     }

       
    
    //     return redirect()->back()->with('success', 'User updated successfully.');
    // }

    // public function destroy($pId){
    //     // Find the Window record where p_id equals the provided $pId
    //     $window = Window::where('p_id', $pId)->first();
    
    //     // Check if the window exists
    //     if ($window) {
    //         // Delete the window record
    //         $window->delete();
    
    //         // Update the status of the corresponding WindowList entry
    //         $windowList = \App\Models\WindowList::where('w_id', $window->w_id)->first();
    //         if ($windowList) {
    //             $windowList->status = 0;
    //             $windowList->save();
    //         }
    //         return redirect()->back()->with('success', 'Window deleted successfully.');
    //     } else {
    //         // Handle case where window with the given p_id doesn't exist
    //         return redirect()->back()->with('error', 'Window not found.');
    //     }
    // }
}
