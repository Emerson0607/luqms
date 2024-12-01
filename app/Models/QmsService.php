<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsService extends Model
{
    protected $table = 'qms_service';
    protected $fillable = ['w_name', 'service_id', 'dept_id'];
}
