<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'description', 'owner_id'];
    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function instructors()
    {
        return $this->belongsToMany(User::class, 'course_instructors');
    }

    public function students()
    {
        return $this->belongsToMany(User::class, 'course_students');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }
    
    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
