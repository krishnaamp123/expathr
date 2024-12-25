<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Link extends Model
{
    use HasFactory;

    protected $table = 'links';

    protected $fillable = [
        'id_user',
        'media',
        'media_url',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
