<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkSkill extends Model
{
    use HasFactory;

    protected $table = 'work_skills';

    protected $fillable = [
        'id_user',
        'id_skill',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function skill()
    {
        return $this->belongsTo(Skill::class, 'id_skill');
    }
}
