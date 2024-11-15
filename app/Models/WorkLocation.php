<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WorkLocation extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_user',
        'id_city',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'id_city');
    }
}
