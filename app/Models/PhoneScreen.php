<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PhoneScreen extends Model
{
    use HasFactory;

    protected $table = 'phone_screens';

    protected $fillable = [
        'id_user_job',
        'phonescreen_date',
        'time',
        'location',
        'link',
        'arrival',
    ];

    public function userHrjob()
    {
        return $this->belongsTo(UserHrjob::class, 'id_user_job');
    }
}
