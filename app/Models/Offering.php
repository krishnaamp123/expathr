<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offering extends Model
{
    use HasFactory;

    protected $table = 'offerings';

    protected $fillable = [
        'id_user_job',
        'id_job',
        'offering_file',
    ];

    public function userHrjob()
    {
        return $this->belongsTo(UserHrjob::class, 'id_user_job');
    }

    public function hrjob()
    {
        return $this->belongsTo(Hrjob::class, 'id_job');
    }
}
