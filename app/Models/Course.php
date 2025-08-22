<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    public function modules(){
        return $this->hasMany(Module::class);
    }

    public function instructor(){
        return $this->belongsTo(User::class,'instructor_id');
    }

    public function grade(){
        return $this->hasMany(Grade::class);
    }

    public function users(){
        return $this->belongsToMany(User::class,'course_user')->withPivot('last_accessed_at')->withTimestamps();
    }

    
}
