<?php

namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;

use Illuminate\Database\Eloquent\Model;

class Personnel extends Authenticatable
{
        protected $table = 'personnel';

        // Define custom primary key
        protected $primaryKey = 'p_id';  // or whatever column you're using as primary key

        // Indicate whether the primary key is auto-incrementing
        public $incrementing = false;  // If the primary key is not auto-incrementing


}
