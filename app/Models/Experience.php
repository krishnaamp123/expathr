<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;
    protected $fillable = [
        'id_user',
        'position',
        'job_type',
        'company_name',
        'location',
        'location_type',
        'responsibility',
        'job_report',
        'start_date',
        'end_date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
