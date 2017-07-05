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
}
