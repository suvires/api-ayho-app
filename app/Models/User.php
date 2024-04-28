<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;
 // class for JWT Authentication In Laravel 11
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable;
    use HasRoles;
    use HasFactory;

    /**
     * Get the unique identifier for the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    /**
     * Return an array of custom claims for the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

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

    protected $with = ['company', 'profile', 'positions', 'skills', 'schedules', 'places', 'attendances'];

    public function offers()
    {
        return $this->belongsToMany(Offer::class, 'offer_user')->withTimestamps();
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'user_position')->withTimestamps();
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, 'user_skill')->withTimestamps();
    }

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class, 'user_schedule')->withTimestamps();
    }

    public function places()
    {
        return $this->belongsToMany(Place::class, 'user_place')->withTimestamps();
    }

    public function attendances()
    {
        return $this->belongsToMany(Attendance::class, 'user_attendance')->withTimestamps();
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }
    public function company()
    {
        return $this->hasOne(Company::class);
    }
}