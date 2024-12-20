<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Emergency extends Model
{
    use HasFactory;

    protected $table = 'emergencies';

    protected $fillable = [
        'id_user',
        'emergency_name',
        'emergency_relation',
        'emergency_phone',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
