<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_user',
        'skill_name',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
