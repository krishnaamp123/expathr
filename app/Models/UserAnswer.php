<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAnswer extends Model
{
    protected $table = 'user_answers';

    protected $fillable = [
        'id_user',
        'id_user_job',
        'id_question',
        'id_answer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function userHrjob()
    {
        return $this->belongsTo(UserHrjob::class, 'id_user_job');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'id_question');
    }

    public function answer()
    {
        return $this->belongsTo(Answer::class, 'id_answer');
    }
}
