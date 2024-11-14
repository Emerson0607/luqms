<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Window extends Model
{
    protected $table = 'qms_window';
    protected $fillable = ['w_id','p_id','department', 'name', 'number', 'status'];
}
