<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Source extends Model
{
    use HasFactory;

    protected $table = 'sources';

    protected $fillable = [
        'id_user',
        'platform',
        'referal',
        'other',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
