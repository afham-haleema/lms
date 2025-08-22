<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{

    protected $casts = [
        'due_date' => 'datetime'
    ];
    protected $fillable = [
        'course_id',
        'module_id',
        'title',
        'description',
        'file_path',
        'due_date',
    ];
    public function submission()
    {
        return $this->hasMany(Submissions::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

}
