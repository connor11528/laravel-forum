<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ThreadSubscription extends Model
{
	// the attributes that are not mass assignable
    protected $guarded = [];

    public function user()
    {
    	return $this->belongsTo(User::class);
    }
}
