<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $table = 'interviews';

    protected $fillable = [
        'id_user_job',
        'interview_date',
        'time',
        'rating',
        'comment',
        'location',
        'link',
        'arrival',
        'interviewed',
    ];

    public function userHrjob()
    {
        return $this->belongsTo(UserHrjob::class, 'id_user_job');
    }

    public function interviewers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'interviewers', 'id_interview', 'id_user');
    }
}
