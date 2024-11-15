<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_user',
        'lisence_name',
        'organization',
        'id_credentials',
        'url_credentials',
        'media',
        'description',
        'start_date',
        'end_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
