<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Hrjob extends Model
{
    use HasFactory;

    protected $table = 'hrjobs';

    protected $fillable = [
        'id_category',
        'id_city',
        'job_name',
        'job_type',
        'job_report',
        'price',
        'description',
        'qualification',
        'location_type',
        'experience_min',
        'education_min',
        'expired',
        'number_hired',
        'is_active',
    ];

    public function category()
    {
        return $this->belongsTo(HrjobCategory::class, 'id_category');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'id_city');
    }
}
