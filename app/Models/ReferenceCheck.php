<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferenceCheck extends Model
{
    use HasFactory;

    protected $table = 'reference_checks';

    protected $fillable = [
        'id_user_job',
        'id_reference',
        'comment',
    ];

    public function userHrjob()
    {
        return $this->belongsTo(UserHrjob::class, 'id_user_job');
    }

    public function reference()
    {
        return $this->belongsTo(Reference::class, 'id_reference');
    }
}
