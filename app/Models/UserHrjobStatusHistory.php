<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserHrjobStatusHistory extends Model
{
    protected $table = 'user_hrjob_status_histories';

    protected $fillable = [
        'user_hrjob_id',
        'status',
    ];

    public function userHrjob()
    {
        return $this->belongsTo(UserHrjob::class);
    }
}
