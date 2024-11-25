<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Skill extends Model
{
    use HasFactory;

    protected $table = 'skills';

    protected $fillable = [
        'id_user',
        'skill_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
