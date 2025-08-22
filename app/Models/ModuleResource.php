<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleResource extends Model
{
    protected $table='module_resources';
    protected $fillable = [
        'course_id','module_id', 'resource_type', 'title', 'file_path'
    ];

    public function module(){
        return $this->belongsTo(Module::class);
    }

    public function course(){
        return $this->belongsTo(Course::class);
    }
}
