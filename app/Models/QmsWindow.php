<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsWindow extends Model
{
    protected $table = 'qms_windows';
    protected $fillable = ['w_name', 'p_id', 'w_status', 'c_name',  'c_number',  'c_status',  'c_service',  'dept_id'];
}
