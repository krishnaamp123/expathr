<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Form extends Model
{
    protected $table = 'forms';

    protected $fillable = [
        'id_job',
        'id_question',
    ];

    public function hrjob()
    {
        return $this->belongsTo(Hrjob::class, 'id_job');
    }

    public function question()
    {
        return $this->belongsTo(Question::class, 'id_question');
    }

    public function answer()
    {
        return $this->hasMany(Answer::class, 'id_user');
    }
}
