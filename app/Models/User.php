<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject, MustVerifyEmail
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'id_city',
        'employee_id',
        'email',
        'password',
        'fullname',
        'nickname',
        'phone',
        'address',
        'birth_date',
        'gender',
        'profile_pict',
        'link',
        'role',
    ];

    public function city()
    {
        return $this->belongsTo(City::class, 'id_city');
    }

    public function workLocation()
    {
        return $this->hasMany(WorkLocation::class, 'id_user');
    }

    public function workField()
    {
        return $this->hasMany(WorkField::class, 'id_user');
    }

    public function skill()
    {
        return $this->hasMany(Skill::class, 'id_user');
    }

    public function about()
    {
        return $this->hasMany(About::class, 'id_user');
    }

    public function emergency()
    {
        return $this->hasMany(Emergency::class, 'id_user');
    }

    public function education()
    {
        return $this->hasMany(Education::class, 'id_user');
    }

    public function language()
    {
        return $this->hasMany(Language::class, 'id_user');
    }

    public function project()
    {
        return $this->hasMany(Project::class, 'id_user');
    }

    public function organization()
    {
        return $this->hasMany(Organization::class, 'id_user');
    }

    public function certification()
    {
        return $this->hasMany(Certification::class, 'id_user');
    }

    public function experience()
    {
        return $this->hasMany(Experience::class, 'id_user');
    }

    public function volunteer()
    {
        return $this->hasMany(Volunteer::class, 'id_user');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
