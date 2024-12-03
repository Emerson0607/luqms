<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsCLientLogs extends Model
{
    protected $table = 'qms_client_logs';
    protected $fillable = ['p_id', 'c_name', 'c_number', 'c_service', 'dept_id', 'date'];
}
