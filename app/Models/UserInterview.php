<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class UserInterview extends Model
{
    protected $table = 'user_interviews';

    protected $fillable = [
        'id_user_job',
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

    public function user_interviewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_interviewers', 'id_user_interview', 'id_user');
    }
}
