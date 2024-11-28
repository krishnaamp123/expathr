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
        'job_name',
        'job_image',
        'job_type',
        'job_report',
        'price',
        'description',
        'qualification',
        'location_type',
        'location',
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
}
