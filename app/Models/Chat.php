<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $fillable = ['offer_id', 'user_id'];

    protected $hidden = ['created_at', 'updated_at', 'offer_id', 'user_id'];

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }
}
