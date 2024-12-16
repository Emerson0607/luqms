<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsSharedWindow extends Model
{
    protected $table = 'qms_shared_window';
    protected $fillable =['w_name', 'dept_id'];
}