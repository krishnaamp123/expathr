<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $table = 'questions';

    protected $fillable = [
        'id_form',
        'question_name',
    ];

    public function answers()
    {
        return $this->hasMany(Answer::class, 'id_question');
    }

    public function form()
    {
        return $this->belongsTo(Form::class, 'id_form');
    }
}
