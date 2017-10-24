<?php

namespace App;

use App\Filters\ThreadFilters;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use RecordsActivity;

    protected $guarded = [];

    protected $with = ['creator', 'channel'];

    protected $appends = ['isSubscribedTo'];
    
    // Boot the model
    protected static function boot()
    {
        parent::boot();

        // delete associated replies when removing thread
        static::deleting(function($thread){
            
            $thread->replies->each->delete();

            // same as:
            // $thread->replies->each(function($reply){
            //     $reply->delete();
            // });
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
        return $this->replies()->create($reply); 
    }

    // Apply all the relevant thread filters
    public function scopeFilter($query, ThreadFilters $filters)
    {
        return $filters->apply($query);
    }

    // a thread can have many subscriptions
    public function subscribtions()
    {
        return $this->hasMany(ThreadSubscription::class);
    }

    // Determine if current user is subscribed to a thread
    public function getIsSubscribedToAttribute()
    {
        return $this->subscribtions()
            ->where('user_id', auth()->id())
            ->exists();
    }

    public function subscribe($userId = null)
    {
        $this->subscribtions()->create([
            'user_id' => $userId ?: auth()->id()
        ]);
    }

    public function unsubscribe($userId = null)
    {
        $this->subscribtions()
            ->where('user_id', $userId ?: auth()->id())
            ->delete();
    }
}
