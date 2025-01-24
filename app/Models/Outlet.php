<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Outlet extends Model
{
    use HasFactory;

    protected $table = 'outlets';

    protected $fillable = [
        'id_company',
        'outlet_name'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }
}
