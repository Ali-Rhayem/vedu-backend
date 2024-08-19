<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LiveCodingSession extends Model
{
    use HasFactory;
    protected $fillable = [
        'meeting_id',
        'editor_state',
        'access_control',
    ];

    protected $casts = [
        'editor_state' => 'array', 
        'access_control' => 'array',
    ];

    public function meeting()
    {
        return $this->belongsTo(Meeting::class);
    }
}
