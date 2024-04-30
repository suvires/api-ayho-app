<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'user_id'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'user_id'
    ];

    public function offers(){
        return $this->hasMany('App\Models\Offer');
    }

    public function users(){
        return $this->belongsToMany('App\Models\User');
    }
}
