<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description'
    ];

    public function offers(){
        return $this->hasMany('App\Models\Offer');
    }

    public function users(){
        return $this->belongsToMany('App\Models\User');
    }
}
