<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SkillTest extends Model
{
    protected $table = 'skill_tests';

    protected $fillable = [
        'id_user_job',
        'score',
        'rating',
        'comment',
    ];

    public function userHrjob()
    {
        return $this->belongsTo(UserHrjob::class, 'id_user_job');
    }
}
