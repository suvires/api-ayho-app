<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OfferUser extends Model 
{
    protected $table = 'offer_user';
    protected $fillable = ['user_id', 'offer_id', 'liked'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class, 'offer_id');
    }
}
