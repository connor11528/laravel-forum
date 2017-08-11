<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Reply extends Model
{
    // Trait
    use Favoritable, RecordsActivity;

	protected $guarded = [];

    // eager load relationship for every query
    protected $with = ['owner', 'favorites'];

    // append custom attribute (getFavoritesCountAttribute) from Trait
    protected $appends = ['favoritesCount', 'isFavorited'];

	// model event for incrementing thread's replies count
    protected static function boot()
    {
        parent::boot();

        static::created(function($reply){
            $reply->thread->increment('replies_count');
        });

        static::deleted(function($reply){
            $reply->thread->decrement('replies_count');
        });
    }

    // A Reply has an owner
    public function owner()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function thread()
    {
        return $this->belongsTo(Thread::class);
    }

}
