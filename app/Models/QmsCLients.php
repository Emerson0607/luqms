<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory; 

class QmsCLients extends Model
{
    use HasFactory;

    protected $table = 'qms_clients';
    protected $fillable = ['studentNo', 'gName', 'sName', 'dept_id'];
}
