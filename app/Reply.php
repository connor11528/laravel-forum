<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
	protected $guarded = [];

	// A Reply has an owner
    public function owner()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function favorites()
    {
    	// polymorphic relation
    	return $this->morphMany(Favorite::class, 'favorited');
    }

    public function favorite()
    {
        $attributes = ['user_id' => auth()->id()];

        // check that user has not already favorited the reply
        if(! $this->favorites()->where($attributes)->exists()){
            // add favorite
            $this->favorites()->create($attributes);
        }
    }
}
