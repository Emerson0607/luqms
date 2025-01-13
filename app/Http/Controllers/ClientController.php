<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    protected $currentDepartment;
    protected $currentDepartmentId;

    public function __construct()
    {
        $this->currentDepartment = session('current_department_name');
        $this->currentDepartmentId = session('current_department_id');
    }

    public function updateDepartment(Request $request)
    {
        $request->validate([
            'department_id' => 'required|exists:dms_departments,id',
            'department_name' => 'required|string',
        ]);

        // Update session values for the current department
        session([
            'current_department_id' => $request->department_id,
            'current_department_name' => $request->department_name,
        ]);

        return response()->json(['message' => 'Current department updated successfully!'], 200);
    }

    public function index()
    {
        return view('queue.dashboard', ['current_department_name' => $this->currentDepartment]);
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

}
