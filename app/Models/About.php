<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class About extends Model
{
    use HasFactory;

    protected $table = 'abouts';

    protected $fillable = [
        'id_user',
        'about',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
