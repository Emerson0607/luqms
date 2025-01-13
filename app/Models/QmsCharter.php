<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsCharter extends Model
{
    protected $table = 'qms_charter';
    protected $fillable = ['id', 'video1','video2', 'dept_id'];
}
