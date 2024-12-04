<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;

class Personnel extends Authenticatable
{
        protected $table = 'personnel';
        protected $primaryKey = 'p_id';
        public $incrementing = false;
}
