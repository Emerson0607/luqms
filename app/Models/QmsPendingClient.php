<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QmsPendingClient extends Model
{
    protected $table = 'qms_pending_clients';
    protected $fillable = ['studentNo', 'gName', 'sName', 'dept_id', 'w_name', 'isShared'];
}
