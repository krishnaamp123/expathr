<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkLocation extends Model
{
    use HasFactory;

    protected $table = 'work_locations';

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
