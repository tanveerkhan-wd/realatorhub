<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserCardsModel extends Model
{
    protected $table = 'user_cards';
    public $timestamps = true;
    protected $fillable = ['user_id','stripe_id', 'card_last_four'];
}
