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
        'id_outlet',
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
        'job_closed',
        'is_ended',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($hrjob) {
            if ($hrjob->is_ended === 'yes' && $hrjob->getOriginal('is_ended') === 'no') {
                $hrjob->job_closed = Carbon::now();
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(HrjobCategory::class, 'id_category');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlet::class, 'id_outlet');
    }

    public function city()
    {
        return $this->belongsTo(City::class, 'id_city');
    }
}
