<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
    public function course(){
        return $this->belongsTo(Course::class);
    }

    public function assignments(){
        return $this->hasMany(Assignment::class);
    }

    public function resources(){
        return $this->hasMany(ModuleResource::class);
    }
}
