<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserInterview extends Model
{
    protected $table = 'user_interviews';

    protected $fillable = [
        'id_user_job',
        'id_user',
        'interview_date',
        'time',
        'rating',
        'comment',
        'location',
        'link',
        'arrival',
    ];

    public function userHrjob()
    {
        return $this->belongsTo(UserHrjob::class, 'id_user_job');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
