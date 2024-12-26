<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHrjobStatusHistory extends Model
{
    protected $table = 'user_hrjob_status_histories';

    protected $fillable = [
        'id_user_job',
        'status',
    ];

    public function userHrjob()
    {
        return $this->belongsTo(UserHrjob::class);
    }
}
