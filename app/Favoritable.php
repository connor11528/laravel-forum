<?php 

namespace App;

use Illuminate\Database\Eloquent\Model;

trait Favoritable
{
    // delete favorites when reply is deleted
    protected static function bootFavoritable()
    {
        static::deleting(function($model){
            // get the collection of favorited instances and delete them
            $model->favorites->each->delete();
        });
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

    public function unfavorite()
    {
        $attributes = ['user_id' => auth()->id()];
        
        // get favorites of reply, find the logged in user's favorite, delete it
        // also deletes associated activity (have to call delete on favorites instance itself)
        $this->favorites()->where($attributes)->get()->each->delete();
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