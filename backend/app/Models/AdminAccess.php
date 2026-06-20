<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminAccess extends Model
{
    public $timestamps = false;

    protected $fillable = ['key', 'title'];
}
