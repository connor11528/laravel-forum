<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

trait Favoritable
{
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

    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];
        
        // get favorites of reply, find the logged in user's favorite, delete it
        $this->favorites()->where($attributes)->delete();
    }

    public function isFavorited()
    {
        return !! $this->favorites->where('user_id', auth()->id())->count();
    }

    public function getIsFavoritedAttribute()
    {
        return $this->isFavorited();
    }

    public function getFavoritesCountAttribute()
    {
        return $this->favorites->count();
    }
}