<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reference extends Model
{
    use HasFactory;

    protected $table = 'references';

    protected $fillable = [
        'id_user',
        'reference_name',
        'relation',
        'company_name',
        'phone',
        'is_call',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }

    public function referencechecks()
    {
        return $this->hasMany(ReferenceCheck::class, 'id_reference');
    }
}
