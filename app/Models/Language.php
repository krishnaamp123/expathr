<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_user',
        'language',
        'skill',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
