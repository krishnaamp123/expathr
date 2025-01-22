<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Hrjob extends Model
{
    use HasFactory;

    protected $table = 'hrjobs';

    protected $fillable = [
        'id_user',
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
        'job_closed',
        'is_ended',
        'hiring_cost',
    ];

    protected $casts = [
        'job_closed' => 'datetime',
        'created_at' => 'datetime',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
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

    public function offerings()
    {
        return $this->hasMany(Offering::class, 'id_job');
    }
}
