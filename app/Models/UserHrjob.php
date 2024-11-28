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
}
