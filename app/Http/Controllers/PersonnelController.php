<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

use App\Models\{
    QmsWindow, QmsService, QmsSharedWindow, QmsSharedClient, QmsClients
};

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
        if (!$this->isDepartmentHead()) {
            return redirect()->route('home')->with('error', 'You do not have access to this resource.');
        }
        return view('user.personnel');
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

        // Check if window already exist
        $wIdExists = QmsWindow::where('w_name', $validatedAttributes['w_name'])
            ->where('dept_id', $validatedAttributes['dept_id'])
            ->exists();

        if ($wIdExists) {
            return redirect()->back()->with('sweetalert', 'The provided window already exists.')->withInput();
        }

        // check if user already exist
        $wPIdExists = QmsWindow::where('p_id', $validatedAttributes['p_id'])
            ->where('dept_id', $validatedAttributes['dept_id'])
            ->exists();

        if ($wPIdExists) {
            return redirect()->back()->with('sweetalert', 'A window can only be assigned to one personnel.')->withInput();
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
                Rule::unique('qms_windows', 'w_name')->ignore($pId)->where('dept_id', $request->input('editDeptId')),
            ],
            'editDeptId' => ['required'],
            'editPersonnel' => [
                'required',
                'string',
                'max:255',
                Rule::unique('qms_windows', 'p_id')->ignore($pId)->where('dept_id', $request->input('editDeptId')),
            ],
            'editStatus' => 'required|string|max:255',
            'editShared' => 'required|string|max:255',
            'editService' => ['array'],
            'editService.*' => ['exists:dms_service,service_id'],
        ], [
            'editWName.unique' => 'The window name already exists. Please choose a different name.',
            'editPersonnel.unique' => 'A window can only be assigned to one personnel.',
        ]);
    
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
    
         // Update all QmsWindow records where shared_name matches
        QmsClients::where('w_name', $window->w_name)
            ->where('dept_id', $window->dept_id)
            ->update(['w_name' => $request->editWName]);

        // Update the model's attributes
        $window->w_name = $request->editWName;
        $window->p_id = $request->editPersonnel;
        $window->w_status = $request->editStatus;
        $window->shared_name = $request->editShared;
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

        $qmsServiceWindow = QmsService::where('w_name', $window->w_name)
                              ->where('dept_id', $window->dept_id);

        if ($qmsServiceWindow->exists()) {
            $qmsServiceWindow->delete();
        }

        if ($window) {
            $window->delete();
            return redirect()->back()->with('success', 'Window deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Window not found.');
        }
    }

    // shared window table
    public function shared_store(){
        $validatedAttributes = request()->validate([
            'w_name' => ['required'],
            'dept_id' => ['required'],
        ]);
    
        // Check if w_id already exists in the Window table
        $wIdExists = QmsSharedWindow::where('w_name', $validatedAttributes['w_name'])
            ->where('dept_id', $validatedAttributes['dept_id'])
            ->exists();
    
        if ($wIdExists) {
            return redirect()->back()->with('sweetalert', 'The provided window already exists.')->withInput();
        }
    
        // Create a new record in the Window table
        QmsSharedWindow::create($validatedAttributes);
        return redirect()->route('personnel');
    }

    // Shared detroy
    public function shared_destroy($pId){
        $window = QmsSharedWindow::where('id', $pId)->first();

        if ($window) {
            // Store the window name for reuse
            $windowName = $window->w_name;
            $deptId = $window->dept_id;
            $window->delete();

            // Update all QmsWindow records where shared_name matches
            QmsWindow::where('shared_name', $windowName)
                ->where('dept_id', $deptId)
                ->update(['shared_name' => 'None']);

            // Delete all QmsSharedClient records where shared_name matches
            QmsSharedClient::where('w_name', $windowName)
                ->where('dept_id', $deptId)
                ->delete();
            
            return redirect()->back()->with('success', 'Window deleted successfully.');
        } else {
            return redirect()->back()->with('error', 'Window not found.');
        }
    }

    // Shared update
    public function shared_update(Request $request, $pId)
    {
        // Validate the incoming request
        $request->validate([
            'editSWName' => [
                'required',
                'string',
                'max:255',
                Rule::unique('qms_shared_window', 'w_name')->ignore($pId)->where('dept_id', $request->input('editSDeptId')),
            ],
            'editSDeptId' => ['required'],
          
        ], [
            'editSWName.unique' => 'The shared window name already exists. Please choose a different name.',
        ]);
    
        $window = QmsSharedWindow::findOrFail($pId); 

        // Update all QmsWindow records where shared_name matches
        QmsWindow::where('shared_name', $window->w_name)
            ->where('dept_id', $window->dept_id)
            ->update(['shared_name' => $request->editSWName]);
        
        // Update all QmsWindow records where shared_name matches
        QmsSharedClient::where('w_name', $window->w_name)
            ->where('dept_id', $window->dept_id)
            ->update(['w_name' => $request->editSWName]);
            
        // Update the model's attributes
        $window->w_name = $request->editSWName;
        $window->save();

        return redirect()->back()->with('success', 'Window updated successfully.');
    }
}
