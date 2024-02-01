<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'questions';

    protected $fillable = [
        'question',
        'answers',
    ];

    protected $casts = [
        'answers' => 'array',
    ];
    public function answers() {
        return $this->belongsToMany(\App\Models\Answer::class, 'answers', 'id');
    }
    public function answersMany() {
        return $this->hasMany(\App\Models\Answer::class, 'answers', 'id');
    }
}
