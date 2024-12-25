<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Interviewer extends Model
{
    protected $table = 'interviewers';

    protected $fillable = [
        'id_interview',
        'id_user',
    ];

    public function interview()
    {
        return $this->belongsTo(Interview::class, 'id_interview');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
