<?php

namespace App;

use App\Filters\ThreadFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Notifications\ThreadWasUpdated;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    // add these properties to model output
    // adds isSubscribedTo property to the JSON
    protected $appends = ['isSubscribedTo'];
    
    // Boot the model
    protected static function boot()
    {
        parent::boot();

        // delete associated replies when removing thread
        static::deleting(function($thread){        
            $thread->replies->each->delete();
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
        // create and save the reply
        $reply = $this->replies()->create($reply); 

        $this->subscriptions 
            // get subscribers that did not leave the reply
            ->where('user_id', '!=', $reply->user_id)
            // send each of them a notification
            ->each->notify($reply);

        return $reply;
    }

    // Apply all the relevant thread filters
    public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }

    // a thread can have many subscriptions
    public function subscriptions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    // Determine if current user is subscribed to a thread
    // 'custom eloquent accessor'
    public function getIsSubscribedToAttribute()
    {
        return $this->subscriptions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function subscribe($userId = null)
    {
        $this->subscriptions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);

        return $this;
    }

    public function unsubscribe($userId = null)
    {
        $this->subscriptions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();

        return $this;
    }
}
