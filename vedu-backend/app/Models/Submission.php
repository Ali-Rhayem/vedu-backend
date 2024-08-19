<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;
    protected $fillable = [
        'assignment_id', 'student_id', 'submission_text', 'file_url', 'submitted_at'
    ];

    public function assignment()
    {
        return $this->belongsTo(Assignment::class);
    }
}
