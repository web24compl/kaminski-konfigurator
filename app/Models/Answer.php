<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;

    protected $table = 'answers';

    protected $fillable = [
        'answer',
        'parent_question_id',
        'child_question_id',
    ];

    public function parent() {
        return $this->belongsTo(\App\Models\Question::class, 'id', 'parent_question_id');
    }

    public function child() {
        return $this->hasOne(\App\Models\Question::class, 'id', 'child_question_id');
    }
}
