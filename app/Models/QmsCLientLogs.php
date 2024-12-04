<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsCLientLogs extends Model
{
    protected $table = 'qms_client_logs';
    protected $fillable = ['p_id', 'gName','sName', 'studentNo', 'c_service', 'dept_id', 'date'];
}
