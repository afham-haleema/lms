<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Instructor extends Model
{
    protected $fillable = [
        'instructor_name',
        'instructor_phone',
        'instructor_email',
    ];
}
