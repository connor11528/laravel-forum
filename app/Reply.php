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
