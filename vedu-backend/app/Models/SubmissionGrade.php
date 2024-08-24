<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubmissionGrade extends Model
{
    use HasFactory;

    protected $fillable = ['submission_id', 'grader_id', 'grade', 'feedback'];

}
