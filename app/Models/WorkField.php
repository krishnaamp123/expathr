<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class WorkField extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_user',
        'id_field',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function field()
    {
        return $this->belongsTo(Field::class, 'id_field');
    }
}
