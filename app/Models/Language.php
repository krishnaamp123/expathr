<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Language extends Model
{
    use HasFactory;

    protected $table = 'languages';

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
