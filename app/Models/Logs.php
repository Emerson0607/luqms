<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Logs extends Model
{
    protected $primaryKey = 'p_id';
    protected $table = 'qms_logs';
    protected $fillable = [
        'p_id', 'p_fname', 'p_lname', 'dept_id', 'time_in', 'time_out', 'date',
    ];
}
