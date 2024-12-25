<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserInterviewer extends Model
{
    protected $table = 'user_interviewers';

    protected $fillable = [
        'id_user_interview',
        'id_user',
    ];

    public function userInterview()
    {
        return $this->belongsTo(UserInterview::class, 'id_user_interview');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
