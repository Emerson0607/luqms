<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Logs;
use App\Models\QmsClientLogs;

class LogsTable extends Component
{
    public $currentTab = 'personnel'; // Default tab
    protected $queryString = ['currentTab'];

    public $currentUserDepartmentId;
    public $currentUserDepartment;

    public function mount()
    {
        // Retrieve from query string or session or default to 'personnel'
        $this->currentTab = request()->query('currentTab', session('currentTab', 'personnel'));
    }

    public function switchTab($tab)
    {
        $this->currentTab = $tab;
        session(['currentTab' => $tab]); 
    }

    public function getLogsProperty()
    {
        $this->currentUserDepartmentId = session('current_department_id');
        $this->currentUserDepartment = session('current_department_name');

        // if ($this->currentTab === 'personnel') {
        //     return Logs::where('department', $this->currentUserDepartment)->get(); 
        // } else {
        //     return QmsClientLogs::query()
        //     ->join('dms_service', 'qms_client_logs.c_service', '=', 'dms_service.service_id')
        //     ->join('dms_userdepts', 'qms_client_logs.p_id', '=', 'dms_userdepts.p_id') 
            
        //     ->select(
        //         'qms_client_logs.*', 
        //         'dms_service.service_name as c_service_name',
        //         'dms_userdepts.firstname',
        //         'dms_userdepts.lastname'  
        //     )
        //     ->where('qms_client_logs.dept_id', $this->currentUserDepartmentId)
        //     ->get();
          

        //     // return QmsClientLogs::where('dept_id',  $this->currentUserDepartmentId)->get(); 
        // }

        if ($this->currentTab === 'personnel') {
            return Logs::where('department', $this->currentUserDepartment)
            ->orderBy('id', 'desc')
            ->get(); 
        } else {
            return QmsClientLogs::query()
                ->join('dms_service', 'qms_client_logs.c_service', '=', 'dms_service.service_id')
                ->join('dms_userdepts', 'qms_client_logs.p_id', '=', 'dms_userdepts.p_id') 
                ->select(
                    'qms_client_logs.*', 
                    'dms_service.service_name as c_service_name',
                    'dms_userdepts.firstname',
                    'dms_userdepts.lastname'
                )
                ->where('qms_client_logs.dept_id', $this->currentUserDepartmentId)
                ->distinct()
                ->orderBy('qms_client_logs.id', 'desc')
                ->get();
        }
        
    }

    public function render()
    {
        return view('livewire.logs-table', [
            'logs' => $this->logs,
            'currentDepartmentName' => session('current_department_name'),
        ]);
    }
}
