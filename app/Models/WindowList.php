<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WindowList extends Model
{
    protected $table = 'qms_window_list'; 
    protected $fillable = ['w_id','p_id','department', 'name',  'status'];
}
