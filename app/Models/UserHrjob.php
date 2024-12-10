<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHrjob extends Model
{
    protected $table = 'user_hrjobs';

    protected $fillable = [
        'id_user',
        'id_job',
        'status',
        'salary_expectation',
        'availability',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function hrjob()
    {
        return $this->belongsTo(Hrjob::class, 'id_job');
    }

    public function answers()
    {
        return $this->hasMany(Answer::class, 'id_user_job');
    }

    public function interviews()
    {
        return $this->hasMany(Interview::class, 'id_user_job');
    }

    public function userInterviews()
    {
        return $this->hasMany(UserInterview::class, 'id_user_job');
    }

    // Tambahkan metode untuk memeriksa apakah pengguna telah melamar pekerjaan tertentu
    public static function hasApplied($userId, $jobId)
    {
        return self::where('id_user', $userId)
                   ->where('id_job', $jobId)
                   ->exists();
    }
}
