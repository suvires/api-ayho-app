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
        'company_id',
        'attendance_id',
        'schedule_id'
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

    public function schedule(){
        return $this->belongsTo(Schedule::class);
    }

    public function attendance(){
        return $this->belongsTo(attendance::class);
    }

}
