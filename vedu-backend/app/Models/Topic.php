<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'course_id'];

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }
}
