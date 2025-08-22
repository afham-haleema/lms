<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    public $incrementing = false;      // Tell Eloquent there’s no auto-increment id
    protected $primaryKey = null;      // No default primary key

    protected $fillable = [
        'student_id',
        'assignment_id',
        'grade',
        'feedback',
    ];
    public function student(){
        return $this->belongsTo(User::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }


}
