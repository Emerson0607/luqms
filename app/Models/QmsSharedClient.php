<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class QmsSharedClient extends Model
{
    use HasFactory;

    protected $table = 'qms_shared_clients';
    protected $fillable = ['studentNo', 'gName', 'sName', 'dept_id', 'w_name'];
}
