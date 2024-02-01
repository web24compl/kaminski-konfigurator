<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'input',
        'response',
        'tokens',
        'mail',
    ];

    protected $casts = [
        'input' => 'array',
        'response' => 'array',
    ];

}
