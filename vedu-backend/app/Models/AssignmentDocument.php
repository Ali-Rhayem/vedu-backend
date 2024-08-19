<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AssignmentDocument extends Model
{
    use HasFactory;
    protected $fillable = ['assignment_id', 'file_url'];
}
