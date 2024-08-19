<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'country',       
        'city',          
        'code',          
        'phone_number',  
        'bio',           
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'country' => 'string',
        'city' => 'string',
        'code' => 'string',
        'phone_number' => 'string',
        'bio' => 'string',
    ];

    public function courses()
    {
        return $this->hasMany(Course::class, 'owner_id');
    }

    public function instructorCourses()
    {
        return $this->belongsToMany(Course::class, 'course_instructors');
    }

    public function studentCourses()
    {
        return $this->belongsToMany(Course::class, 'course_students');
    }

    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class, 'student_id');
    }
}
