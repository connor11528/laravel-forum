<?php

namespace App;

use App\Activity;
use App\Filters\ThreadFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    protected $guarded = [];

    protected $with = ['creator', 'channel'];
    
    // Boot the model
    protected static function boot()
    {
        parent::boot();

        // query scope automatically applied to app queries
        static::addGlobalScope('replyCount', function($builder){
            $builder->withCount('replies');
        });

        // delete associated replies when removing thread
        static::deleting(function($thread){
            $thread->replies()->delete();
        });

        // create activity when thread created
        static::created(function($thread){
            $userId = auth()->id() ? auth()->id() : 1;
            
            Activity::create([
                'user_id' => $userId,
                'type' => 'created_thread',
                'subject_id' => $thread->id,
                'subject_type' => 'App\Thread'
            ]);
        });
    }

    public function path()
    {
    	return "/threads/{$this->channel->slug}/{$this->id}";
    }

    public function replies()
    {
    	return $this->hasMany(Reply::class);
    }

    public function creator()
    {
    	return $this->belongsTo(User::class, 'user_id');
    }

    public function channel()
    {
        return $this->belongsTo(Channel::class);
    }

    public function addReply($reply)
    {
        $this->replies()->create($reply); 
    }

    // Apply all the relevant thread filters
    public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }
}
