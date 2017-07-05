<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    // disable mass assignment exception
    protected $guarded = [];

    public function subject()
    {
    	return $this->morphTo();
    }

    // Fetch activity feed for the given user
    public static function feed($user, $take = 50)
    {
    	return static::where('user_id', $user->id)
    		->latest()
    		->with('subject')
    		->take($take)
    		->get()
    		->groupBy(function($activity){
    		// group by day
    		return $activity->created_at->format('Y-m-d');
    	});
    }
}
