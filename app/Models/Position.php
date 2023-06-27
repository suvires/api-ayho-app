<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'name'
    ];

    protected $hidden = ['pivot', 'created_at', 'updated_at'];

    public function offers()
    {
        return $this->belongsToMany(Offer::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
