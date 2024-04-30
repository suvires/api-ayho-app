<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'salary',
        'company_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'company_id',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'offer_user')->withTimestamps();
    }

    public function skills(){
        return $this->belongsToMany(Skill::class);
    }

    public function company(){
        return $this->belongsTo(Company::class);
    }

    public function positions(){
        return $this->belongsToMany(Position::class);
    }

    public function places(){
        return $this->belongsToMany(Place::class);
    }

    public function schedules(){
        return $this->belongsToMany(Schedule::class);
    }

    public function attendances(){
        return $this->belongsToMany(Attendance::class, 'offer_attendance');
    }

}
