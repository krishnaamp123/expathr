<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Answer extends Model
{
    use HasFactory;
    protected $table = 'answers';

    protected $fillable = [
        'id_question',
        'answer_name',
        'is_answer',
    ];

    public function question()
    {
        return $this->belongsTo(Question::class, 'id_question');
    }
}
