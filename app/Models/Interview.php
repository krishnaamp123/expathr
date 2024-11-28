<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interview extends Model
{
    protected $table = 'interviews';

    protected $fillable = [
        'id_user_job',
        'id_user',
        'interview_date',
        'time',
        'rating',
        'comment',
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
