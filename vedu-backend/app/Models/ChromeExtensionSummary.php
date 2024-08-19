<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChromeExtensionSummary extends Model
{
    use HasFactory;
    protected $fillable = [
        'chat_id',
        'summary',
        'generated_at',
    ];

    public function chat()
    {
        return $this->belongsTo(Chat::class);
    }
}
