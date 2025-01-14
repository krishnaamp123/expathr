<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Form extends Model
{
    use HasFactory;

    protected $table = 'forms';

    protected $fillable = [
        'form_name',
    ];

    public function questions()
    {
        return $this->hasMany(Question::class, 'id_form');
    }
}
