<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;
    protected $fillable = ['course_id', 'title', 'description', 'due_date', 'topic_id'];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function documents()
    {
        return $this->hasMany(AssignmentDocument::class);
    }


    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function topic()
    {
        return $this->belongsTo(Topic::class);
    }
}
